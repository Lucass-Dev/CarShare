/**
 * Enhanced Offers Page JavaScript
 * Handles search debouncing, dynamic labels, and smooth interactions
 * @version 2.0 - 2026-01-17
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        searchDebounceDelay: 800,
        animationDuration: 300
    };

    // DOM Elements
    let searchInput, sortSelect, orderSelect, priceMaxInput, placesMinInput;

    /**
     * Initialize the page
     */
    function init() {
        // Cache DOM elements
        searchInput = document.querySelector('.search-input');
        sortSelect = document.querySelector('select[name="sort"]');
        orderSelect = document.querySelector('select[name="order"]');
        priceMaxInput = document.querySelector('input[name="prix_max"]');
        placesMinInput = document.querySelector('input[name="places_min"]');

        // Setup event listeners
        if (searchInput) {
            setupSearchDebounce();
        }

        if (sortSelect && orderSelect) {
            setupSortLabels();
        }

        if (priceMaxInput) {
            setupPriceMaxDebounce();
        }

        if (placesMinInput) {
            setupPlacesMinDebounce();
        }

        // Setup card animations
        setupCardAnimations();

        // Setup clear button
        setupClearButton();
    }

    /**
     * Setup search input with debounce
     */
    function setupSearchDebounce() {
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            // Add loading indicator
            searchInput.classList.add('loading');

            searchTimeout = setTimeout(() => {
                searchInput.classList.remove('loading');
                this.form.submit();
            }, CONFIG.searchDebounceDelay);
        });

        // Clear on Escape key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                this.form.submit();
            }
        });
    }

    /**
     * Setup dynamic sort order labels
     */
    function setupSortLabels() {
        // Update labels when sort type changes
        sortSelect.addEventListener('change', function() {
            updateOrderLabels(this.value);
        });

        // Initialize labels on load
        updateOrderLabels(sortSelect.value);
    }

    /**
     * Update order select labels based on sort type
     * @param {string} sortType - 'date' or 'price'
     */
    function updateOrderLabels(sortType) {
        const options = orderSelect.options;

        if (sortType === 'price') {
            options[0].text = 'Moins cher';
            options[1].text = 'Plus cher';
        } else {
            options[0].text = 'Plus proche';
            options[1].text = 'Plus loin';
        }
    }

    /**
     * Setup price max input with debounce
     */
    function setupPriceMaxDebounce() {
        let priceTimeout;

        priceMaxInput.addEventListener('input', function() {
            clearTimeout(priceTimeout);

            priceTimeout = setTimeout(() => {
                if (this.value === '' || parseInt(this.value) > 0) {
                    this.form.submit();
                }
            }, CONFIG.searchDebounceDelay);
        });
    }

    /**
     * Setup places min input with debounce
     */
    function setupPlacesMinDebounce() {
        let placesTimeout;

        placesMinInput.addEventListener('input', function() {
            clearTimeout(placesTimeout);

            placesTimeout = setTimeout(() => {
                const value = parseInt(this.value);
                if (this.value === '' || (value >= 1 && value <= 8)) {
                    this.form.submit();
                }
            }, CONFIG.searchDebounceDelay);
        });
    }

    /**
     * Setup smooth animations for offer cards
     */
    function setupCardAnimations() {
        const cards = document.querySelectorAll('.offer-card');

        // Intersection Observer for fade-in animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('fade-in');
                    }, index * 50); // Stagger animation
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `opacity ${CONFIG.animationDuration}ms ease, transform ${CONFIG.animationDuration}ms ease`;
            observer.observe(card);
        });
    }

    /**
     * Setup clear search button
     */
    function setupClearButton() {
        const clearButton = document.querySelector('.clear-search');

        if (clearButton) {
            clearButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (searchInput) {
                    searchInput.value = '';
                    searchInput.form.submit();
                }
            });
        }
    }

    /**
     * Add fade-in class for smooth appearance
     */
    document.addEventListener('DOMContentLoaded', function() {
        const style = document.createElement('style');
        style.textContent = `
            .offer-card.fade-in {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }

            .search-input.loading {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='12' y1='2' x2='12' y2='6'%3E%3C/line%3E%3Cline x1='12' y1='18' x2='12' y2='22'%3E%3C/line%3E%3Cline x1='4.93' y1='4.93' x2='7.76' y2='7.76'%3E%3C/line%3E%3Cline x1='16.24' y1='16.24' x2='19.07' y2='19.07'%3E%3C/line%3E%3Cline x1='2' y1='12' x2='6' y2='12'%3E%3C/line%3E%3Cline x1='18' y1='12' x2='22' y2='12'%3E%3C/line%3E%3Cline x1='4.93' y1='19.07' x2='7.76' y2='16.24'%3E%3C/line%3E%3Cline x1='16.24' y1='7.76' x2='19.07' y2='4.93'%3E%3C/line%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 1rem center;
                background-size: 20px;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                }
            }
        `;
        document.head.appendChild(style);
    });

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
