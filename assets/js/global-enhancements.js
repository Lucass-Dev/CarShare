/**
 * Global UI Enhancements - 2025 Modern Experience
 * Smooth interactions, tooltips, confirmations
 */

document.addEventListener('DOMContentLoaded', () => {
    initSmoothScrolling();
    initConfirmDialogs();
    initTooltips();
    initCardHoverEffects();
    initBackToTop();
    initComingSoonNotices();
});

// Smooth scrolling for all anchor links
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Modern confirm dialogs - Using custom dialog system
function initConfirmDialogs() {
    document.querySelectorAll('[data-confirm]').forEach(element => {
        element.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const message = this.getAttribute('data-confirm');
            const title = this.getAttribute('data-confirm-title') || 'Confirmation';
            const confirmText = this.getAttribute('data-confirm-text') || 'Confirmer';
            const cancelText = this.getAttribute('data-cancel-text') || 'Annuler';
            const isDanger = this.classList.contains('btn--danger') || this.getAttribute('data-danger') === 'true';
            
            const confirmed = await window.customConfirm(message, {
                title: title,
                confirmText: confirmText,
                cancelText: cancelText,
                confirmClass: isDanger ? 'btn-danger' : 'btn-confirm',
                icon: isDanger ? '⚠️' : 'ℹ️'
            });
            
            if (confirmed) {
                // User confirmed
                if (this.tagName === 'A') {
                    window.location.href = this.href;
                } else if (this.tagName === 'BUTTON' && this.form) {
                    this.form.submit();
                } else if (this.hasAttribute('data-action')) {
                    // Execute custom action
                    const action = this.getAttribute('data-action');
                    if (typeof window[action] === 'function') {
                        window[action]();
                    }
                }
            }
        });
    });
}

// Modern tooltips
function initTooltips() {
    document.querySelectorAll('[data-tooltip]').forEach(element => {
        const tooltip = document.createElement('div');
        tooltip.className = 'modern-tooltip';
        tooltip.textContent = element.getAttribute('data-tooltip');
        document.body.appendChild(tooltip);
        
        element.addEventListener('mouseenter', (e) => {
            const rect = element.getBoundingClientRect();
            tooltip.style.left = rect.left + rect.width / 2 + 'px';
            tooltip.style.top = rect.top - 10 + 'px';
            tooltip.classList.add('show');
        });
        
        element.addEventListener('mouseleave', () => {
            tooltip.classList.remove('show');
        });
    });
}

// Card hover effects with depth
function initCardHoverEffects() {
    document.querySelectorAll('.card, .trip-card, .trajet-card').forEach(card => {
        card.addEventListener('mouseenter', function(e) {
            this.style.boxShadow = '0 12px 32px rgba(0, 0, 0, 0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });
}

// Back to top button
function initBackToTop() {
    const button = document.createElement('button');
    button.className = 'back-to-top';
    button.innerHTML = '↑';
    button.title = 'Retour en haut';
    document.body.appendChild(button);
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            button.classList.add('show');
        } else {
            button.classList.remove('show');
        }
    });
    
    button.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Coming soon notices
function initComingSoonNotices() {
    document.querySelectorAll('[data-coming-soon]').forEach(element => {
        element.addEventListener('click', (event) => {
            event.preventDefault();
            const message = element.getAttribute('data-coming-soon') || 'Fonctionnalité bientôt disponible.';
            if (window.notificationManager && typeof window.notificationManager.show === 'function') {
                window.notificationManager.show(message, 'info', 5000);
            } else if (typeof window.showNotification === 'function') {
                window.showNotification(message, 'info', 5000);
            } else {
                console.info(message);
            }
        });
    });
}

// Loading state for links
document.addEventListener('click', (e) => {
    const link = e.target.closest('a:not([href^="#"]):not([target="_blank"])');
    if (link && !e.ctrlKey && !e.metaKey) {
        // Add subtle loading indicator
        const loader = document.createElement('div');
        loader.className = 'page-transition';
        document.body.appendChild(loader);
        setTimeout(() => loader.classList.add('active'), 10);
    }
});

// Prevent form resubmission on page refresh
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
