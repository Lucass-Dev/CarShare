/**
 * Search Page Modern Enhancements - 2025 UI
 * Auto-suggestions, smooth filters, loading states
 */

document.addEventListener('DOMContentLoaded', () => {
    initSearchEnhancements();
    initFilterAnimations();
    initResultsLoadingState();
});

function initSearchEnhancements() {
    const searchInputs = document.querySelectorAll('input[type="text"][placeholder*="ville"], input[placeholder*="Ville"]');
    
    searchInputs.forEach(input => {
        // Add loading indicator
        const wrapper = document.createElement('div');
        wrapper.className = 'search-input-wrapper';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        
        const loader = document.createElement('span');
        loader.className = 'search-loader';
        loader.innerHTML = '🔍';
        wrapper.appendChild(loader);

        // Debounced search
        let searchTimeout;
        input.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const value = e.target.value.trim();
            
            if (value.length >= 2) {
                loader.classList.add('searching');
                
                searchTimeout = setTimeout(() => {
                    // Simulate API call - in production, call real API
                    loader.classList.remove('searching');
                    loader.classList.add('found');
                    
                    setTimeout(() => {
                        loader.classList.remove('found');
                    }, 1000);
                }, 500);
            } else {
                loader.classList.remove('searching', 'found');
            }
        });

        // Add focus glow effect
        input.addEventListener('focus', () => {
            wrapper.classList.add('focused');
        });

        input.addEventListener('blur', () => {
            wrapper.classList.remove('focused');
        });
    });
}

function initFilterAnimations() {
    const filterButtons = document.querySelectorAll('.filter-btn, button[type="submit"]');
    
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Add ripple effect
            const ripple = document.createElement('span');
            ripple.className = 'btn-ripple';
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Smooth collapse/expand for filters
    const filterToggles = document.querySelectorAll('[data-toggle="collapse"]');
    filterToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const target = document.querySelector(this.getAttribute('data-target'));
            if (target) {
                target.classList.toggle('show');
                this.classList.toggle('active');
            }
        });
    });
}

function initResultsLoadingState() {
    const searchForm = document.querySelector('form[action*="search"]');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            // Show loading overlay
            const overlay = document.createElement('div');
            overlay.className = 'search-loading-overlay';
            overlay.innerHTML = `
                <div class="loading-content">
                    <div class="loading-spinner"></div>
                    <p>Recherche des meilleurs trajets...</p>
                </div>
            `;
            
            document.body.appendChild(overlay);
            
            setTimeout(() => overlay.classList.add('show'), 10);
        });
    }

    // Animate results appearance
    const results = document.querySelectorAll('.trip-card, .result-item');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('animate-in');
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    results.forEach(result => {
        result.classList.add('animate-ready');
        observer.observe(result);
    });
}

// Date picker enhancement
function enhanceDateInputs() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    
    dateInputs.forEach(input => {
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        input.setAttribute('min', today);
        
        // Add visual feedback
        input.addEventListener('change', function() {
            if (this.value) {
                this.classList.add('has-value');
            } else {
                this.classList.remove('has-value');
            }
        });
    });
}

enhanceDateInputs();
