/**
 * Arrival Cards - Main JavaScript
 * Handles search, filters, language switcher, and UI interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // MOBILE MENU TOGGLE
    // ============================================
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');
    
    if (mobileMenuToggle && mainNav) {
        mobileMenuToggle.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            mainNav.classList.toggle('mobile-open');
        });
    }
    
    // ============================================
    // LANGUAGE DROPDOWN
    // ============================================
    const langDropdownBtn = document.querySelector('.lang-dropdown-btn');
    const langDropdownMenu = document.querySelector('.lang-dropdown-menu');
    
    if (langDropdownBtn) {
        langDropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.language-switcher')) {
                langDropdownBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }
    
    // ============================================
    // REAL-TIME SEARCH & FILTER
    // ============================================
    const searchInput = document.querySelector('.search-input');
    const regionFilter = document.querySelector('#region-filter');
    const visaFilter = document.querySelector('#visa-filter');
    const countryCards = document.querySelectorAll('.country-card');
    const noResultsMessage = document.querySelector('.no-results');
    
    function filterCountries() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const selectedRegion = regionFilter ? regionFilter.value : '';
        const selectedVisaType = visaFilter ? visaFilter.value : '';
        
        let visibleCount = 0;
        
        countryCards.forEach(card => {
            const countryName = card.dataset.name ? card.dataset.name.toLowerCase() : '';
            const cardRegion = card.dataset.region || '';
            const cardVisaType = card.dataset.visaType || '';
            
            const matchesSearch = countryName.includes(searchTerm);
            const matchesRegion = !selectedRegion || cardRegion === selectedRegion;
            const matchesVisaType = !selectedVisaType || cardVisaType === selectedVisaType;
            
            if (matchesSearch && matchesRegion && matchesVisaType) {
                card.style.display = '';
                card.style.animation = 'fadeIn 0.3s ease';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (noResultsMessage) {
            if (visibleCount === 0) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        }
    }
    
    // Attach event listeners
    if (searchInput) {
        searchInput.addEventListener('input', filterCountries);
    }
    
    if (regionFilter) {
        regionFilter.addEventListener('change', filterCountries);
    }
    
    if (visaFilter) {
        visaFilter.addEventListener('change', filterCountries);
    }
    
    // ============================================
    // FLASH MESSAGE CLOSE
    // ============================================
    const flashClose = document.querySelector('.flash-close');
    const flashMessage = document.querySelector('.flash-message');
    
    if (flashClose && flashMessage) {
        flashClose.addEventListener('click', function() {
            flashMessage.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => {
                flashMessage.remove();
            }, 300);
        });
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (flashMessage) {
                flashMessage.style.animation = 'slideUp 0.3s ease';
                setTimeout(() => {
                    flashMessage.remove();
                }, 300);
            }
        }, 5000);
    }
    
    // ============================================
    // FORM VALIDATION
    // ============================================
    const contactForm = document.querySelector('#contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous errors
            const errorMessages = contactForm.querySelectorAll('.form-error');
            errorMessages.forEach(msg => msg.remove());
            
            // Validate name
            const nameInput = contactForm.querySelector('#name');
            if (nameInput && nameInput.value.trim().length < 2) {
                showError(nameInput, 'Please enter a valid name');
                isValid = false;
            }
            
            // Validate email
            const emailInput = contactForm.querySelector('#email');
            if (emailInput) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value.trim())) {
                    showError(emailInput, 'Please enter a valid email address');
                    isValid = false;
                }
            }
            
            // Validate message
            const messageInput = contactForm.querySelector('#message');
            if (messageInput && messageInput.value.trim().length < 10) {
                showError(messageInput, 'Message must be at least 10 characters');
                isValid = false;
            }
            
            // Check honeypot (spam protection)
            const honeypot = contactForm.querySelector('.hp-field');
            if (honeypot && honeypot.value !== '') {
                e.preventDefault();
                return false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    function showError(input, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'form-error';
        errorDiv.textContent = message;
        input.parentNode.appendChild(errorDiv);
        input.style.borderColor = 'var(--danger-color)';
        
        // Remove error styling on input
        input.addEventListener('input', function() {
            input.style.borderColor = '';
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, { once: true });
    }
    
    // ============================================
    // SMOOTH SCROLL FOR ANCHOR LINKS
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // ============================================
    // EXTERNAL LINKS (open in new tab)
    // ============================================
    document.querySelectorAll('a[href^="http"]').forEach(link => {
        // Skip language switcher links, admin navigation, logo links, and internal links
        if (!link.getAttribute('target') && 
            !link.closest('.language-switcher') &&
            !link.closest('.logo') &&
            !link.closest('.admin-logo') &&
            !link.closest('.admin-header') &&
            !link.classList.contains('admin-nav-link') &&
            !link.href.includes('set_language.php') &&
            !link.href.includes('/admin/')) {
            link.setAttribute('target', '_blank');
            link.setAttribute('rel', 'noopener noreferrer');
        }
    });
    
    // ============================================
    // ADD FADE-IN ANIMATION
    // ============================================
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
        
        @media (max-width: 767px) {
            .main-nav.mobile-open {
                display: block;
                position: absolute;
                top: var(--header-height);
                left: 0;
                right: 0;
                background-color: var(--bg-primary);
                border-bottom: 1px solid var(--border-color);
                box-shadow: var(--shadow-lg);
                animation: slideDown 0.3s ease;
            }
            
            .main-nav.mobile-open ul {
                flex-direction: column;
                padding: var(--spacing-md);
            }
            
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }
    `;
    document.head.appendChild(style);
    
    // ============================================
    // KEYBOARD ACCESSIBILITY
    // ============================================
    
    // Escape key closes dropdowns
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close language dropdown
            if (langDropdownBtn) {
                langDropdownBtn.setAttribute('aria-expanded', 'false');
            }
            
            // Close mobile menu
            if (mobileMenuToggle) {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                mainNav.classList.remove('mobile-open');
            }
        }
    });
    
    // ============================================
    // PERFORMANCE: DEBOUNCE SEARCH INPUT
    // ============================================
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    if (searchInput) {
        const debouncedFilter = debounce(filterCountries, 300);
        searchInput.removeEventListener('input', filterCountries);
        searchInput.addEventListener('input', debouncedFilter);
    }
    
    // ============================================
    // LAZY LOAD IMAGES (if any added in future)
    // ============================================
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // ============================================
    // ANALYTICS (placeholder for future)
    // ============================================
    function trackEvent(category, action, label) {
        // Placeholder for Google Analytics or other tracking
        console.log('Event:', category, action, label);
    }
    
    // Track country card clicks
    document.querySelectorAll('.country-card .btn-primary').forEach(btn => {
        btn.addEventListener('click', function() {
            const countryName = this.closest('.country-card').dataset.name;
            trackEvent('Country', 'View Official Site', countryName);
        });
    });
    
    // ============================================
    // COUNTRY FEEDBACK SYSTEM
    // ============================================
    const feedbackButtons = document.querySelectorAll('.feedback-btn');
    
    feedbackButtons.forEach(button => {
        button.addEventListener('click', function() {
            const countryId = this.dataset.countryId;
            const feedbackType = this.dataset.type;
            const card = this.closest('.country-card');
            
            // Prevent multiple clicks
            if (this.classList.contains('feedback-loading')) {
                return;
            }
            
            // Add loading state
            this.classList.add('feedback-loading');
            this.disabled = true;
            
            // Send feedback via AJAX
            fetch('submit_feedback.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `country_id=${countryId}&type=${feedbackType}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update vote counts
                    const yesCount = card.querySelector('[data-count-type="yes"]');
                    const noCount = card.querySelector('[data-count-type="no"]');
                    
                    if (yesCount) yesCount.textContent = data.helpful_yes;
                    if (noCount) noCount.textContent = data.helpful_no;
                    
                    // Visual feedback
                    const allButtons = card.querySelectorAll('.feedback-btn');
                    allButtons.forEach(btn => {
                        btn.classList.remove('feedback-active');
                    });
                    this.classList.add('feedback-active');
                    
                    // Show thank you message
                    showFeedbackMessage(card, data.message, 'success');
                    
                    // Track analytics
                    trackEvent('Feedback', feedbackType, card.dataset.name);
                    
                    // Re-enable buttons after cooldown
                    setTimeout(() => {
                        allButtons.forEach(btn => {
                            btn.classList.remove('feedback-loading');
                            btn.disabled = false;
                        });
                    }, 1000);
                } else {
                    // Show error message
                    showFeedbackMessage(card, data.message || 'An error occurred', 'error');
                    this.classList.remove('feedback-loading');
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Feedback error:', error);
                showFeedbackMessage(card, 'Could not submit feedback. Please try again.', 'error');
                this.classList.remove('feedback-loading');
                this.disabled = false;
            });
        });
    });
    
    function showFeedbackMessage(card, message, type) {
        // Remove any existing messages
        const existingMessage = card.querySelector('.feedback-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Create new message
        const messageDiv = document.createElement('div');
        messageDiv.className = `feedback-message feedback-message-${type}`;
        messageDiv.textContent = message;
        
        // Insert after feedback buttons
        const feedbackSection = card.querySelector('.country-feedback');
        if (feedbackSection) {
            feedbackSection.appendChild(messageDiv);
            
            // Auto-remove after 3 seconds
            setTimeout(() => {
                messageDiv.style.opacity = '0';
                setTimeout(() => messageDiv.remove(), 300);
            }, 3000);
        }
    }
    
    console.log('Arrival Cards initialized successfully!');
});
