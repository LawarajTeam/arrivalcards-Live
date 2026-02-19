<?php
/**
 * Populate Bilateral Visa Requirements - Sample Data
 * Purpose: Add sample visa data for 5 passports to test personalization
 * Passports: USA, India, UK, China, UAE
 */

require_once 'includes/config.php';

echo "=== PASSPORT PERSONALIZATION - DATABASE SETUP ===\n\n";

// Step 1: Create tables
echo "Step 1: Creating database tables...\n";

try {
    // Table 1: bilateral_visa_requirements
    echo "  Creating bilateral_visa_requirements table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `bilateral_visa_requirements` (
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `from_country_id` INT NOT NULL,
          `to_country_id` INT NOT NULL,
          `visa_type` ENUM('visa_free', 'visa_on_arrival', 'evisa', 'visa_required', 'no_entry') NOT NULL DEFAULT 'visa_required',
          `duration_days` INT NULL,
          `cost_usd` DECIMAL(10,2) NULL,
          `cost_local_currency` VARCHAR(20) NULL,
          `processing_time_days` INT NULL,
          `requirements_summary` TEXT NULL,
          `application_process` TEXT NULL,
          `special_notes` TEXT NULL,
          `approval_rate_percent` TINYINT NULL,
          `is_verified` BOOLEAN DEFAULT FALSE,
          `data_source` VARCHAR(255) NULL,
          `last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          INDEX `idx_from_country` (`from_country_id`),
          INDEX `idx_to_country` (`to_country_id`),
          INDEX `idx_visa_type` (`visa_type`),
          INDEX `idx_from_to` (`from_country_id`, `to_country_id`),
          UNIQUE KEY `unique_bilateral` (`from_country_id`, `to_country_id`),
          FOREIGN KEY (`from_country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
          FOREIGN KEY (`to_country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "  ✓ bilateral_visa_requirements created\n";
    
    // Table 2: user_preferences
    echo "  Creating user_preferences table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `user_preferences` (
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `session_id` VARCHAR(255) NOT NULL,
          `selected_passport_country_id` INT NULL,
          `last_accessed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          INDEX `idx_session` (`session_id`),
          INDEX `idx_passport` (`selected_passport_country_id`),
          FOREIGN KEY (`selected_passport_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "  ✓ user_preferences created\n";
    
    // Table 3: personalization_stats
    echo "  Creating personalization_stats table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `personalization_stats` (
          `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `passport_country_id` INT NOT NULL,
          `destination_country_id` INT NULL,
          `action_type` ENUM('passport_selected', 'country_viewed', 'filter_used') NOT NULL,
          `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          INDEX `idx_passport_stats` (`passport_country_id`),
          INDEX `idx_destination_stats` (`destination_country_id`),
          INDEX `idx_timestamp` (`timestamp`),
          FOREIGN KEY (`passport_country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
          FOREIGN KEY (`destination_country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "  ✓ personalization_stats created\n";
    
    echo "✓ All tables created successfully!\n\n";
    
} catch (PDOException $e) {
    die("ERROR: Failed to create tables: " . $e->getMessage() . "\n");
}

// Step 2: Get country IDs for our sample passports
echo "Step 2: Fetching country IDs for sample passports...\n";

$samplePassports = ['USA', 'IND', 'GBR', 'CHN', 'ARE']; // ISO codes
$countryIds = [];

foreach ($samplePassports as $code) {
    $stmt = $pdo->prepare("
        SELECT c.id, ct.country_name 
        FROM countries c
        JOIN country_translations ct ON c.id = ct.country_id
        WHERE c.country_code = ? AND ct.lang_code = 'en'
        LIMIT 1
    ");
    $stmt->execute([$code]);
    $country = $stmt->fetch();
    
    if ($country) {
        $countryIds[$code] = $country['id'];
        echo "  ✓ {$country['country_name']} ({$code}): ID = {$country['id']}\n";
    } else {
        echo "  ✗ WARNING: Country code {$code} not found in database!\n";
    }
}

if (count($countryIds) < 5) {
    die("\nERROR: Could not find all sample passport countries. Please check country codes.\n");
}

echo "\n";

// Step 3: Fetch all destination countries
echo "Step 3: Fetching all destination countries...\n";

$stmt = $pdo->query("SELECT id, country_code FROM countries ORDER BY id");
$allCountries = $stmt->fetchAll();

echo "✓ Found " . count($allCountries) . " destination countries\n\n";

// Step 4: Sample visa data structure
echo "Step 4: Preparing sample visa data...\n";

// Define visa rules for our 5 sample passports
// Format: 'FROM_CODE' => ['TO_CODE' => [visa_type, duration, cost, ...]]

$visaRules = [
    'USA' => [
        // USA passport (Rank #1 - very powerful)
        'GBR' => ['type' => 'visa_free', 'days' => 180, 'cost' => 0, 'time' => 0, 'req' => 'Valid passport, return ticket', 'process' => 'Visa-free entry', 'notes' => 'UK-US special relationship', 'approval' => 99],
        'CHN' => ['type' => 'visa_required', 'days' => 30, 'cost' => 140, 'time' => 4, 'req' => 'Passport, application form, photo, itinerary', 'process' => 'Chinese embassy or visa center', 'notes' => 'Tourism (L) visa required', 'approval' => 85],
        'IND' => ['type' => 'evisa', 'days' => 60, 'cost' => 25, 'time' => 3, 'req' => 'Passport scan, photo, travel dates', 'process' => 'Apply online at indianvisaonline.gov.in', 'notes' => 'eVisa available for tourism', 'approval' => 95],
        'ARE' => ['type' => 'visa_free', 'days' => 90, 'cost' => 0, 'time' => 0, 'req' => 'Valid passport, return ticket', 'process' => 'Visa-free entry at all ports', 'notes' => 'Can extend for 90 more days', 'approval' => 99],
        'THA' => ['type' => 'visa_free', 'days' => 30, 'cost' => 0, 'time' => 0, 'req' => 'Valid passport (6mo), return ticket', 'process' => 'Visa-free at airport', 'notes' => 'Can extend 30 days', 'approval' => 99],
    ],
    'IND' => [
        // India passport (Rank #83 - moderate power)
        'GBR' => ['type' => 'visa_required', 'days' => 180, 'cost' => 115, 'time' => 21, 'req' => 'Application, passport, bank statements, ITR, employment letter', 'process' => 'VFS Global centers across India', 'notes' => 'Standard visitor visa £95', 'approval' => 90],
        'USA' => ['type' => 'visa_required', 'days' => 3650, 'cost' => 185, 'time' => 120, 'req' => 'DS-160, interview, bank statements, ITR (3 years), employment proof', 'process' => 'US consulate interview mandatory', 'notes' => 'Interview wait time: 400+ days currently', 'approval' => 75],
        'CHN' => ['type' => 'visa_required', 'days' => 30, 'cost' => 65, 'time' => 5, 'req' => 'Application, passport, confirmed hotel/flight bookings, bank statements', 'process' => 'Chinese visa centers in 4 Indian cities', 'notes' => 'Strict documentation required', 'approval' => 80],
        'ARE' => ['type' => 'evisa', 'days' => 60, 'cost' => 60, 'time' => 4, 'req' => 'Passport, photo, hotel booking, return ticket', 'process' => 'Online through authorized agents', 'notes' => '3.5M+ Indians visit yearly', 'approval' => 92],
        'THA' => ['type' => 'evisa', 'days' => 60, 'cost' => 40, 'time' => 4, 'req' => 'Passport (6mo), photo, flight, hotel booking, bank statement', 'process' => 'Thailand eVisa portal', 'notes' => '1M+ Indians visit annually', 'approval' => 95],
        'NPL' => ['type' => 'visa_free', 'days' => 0, 'cost' => 0, 'time' => 0, 'req' => 'Passport OR Voter ID/Aadhaar', 'process' => 'Free entry at all borders', 'notes' => 'Indo-Nepal treaty - unlimited stay, can work', 'approval' => 100],
        'BTN' => ['type' => 'visa_free', 'days' => 14, 'cost' => 0, 'time' => 0, 'req' => 'Passport OR Voter ID', 'process' => 'Entry permit at border', 'notes' => 'No SDF fee! Others pay $200/day + tour', 'approval' => 100],
        'LKA' => ['type' => 'visa_free', 'days' => 30, 'cost' => 0, 'time' => 0, 'req' => 'Passport (6mo), return ticket', 'process' => 'Free entry since March 2024', 'notes' => 'NEW! Was $50 eVisa before', 'approval' => 99],
        'MUS' => ['type' => 'visa_free', 'days' => 60, 'cost' => 0, 'time' => 0, 'req' => 'Passport (6mo), return ticket, sufficient funds', 'process' => 'Free entry at all ports', 'notes' => '68% Indian diaspora, Hindi spoken', 'approval' => 99],
    ],
    'GBR' => [
        // UK passport (Rank #2 - very powerful)
        'USA' => ['type' => 'visa_free', 'days' => 90, 'cost' => 0, 'time' => 0, 'req' => 'Valid passport, ESTA authorization', 'process' => 'ESTA ($21) online before travel', 'notes' => 'Part of Visa Waiver Program', 'approval' => 99],
        'CHN' => ['type' => 'visa_required', 'days' => 30, 'cost' => 126, 'time' => 5, 'req' => 'Passport, application, photo, itinerary, accommodation proof', 'process' => 'Chinese visa center', 'notes' => 'Tourism (L) visa', 'approval' => 90],
        'IND' => ['type' => 'evisa', 'days' => 60, 'cost' => 25, 'time' => 3, 'req' => 'Passport scan, photo, travel plans', 'process' => 'Online eVisa portal', 'notes' => 'eVisa for tourism', 'approval' => 95],
        'ARE' => ['type' => 'visa_free', 'days' => 90, 'cost' => 0, 'time' => 0, 'req' => 'Valid passport', 'process' => 'Free entry at all airports', 'notes' => 'Automatic visa on entry', 'approval' => 99],
        'THA' => ['type' => 'visa_free', 'days' => 60, 'cost' => 0, 'time' => 0, 'req' => 'Valid passport (6 months)', 'process' => 'Visa-free entry', 'notes' => 'Extended from 30 to 60 days', 'approval' => 99],
    ],
    'CHN' => [
        // China passport (Rank #62 - moderate)
        'USA' => ['type' => 'visa_required', 'days' => 3650, 'cost' => 185, 'time' => 30, 'req' => 'DS-160, interview, bank statements, employment proof', 'process' => 'US embassy interview', 'notes' => 'B1/B2 tourist visa', 'approval' => 83],
        'GBR' => ['type' => 'visa_required', 'days' => 180, 'cost' => 115, 'time' => 21, 'req' => 'Application, passport, bank statements, travel insurance', 'process' => 'UK visa center', 'notes' => 'Standard visitor visa', 'approval' => 88],
        'IND' => ['type' => 'evisa', 'days' => 60, 'cost' => 25, 'time' => 3, 'req' => 'Passport, photo, travel details', 'process' => 'Online eVisa', 'notes' => 'eVisa available', 'approval' => 92],
        'ARE' => ['type' => 'visa_on_arrival', 'days' => 30, 'cost' => 60, 'time' => 0, 'req' => 'Passport, return ticket, hotel booking', 'process' => 'Visa on arrival at airport', 'notes' => 'Pay at airport', 'approval' => 95],
        'THA' => ['type' => 'visa_on_arrival', 'days' => 15, 'cost' => 50, 'time' => 0, 'req' => 'Passport, photo, 10,000 THB cash', 'process' => 'Visa on arrival counter', 'notes' => 'Short 15-day stay only', 'approval' => 95],
    ],
    'ARE' => [
        // UAE passport (Rank #12 - strong)
        'USA' => ['type' => 'visa_required', 'days' => 3650, 'cost' => 185, 'time' => 30, 'req' => 'DS-160, interview, documents', 'process' => 'US embassy Abu Dhabi/Dubai', 'notes' => 'B1/B2 visa required', 'approval' => 88],
        'GBR' => ['type' => 'visa_free', 'days' => 180, 'cost' => 0, 'time' => 0, 'req' => 'Valid passport', 'process' => 'Visa-free entry', 'notes' => 'UK standard visitor', 'approval' => 99],
        'CHN' => ['type' => 'visa_required', 'days' => 30, 'cost' => 126, 'time' => 5, 'req' => 'Application, passport, itinerary', 'process' => 'Chinese visa center', 'notes' => 'Tourism visa needed', 'approval' => 85],
        'IND' => ['type' => 'evisa', 'days' => 60, 'cost' => 25, 'time' => 3, 'req' => 'Passport, photo', 'process' => 'Online eVisa', 'notes' => 'eVisa available', 'approval' => 95],
        'THA' => ['type' => 'visa_free', 'days' => 60, 'cost' => 0, 'time' => 0, 'req' => 'Valid passport', 'process' => 'Visa-free entry', 'notes' => 'Tourism and business', 'approval' => 99],
    ],
];

echo "✓ Sample visa rules prepared\n\n";

// Step 5: Insert sample data
echo "Step 5: Inserting sample bilateral visa data...\n";

$insertCount = 0;
$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare("
        INSERT INTO bilateral_visa_requirements 
        (from_country_id, to_country_id, visa_type, duration_days, cost_usd, 
         processing_time_days, requirements_summary, application_process, 
         special_notes, approval_rate_percent, is_verified, data_source)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            visa_type = VALUES(visa_type),
            duration_days = VALUES(duration_days),
            cost_usd = VALUES(cost_usd),
            processing_time_days = VALUES(processing_time_days),
            requirements_summary = VALUES(requirements_summary),
            application_process = VALUES(application_process),
            special_notes = VALUES(special_notes),
            approval_rate_percent = VALUES(approval_rate_percent),
            last_updated = CURRENT_TIMESTAMP
    ");
    
    foreach ($visaRules as $fromCode => $destinations) {
        $fromId = $countryIds[$fromCode];
        
        foreach ($destinations as $toCode => $rule) {
            // Find destination country ID
            $toId = null;
            foreach ($allCountries as $country) {
                if ($country['country_code'] === $toCode) {
                    $toId = $country['id'];
                    break;
                }
            }
            
            if (!$toId) {
                echo "  ! Skipping {$fromCode} → {$toCode} (destination not found)\n";
                continue;
            }
            
            $stmt->execute([
                $fromId,
                $toId,
                $rule['type'],
                $rule['days'] > 0 ? $rule['days'] : null,
                $rule['cost'] > 0 ? $rule['cost'] : null,
                $rule['time'] > 0 ? $rule['time'] : null,
                $rule['req'],
                $rule['process'],
                $rule['notes'],
                $rule['approval'],
                1, // is_verified
                'Manual research - Sample data'
            ]);
            
            $insertCount++;
        }
    }
    
    $pdo->commit();
    echo "✓ Inserted {$insertCount} bilateral visa records\n\n";
    
} catch (PDOException $e) {
    $pdo->rollBack();
    die("ERROR: Failed to insert data: " . $e->getMessage() . "\n");
}

// Step 6: Verification
echo "Step 6: Verifying data...\n\n";

foreach ($samplePassports as $code) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count, visa_type, COUNT(*) as type_count
        FROM bilateral_visa_requirements
        WHERE from_country_id = ?
        GROUP BY visa_type
    ");
    $stmt->execute([$countryIds[$code]]);
    $stats = $stmt->fetchAll();
    
    $stmt2 = $pdo->prepare("
        SELECT COUNT(*) as total
        FROM bilateral_visa_requirements
        WHERE from_country_id = ?
    ");
    $stmt2->execute([$countryIds[$code]]);
    $total = $stmt2->fetch()['total'];
    
    echo "  {$code} Passport: {$total} destinations\n";
    foreach ($stats as $stat) {
        echo "    - {$stat['visa_type']}: {$stat['type_count']}\n";
    }
    echo "\n";
}

// Final summary
echo "=== SETUP COMPLETE ===\n\n";
echo "✓ Database tables created\n";
echo "✓ Sample data populated for 5 passports\n";
echo "✓ Total records: {$insertCount}\n\n";
echo "Next steps:\n";
echo "  1. Create API endpoint: api/get_personalized_visa_requirements.php\n";
echo "  2. Build passport selector UI\n";
echo "  3. Update index.php and country.php\n";
echo "  4. Test personalization feature\n\n";
echo "To test API later: /api/get_personalized_visa_requirements.php?passport=USA\n\n";

?>
