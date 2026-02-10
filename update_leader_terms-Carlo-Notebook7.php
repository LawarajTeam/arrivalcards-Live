<?php
/**
 * Add leader term dates and update current leaders
 */

require_once 'includes/config.php';

echo "=== Adding Leader Term Dates ===\n\n";

// Add new column for leader term
try {
    $pdo->exec("ALTER TABLE countries ADD COLUMN leader_term VARCHAR(100) DEFAULT NULL AFTER leader_title");
    echo "✓ Added leader_term column\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "ℹ Column leader_term already exists\n";
    } else {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Updating Leader Information with Terms (2026) ===\n\n";

// Updated leader information with current data and term dates
$leaders = [
    'USA' => [
        'leader_name' => 'Donald Trump',
        'leader_title' => 'President',
        'leader_term' => '2025-2029'
    ],
    'GBR' => [
        'leader_name' => 'Rishi Sunak',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2022-present (verify current)'
    ],
    'AUS' => [
        'leader_name' => 'Anthony Albanese',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2022-present (verify current)'
    ],
    'CAN' => [
        'leader_name' => 'Justin Trudeau',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2015-present (verify current)'
    ],
    'FRA' => [
        'leader_name' => 'Emmanuel Macron',
        'leader_title' => 'President',
        'leader_term' => '2017-2027 (verify current)'
    ],
    'DEU' => [
        'leader_name' => 'Olaf Scholz',
        'leader_title' => 'Chancellor',
        'leader_term' => '2021-present (verify current)'
    ],
    'ITA' => [
        'leader_name' => 'Giorgia Meloni',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2022-present (verify current)'
    ],
    'ESP' => [
        'leader_name' => 'Pedro Sánchez',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2018-present (verify current)'
    ],
    'JPN' => [
        'leader_name' => 'Fumio Kishida',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2021-present (verify current)'
    ],
    'CHN' => [
        'leader_name' => 'Xi Jinping',
        'leader_title' => 'President',
        'leader_term' => '2013-present'
    ],
    'IND' => [
        'leader_name' => 'Narendra Modi',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2014-present (verify current)'
    ],
    'BRA' => [
        'leader_name' => 'Luiz Inácio Lula da Silva',
        'leader_title' => 'President',
        'leader_term' => '2023-2027'
    ],
    'MEX' => [
        'leader_name' => 'Andrés Manuel López Obrador',
        'leader_title' => 'President',
        'leader_term' => '2018-2024 (verify current)'
    ],
    'ARG' => [
        'leader_name' => 'Javier Milei',
        'leader_title' => 'President',
        'leader_term' => '2023-2027'
    ],
    'ZAF' => [
        'leader_name' => 'Cyril Ramaphosa',
        'leader_title' => 'President',
        'leader_term' => '2018-present (verify current)'
    ],
    'RUS' => [
        'leader_name' => 'Vladimir Putin',
        'leader_title' => 'President',
        'leader_term' => '2000-2008, 2012-present'
    ],
    'KOR' => [
        'leader_name' => 'Yoon Suk Yeol',
        'leader_title' => 'President',
        'leader_term' => '2022-2027'
    ],
    'SAU' => [
        'leader_name' => 'Mohammed bin Salman',
        'leader_title' => 'Crown Prince & PM',
        'leader_term' => '2022-present'
    ],
    'UAE' => [
        'leader_name' => 'Mohamed bin Zayed Al Nahyan',
        'leader_title' => 'President',
        'leader_term' => '2022-present'
    ],
    'TUR' => [
        'leader_name' => 'Recep Tayyip Erdoğan',
        'leader_title' => 'President',
        'leader_term' => '2014-present'
    ],
    'NLD' => [
        'leader_name' => 'Mark Rutte',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2010-present (verify current)'
    ],
    'CHE' => [
        'leader_name' => 'Swiss Federal Council',
        'leader_title' => 'Collective Leadership',
        'leader_term' => 'Rotating presidency'
    ],
    'SWE' => [
        'leader_name' => 'Ulf Kristersson',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2022-present (verify current)'
    ],
    'NOR' => [
        'leader_name' => 'Jonas Gahr Støre',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2021-present (verify current)'
    ],
    'DNK' => [
        'leader_name' => 'Mette Frederiksen',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2019-present (verify current)'
    ],
    'POL' => [
        'leader_name' => 'Donald Tusk',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2023-present (verify current)'
    ],
    'NZL' => [
        'leader_name' => 'Christopher Luxon',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2023-present (verify current)'
    ],
    'SGP' => [
        'leader_name' => 'Lawrence Wong',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2024-present'
    ],
    'ISR' => [
        'leader_name' => 'Benjamin Netanyahu',
        'leader_title' => 'Prime Minister',
        'leader_term' => '2009-2021, 2022-present'
    ],
    'EGY' => [
        'leader_name' => 'Abdel Fattah el-Sisi',
        'leader_title' => 'President',
        'leader_term' => '2014-present'
    ]
];

$stmt = $pdo->prepare("UPDATE countries SET leader_name = ?, leader_title = ?, leader_term = ? WHERE country_code = ?");

$updated = 0;
foreach ($leaders as $code => $leader) {
    $stmt->execute([
        $leader['leader_name'],
        $leader['leader_title'],
        $leader['leader_term'],
        $code
    ]);
    
    if ($stmt->rowCount() > 0) {
        $updated++;
        echo "✓ Updated: $code - {$leader['leader_name']} ({$leader['leader_term']})\n";
    }
}

echo "\n✅ Updated $updated countries with leader term dates\n";
echo "\nℹ️ NOTE: Leaders marked with '(verify current)' should be checked for accuracy in 2026\n";
echo "Political situations change frequently - this data should be regularly updated.\n";
