<?php
/**
 * Homepage - Country Listings
 * Displays all countries with search and filter functionality
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/adsense_functions.php';
require_once __DIR__ . '/includes/analytics_functions.php';

$pageTitle = 'Visa Requirements & Arrival Cards for 156 Countries | Arrival Cards';
$pageDescription = 'Free visa information, entry requirements, and arrival card details for 156 countries worldwide. Find out if you need a visa, eVisa, or visa on arrival in 6 languages.';
$pageKeywords = 'visa requirements, arrival cards, visa information, travel visa, eVisa, visa on arrival, visa free countries, international travel, passport requirements';

// Track page view
trackPageView(null, $pageTitle);

// Get countries
$countries = getCountries();
$regions = getRegions();
$visaTypes = getVisaTypes();
$totalCountries = getCountryCount();

// Generate JSON-LD structured data for SEO
$structuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'Arrival Cards',
    'url' => APP_URL,
    'description' => $pageDescription,
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => APP_URL . '/index.php?search={search_term_string}',
        'query-input' => 'required name=search_term_string'
    ]
];

$organizationData = [
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'Arrival Cards',
    'url' => APP_URL,
    'logo' => APP_URL . '/assets/images/logo.svg',
    'description' => 'Global visa information and travel requirements resource',
    'sameAs' => []
];

include __DIR__ . '/includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-background">
        <div class="hero-city-layer city-layer-1"></div>
        <div class="hero-city-layer city-layer-2"></div>
        <div class="hero-city-layer city-layer-3"></div>
        <div class="hero-plane">✈️</div>
        <div class="hero-overlay"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                </svg>
                <?php echo e(t('hero_badge')); ?>
            </div>
            <h1 class="hero-title"><?php echo e(t('site_title')); ?></h1>
            <p class="hero-subtitle"><?php echo e(t('site_tagline')); ?></p>
        </div>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="search-filter-section" role="search" aria-label="<?php echo e(t('search_and_filter')); ?>">
    <div class="container">
        <h2 class="visually-hidden"><?php echo e(t('search_visa_requirements')); ?></h2>
        <div class="search-filter-container">
            <!-- Search Box -->
            <div class="search-box">
                <svg class="search-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
                <input 
                    type="text" 
                    class="search-input" 
                    id="search-input" 
                    placeholder="<?php echo e(t('search_placeholder')); ?>"
                    aria-label="<?php echo e(t('search_placeholder')); ?>"
                    autocomplete="off"
                >
            </div>
            
            <!-- Filters -->
            <div class="filter-group">
                <!-- Region Filter -->
                <select class="filter-select" id="region-filter" aria-label="<?php echo e(t('filter_by_region')); ?>">
                    <option value=""><?php echo e(t('all_regions')); ?></option>
                    <?php foreach ($regions as $region): ?>
                        <option value="<?php echo e($region); ?>"><?php echo e($region); ?></option>
                    <?php endforeach; ?>
                </select>
                
                <!-- Visa Type Filter -->
                <select class="filter-select" id="visa-filter" aria-label="<?php echo e(t('filter_by_visa_type')); ?>">
                    <option value=""><?php echo e(t('all_visa_types')); ?></option>
                    <?php foreach ($visaTypes as $key => $label): ?>
                        <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Countries Grid -->
<section class="countries-section" itemscope itemtype="https://schema.org/ItemList">
    <div class="container">
        <h2 class="text-center"><?php echo e(t('browse_countries_title')); ?> <?php echo $totalCountries; ?> <?php echo e(t('countries')); ?></h2>
        <meta itemprop="numberOfItems" content="<?php echo $totalCountries; ?>">
        
        <div class="countries-grid">
            <?php 
            // Generate randomized ad positions for this page load
            $totalCountries = count($countries);
            $adPositions = [];
            $panelPositions = [];
            
            // First ad card appears after 12-18 cards (randomized)
            $firstAdPosition = rand(12, 18);
            $adPositions[] = $firstAdPosition;
            
            // Add more ad cards at random intervals (every 25-40 cards)
            $nextAdPosition = $firstAdPosition + rand(25, 40);
            while ($nextAdPosition < $totalCountries) {
                $adPositions[] = $nextAdPosition;
                $nextAdPosition += rand(25, 40);
            }
            
            // Add 2-3 panel ads at strategic positions
            $numPanels = rand(2, 3);
            for ($i = 0; $i < $numPanels; $i++) {
                $panelPosition = rand(30 + ($i * 50), 80 + ($i * 50));
                if ($panelPosition < $totalCountries) {
                    $panelPositions[] = $panelPosition;
                }
            }
            
            $counter = 0;
            $initialLoad = 30; // Show first 30 countries immediately
            foreach ($countries as $country): 
                $counter++;
                $isInitialLoad = ($counter <= $initialLoad);
                $lazyClass = $isInitialLoad ? '' : ' lazy-load';
                $displayStyle = $isInitialLoad ? '' : ' style="display:none"';
                
                // Check if we should insert ad card at this position
                if (in_array($counter, $adPositions)) {
                    echo displayAdCard();
                }
                
                // Check if we should insert panel ad at this position
                if (in_array($counter, $panelPositions)) {
                    echo '</div>' . displayAdPanel('ad_slot_landing_middle') . '<div class="countries-grid">';
                }
            ?>
                <article 
                    class="country-card<?php echo $lazyClass; ?>"
                    data-name="<?php echo e($country['country_name']); ?>"
                    data-region="<?php echo e($country['region']); ?>"
                    data-visa-type="<?php echo e($country['visa_type']); ?>"<?php echo $displayStyle; ?>
                >
                    <div class="country-header">
                        <?php 
                        $flagCode = strtolower(getCountryCode2Letter($country['country_code']));
                        $flagPath = APP_URL . '/assets/images/flags/' . $flagCode . '.svg';
                        // Always use img with fallback - browser will handle missing images faster
                        echo '<img src="' . $flagPath . '" alt="' . e($country['country_name']) . ' flag" class="country-flag-img" loading="lazy" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'inline-flex\'">';
                        echo '<span class="country-code-flag" style="display:none" role="img" aria-label="' . e($country['country_name']) . ' country code">' . strtoupper($flagCode) . '</span>';
                        ?>
                        <h3 class="country-name"><?php echo e($country['country_name']); ?></h3>
                    </div>
                    
                    <div class="country-badges">
                        <?php if (!empty($country['is_popular'])): ?>
                            <span class="popular-badge">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <?php echo e(t('popular')); ?>
                            </span>
                        <?php endif; ?>
                        <span class="visa-type-badge <?php echo getVisaTypeBadgeClass($country['visa_type']); ?>">
                            <?php echo e($visaTypes[$country['visa_type']]); ?>
                        </span>
                    </div>
                    
                    <div class="country-info-box">
                        <p class="country-summary">
                            <?php echo e(truncate($country['entry_summary'], 120)); ?>
                        </p>
                        
                        <div class="visa-quick-facts">
                            <?php
                            // Generate quick visa facts based on visa type
                            $visaFacts = [];
                            switch($country['visa_type']) {
                                case 'visa_free':
                                    $visaFacts = [
                                        ['icon' => '✓', 'label' => t('visa_fact_no_visa_required'), 'class' => 'success'],
                                        ['icon' => '📅', 'label' => t('visa_fact_typically_days'), 'class' => 'info'],
                                        ['icon' => '⚡', 'label' => t('visa_fact_entry_on_arrival'), 'class' => 'success']
                                    ];
                                    break;
                                case 'visa_on_arrival':
                                    $visaFacts = [
                                        ['icon' => '🎫', 'label' => t('visa_fact_at_airport_border'), 'class' => 'warning'],
                                        ['icon' => '⏱️', 'label' => t('visa_fact_issued_immediately'), 'class' => 'info'],
                                        ['icon' => '💰', 'label' => t('visa_fact_fee_on_arrival'), 'class' => 'warning']
                                    ];
                                    break;
                                case 'evisa':
                                    $visaFacts = [
                                        ['icon' => '💻', 'label' => t('visa_fact_apply_online'), 'class' => 'info'],
                                        ['icon' => '⏳', 'label' => t('visa_fact_processing_days'), 'class' => 'warning'],
                                        ['icon' => '📧', 'label' => t('visa_fact_approved_email'), 'class' => 'success']
                                    ];
                                    break;
                                case 'visa_required':
                                    $visaFacts = [
                                        ['icon' => '🏢', 'label' => t('visa_fact_embassy_visit'), 'class' => 'danger'],
                                        ['icon' => '📋', 'label' => t('visa_fact_documents_required'), 'class' => 'warning'],
                                        ['icon' => '⏰', 'label' => t('visa_fact_apply_advance'), 'class' => 'danger']
                                    ];
                                    break;
                            }
                            ?>
                            
                            <?php foreach ($visaFacts as $fact): ?>
                                <div class="visa-fact visa-fact-<?php echo $fact['class']; ?>">
                                    <span class="visa-fact-icon"><?php echo $fact['icon']; ?></span>
                                    <span class="visa-fact-label"><?php echo e($fact['label']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Feedback Section -->
                    <div class="country-feedback">
                        <span class="feedback-label"><?php echo e(t('was_this_helpful')); ?></span>
                        <div class="feedback-buttons">
                            <button 
                                class="feedback-btn feedback-btn-yes" 
                                data-country-id="<?php echo $country['id']; ?>"
                                data-type="helpful"
                                aria-label="Mark as helpful"
                            >
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                </svg>
                                <span class="feedback-count" data-count-type="yes"><?php echo (int)$country['helpful_yes']; ?></span>
                            </button>
                            <button 
                                class="feedback-btn feedback-btn-no" 
                                data-country-id="<?php echo $country['id']; ?>"
                                data-type="not_helpful"
                                aria-label="Mark as not helpful"
                            >
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z"/>
                                </svg>
                                <span class="feedback-count" data-count-type="no"><?php echo (int)$country['helpful_no']; ?></span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="country-footer">
                        <div class="country-meta">
                            <span class="country-meta-left">
                                <?php echo e(t('last_updated')); ?>: <?php echo formatDate($country['last_verified']); ?>
                            </span>
                            <div class="view-counter" title="<?php echo number_format($country['view_count']); ?> views">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><?php echo number_format($country['view_count']); ?></span>
                            </div>
                        </div>
                        <a 
                            href="country.php?id=<?php echo $country['id']; ?>&lang=<?php echo CURRENT_LANG; ?>" 
                            class="btn btn-primary btn-sm btn-full"
                            aria-label="<?php echo e(t('view_details')); ?> - <?php echo e($country['country_name']); ?>"
                        >
                            <?php echo e(t('view_details')); ?>
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        
        <!-- No Results Message -->
        <div class="no-results" style="display: none;">
            <div class="no-results-icon">🔍</div>
            <h3><?php echo e(t('no_results')); ?></h3>
            <p><?php echo e(t('no_results_message')); ?></p>
        </div>
    </div>
</section>

<!-- Bottom Ad Panel -->
<?php echo displayAdPanel('ad_slot_landing_bottom'); ?>

<!-- Initialize AdSense Ads -->
<script>
<?php if (isAdSenseEnabled()): ?>
    // Initialize all AdSense ads on the page
    (adsbygoogle = window.adsbygoogle || []).push({});
<?php endif; ?>

// Lazy load remaining countries for better performance
(function() {
    const lazyLoadCountries = () => {
        const lazyCards = document.querySelectorAll('.country-card.lazy-load');
        if (lazyCards.length === 0) return;
        
        // Show cards in smaller batches with fade-in animation
        let index = 0;
        const batchSize = 15;
        
        const showBatch = () => {
            const end = Math.min(index + batchSize, lazyCards.length);
            for (let i = index; i < end; i++) {
                lazyCards[i].style.display = '';
                lazyCards[i].style.animation = 'fadeIn 0.4s ease-in';
                lazyCards[i].classList.remove('lazy-load');
            }
            index = end;
            
            if (index < lazyCards.length) {
                requestAnimationFrame(() => setTimeout(showBatch, 50));
            }
        };
        
        // Start loading after initial render
        requestAnimationFrame(() => setTimeout(showBatch, 150));
    };
    
    // Start lazy loading when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', lazyLoadCountries);
    } else {
        lazyLoadCountries();
    }
})();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
