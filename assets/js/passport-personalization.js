/**
 * Passport Personalization Engine
 * Handles passport selection and dynamic visa requirement updates
 */

(function() {
    'use strict';
    
    // Configuration
    const CONFIG = {
        API_URL: '/api/get_personalized_visa_requirements.php',
        STORAGE_KEY: 'selected_passport',
        CACHE_DURATION: 3600000, // 1 hour in milliseconds
        ANIMATION_DURATION: 300
    };
    
    // State management
    const state = {
        selectedPassport: null,
        personalizedData: null,
        isLoading: false,
        countries: []
    };
    
    /**
     * Initialize personalization system
     */
    function init() {
        console.log('🛂 Initializing Passport Personalization...');
        
        // Load saved passport from localStorage
        loadSavedPassport();
        
        // Setup passport selector if on homepage
        if (document.querySelector('.countries-grid')) {
            setupPassportSelector();
        }
        
        // Setup event listeners
        setupEventListeners();
        
        // If passport was previously selected, load personalized data
        if (state.selectedPassport) {
            loadPersonalizedData(state.selectedPassport);
        }
        
        console.log('✅ Passport Personalization initialized');
    }
    
    /**
     * Load saved passport from localStorage
     */
    function loadSavedPassport() {
        try {
            const saved = localStorage.getItem(CONFIG.STORAGE_KEY);
            if (saved) {
                const data = JSON.parse(saved);
                const age = Date.now() - data.timestamp;
                
                // Check if cache is still valid
                if (age < CONFIG.CACHE_DURATION) {
                    state.selectedPassport = data.passport;
                    console.log('📦 Loaded saved passport:', data.passport.name);
                } else {
                    // Clear expired data
                    localStorage.removeItem(CONFIG.STORAGE_KEY);
                    console.log('🗑️ Cleared expired passport selection');
                }
            }
        } catch (error) {
            console.error('Error loading saved passport:', error);
            localStorage.removeItem(CONFIG.STORAGE_KEY);
        }
    }
    
    /**
     * Save passport selection to localStorage
     */
    function savePassport(passport) {
        try {
            localStorage.setItem(CONFIG.STORAGE_KEY, JSON.stringify({
                passport: passport,
                timestamp: Date.now()
            }));
        } catch (error) {
            console.error('Error saving passport:', error);
        }
    }
    
    /**
     * Create and inject passport selector UI
     */
    function setupPassportSelector() {
        const searchFilterSection = document.querySelector('.search-filter-container');
        if (!searchFilterSection) return;
        
        // Create passport selector container
        const selectorHTML = `
            <div class="passport-selector-wrapper" id="passport-selector-wrapper">
                <div class="passport-selector-header">
                    <svg class="passport-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="4" width="18" height="16" rx="2" ry="2" stroke-width="2"/>
                        <circle cx="12" cy="10" r="2" fill="currentColor"/>
                        <path d="M9 15c0-1.5 1.34-3 3-3s3 1.5 3 3" stroke-width="2"/>
                    </svg>
                    <label for="passport-select" class="passport-label">
                        Select Your Passport:
                    </label>
                </div>
                <div class="passport-selector-content">
                    <select id="passport-select" class="passport-select">
                        <option value="">🌍 Select your passport nationality...</option>
                    </select>
                    <button id="clear-passport" class="btn-clear-passport" style="display:none" title="Clear selection">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Personalization Stats Banner (hidden by default) -->
            <div class="personalization-banner" id="personalization-banner" style="display:none">
                <div class="banner-content">
                    <div class="banner-passport-info">
                        <span class="banner-flag" id="banner-flag"></span>
                        <span class="banner-text">
                            Viewing as: <strong id="banner-passport-name"></strong>
                        </span>
                    </div>
                    <div class="banner-stats" id="banner-stats">
                        <div class="stat-item stat-visa-free">
                            <span class="stat-number" id="stat-visa-free">0</span>
                            <span class="stat-label">Visa Free</span>
                        </div>
                        <div class="stat-item stat-voa">
                            <span class="stat-number" id="stat-voa">0</span>
                            <span class="stat-label">On Arrival</span>
                        </div>
                        <div class="stat-item stat-evisa">
                            <span class="stat-number" id="stat-evisa">0</span>
                            <span class="stat-label">eVisa</span>
                        </div>
                        <div class="stat-item stat-required">
                            <span class="stat-number" id="stat-required">0</span>
                            <span class="stat-label">Visa Req'd</span>
                        </div>
                    </div>
                    <div class="banner-easy-access">
                        ✨ <span id="easy-access-count">0</span> destinations without advance visa!
                    </div>
                </div>
            </div>
        `;
        
        searchFilterSection.insertAdjacentHTML('afterend', selectorHTML);
        
        // Populate passport options
        populatePassportOptions();
        
        // If passport already selected, update UI
        if (state.selectedPassport) {
            const select = document.getElementById('passport-select');
            select.value = state.selectedPassport.code;
            showClearButton();
        }
    }
    
    /**
     * Populate passport selector with all countries
     */
    async function populatePassportOptions() {
        const select = document.getElementById('passport-select');
        if (!select) return;
        
        try {
            // Fetch all countries from API
            const response = await fetch('/api/get_countries.php');
            const data = await response.json();
            
            if (!data.success) {
                throw new Error('Failed to load countries');
            }
            
            // Add options
            data.countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.country_code;
                option.textContent = `${country.flag_emoji} ${country.country_name}`;
                option.dataset.name = country.country_name;
                option.dataset.flag = country.flag_emoji;
                option.dataset.code = country.country_code;
                select.appendChild(option);
            });
            
            // If passport was previously selected, restore it
            if (state.selectedPassport && state.selectedPassport.code) {
                select.value = state.selectedPassport.code;
                showClearButton();
            }
            
        } catch (error) {
            console.error('Error loading countries:', error);
        }
    }
    
    /**
     * Setup event listeners
     */
    function setupEventListeners() {
        // Passport selector change
        const select = document.getElementById('passport-select');
        if (select) {
            select.addEventListener('change', handlePassportChange);
        }
        
        // Clear button
        const clearBtn = document.getElementById('clear-passport');
        if (clearBtn) {
            clearBtn.addEventListener('click', clearPassportSelection);
        }
    }
    
    /**
     * Handle passport selection change
     */
    async function handlePassportChange(event) {
        const select = event.target;
        const selectedOption = select.options[select.selectedIndex];
        
        if (!selectedOption.value) {
            clearPassportSelection();
            return;
        }
        
        const passport = {
            code: selectedOption.value,
            name: selectedOption.dataset.name,
            flag: selectedOption.dataset.flag
        };
        
        state.selectedPassport = passport;
        savePassport(passport);
        showClearButton();
        
        // Load personalized data
        await loadPersonalizedData(passport);
        
        // Track selection
        trackPassportSelection(passport);
    }
    
    /**
     * Load personalized visa data from API
     */
    async function loadPersonalizedData(passport) {
        if (state.isLoading) return;
        
        state.isLoading = true;
        showLoadingState();
        
        try {
            const response = await fetch(`${CONFIG.API_URL}?passport=${passport.code}`);
            
            if (!response.ok) {
                throw new Error(`API error: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.error || 'Unknown API error');
            }
            
            state.personalizedData = data;
            
            // Update UI with personalized data
            updateUI(data);
            
            console.log('✅ Loaded personalized data:', data.statistics);
            
        } catch (error) {
            console.error('Error loading personalized data:', error);
            showError('Failed to load personalized visa information. Please try again.');
        } finally {
            state.isLoading = false;
            hideLoadingState();
        }
    }
    
    /**
     * Update UI with personalized data
     */
    function updateUI(data) {
        // Update banner
        updateBanner(data);
        
        // Update country cards
        updateCountryCards(data.destinations);
        
        // Show banner
        showBanner();
    }
    
    /**
     * Update personalization banner
     */
    function updateBanner(data) {
        const banner = document.getElementById('personalization-banner');
        if (!banner) return;
        
        document.getElementById('banner-flag').textContent = data.passport.flag;
        document.getElementById('banner-passport-name').textContent = data.passport.name;
        
        // Update stats
        document.getElementById('stat-visa-free').textContent = data.statistics.visa_free;
        document.getElementById('stat-voa').textContent = data.statistics.visa_on_arrival;
        document.getElementById('stat-evisa').textContent = data.statistics.evisa;
        document.getElementById('stat-required').textContent = data.statistics.visa_required;
        document.getElementById('easy-access-count').textContent = data.statistics.easy_access;
    }
    
    /**
     * Update country cards with personalized data
     */
    function updateCountryCards(destinations) {
        const countryCards = document.querySelectorAll('.country-card');
        
        // Create lookup map for quick access
        const destMap = new Map();
        destinations.forEach(dest => {
            destMap.set(dest.country_name, dest);
        });
        
        countryCards.forEach(card => {
            const countryName = card.dataset.name;
            const dest = destMap.get(countryName);
            
            if (dest && dest.is_personalized) {
                updateCard(card, dest);
            }
        });
    }
    
    /**
     * Update individual country card
     */
    function updateCard(card, destination) {
        // Update visa type badge
        const badge = card.querySelector('.visa-type-badge');
        if (badge) {
            badge.textContent = getVisaTypeLabel(destination.visa_type);
            badge.className = 'visa-type-badge ' + getVisaTypeBadgeClass(destination.visa_type);
        }
        
        // Add personalization indicator
        if (!card.querySelector('.personalized-badge')) {
            const personalizedBadge = document.createElement('span');
            personalizedBadge.className = 'personalized-badge';
            personalizedBadge.innerHTML = '✓ For You';
            personalizedBadge.title = 'Personalized for your passport';
            
            const badgesContainer = card.querySelector('.country-badges');
            if (badgesContainer) {
                badgesContainer.appendChild(personalizedBadge);
            }
        }
        
        // Add animated effect
        card.classList.add('card-updating');
        setTimeout(() => card.classList.remove('card-updating'), CONFIG.ANIMATION_DURATION);
        
        // Update data attributes for filtering
        card.dataset.visaType = destination.visa_type;
    }
    
    /**
     * Get visa type label
     */
    function getVisaTypeLabel(type) {
        const labels = {
            'visa_free': 'Visa Free',
            'visa_on_arrival': 'Visa on Arrival',
            'evisa': 'eVisa',
            'visa_required': 'Visa Required',
            'no_entry': 'No Entry'
        };
        return labels[type] || type;
    }
    
    /**
     * Get visa type badge CSS class
     */
    function getVisaTypeBadgeClass(type) {
        const classes = {
            'visa_free': 'badge-visa-free',
            'visa_on_arrival': 'badge-visa-on-arrival',
            'evisa': 'badge-evisa',
            'visa_required': 'badge-visa-required',
            'no_entry': 'badge-no-entry'
        };
        return classes[type] || '';
    }
    
    /**
     * Clear passport selection
     */
    function clearPassportSelection() {
        state.selectedPassport = null;
        state.personalizedData = null;
        localStorage.removeItem(CONFIG.STORAGE_KEY);
        
        const select = document.getElementById('passport-select');
        if (select) select.value = '';
        
        hideClearButton();
        hideBanner();
        
        // Reset all cards to generic data
        resetCountryCards();
        
        console.log('🗑️ Cleared passport selection');
    }
    
    /**
     * Reset country cards to generic data
     */
    function resetCountryCards() {
        const cards = document.querySelectorAll('.country-card');
        cards.forEach(card => {
            // Remove personalized badge
            const personalizedBadge = card.querySelector('.personalized-badge');
            if (personalizedBadge) {
                personalizedBadge.remove();
            }
            
            // You might want to restore original visa types here
            // This would require storing original data somewhere
        });
    }
    
    /**
     * Show/hide UI elements
     */
    function showBanner() {
        const banner = document.getElementById('personalization-banner');
        if (banner) {
            banner.style.display = 'block';
            setTimeout(() => banner.classList.add('banner-visible'), 10);
        }
    }
    
    function hideBanner() {
        const banner = document.getElementById('personalization-banner');
        if (banner) {
            banner.classList.remove('banner-visible');
            setTimeout(() => banner.style.display = 'none', CONFIG.ANIMATION_DURATION);
        }
    }
    
    function showClearButton() {
        const btn = document.getElementById('clear-passport');
        if (btn) btn.style.display = 'flex';
    }
    
    function hideClearButton() {
        const btn = document.getElementById('clear-passport');
        if (btn) btn.style.display = 'none';
    }
    
    function showLoadingState() {
        const select = document.getElementById('passport-select');
        if (select) {
            select.disabled = true;
            select.style.opacity = '0.6';
        }
    }
    
    function hideLoadingState() {
        const select = document.getElementById('passport-select');
        if (select) {
            select.disabled = false;
            select.style.opacity = '1';
        }
    }
    
    /**
     * Show error message
     */
    function showError(message) {
        // You can implement a toast/notification system here
        console.error(message);
        alert(message); // Temporary fallback
    }
    
    /**
     * Track passport selection (analytics)
     */
    function trackPassportSelection(passport) {
        if (typeof trackEvent === 'function') {
            trackEvent('passport_selected', {
                passport_name: passport.name,
                passport_id: passport.id
            });
        }
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Export for external access if needed
    window.PassportPersonalization = {
        getSelectedPassport: () => state.selectedPassport,
        clearSelection: clearPassportSelection,
        getData: () => state.personalizedData
    };
    
})();
