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

// Modern confirm dialogs
function initConfirmDialogs() {
    document.querySelectorAll('[data-confirm]').forEach(element => {
        element.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            
            e.preventDefault();
            
            // Create modern modal
            const modal = createConfirmModal(message, () => {
                // User confirmed
                if (this.tagName === 'A') {
                    window.location.href = this.href;
                } else if (this.tagName === 'BUTTON' && this.form) {
                    this.form.submit();
                }
            });
            
            document.body.appendChild(modal);
            setTimeout(() => modal.classList.add('show'), 10);
        });
    });
}

function createConfirmModal(message, onConfirm) {
    const modal = document.createElement('div');
    modal.className = 'modern-modal';
    modal.innerHTML = `
        <div class="modal-backdrop"></div>
        <div class="modal-content">
            <div class="modal-icon">⚠️</div>
            <h3>Confirmation</h3>
            <p>${message}</p>
            <div class="modal-actions">
                <button class="btn-cancel">Annuler</button>
                <button class="btn-confirm">Confirmer</button>
            </div>
        </div>
    `;
    
    modal.querySelector('.btn-cancel').addEventListener('click', () => {
        modal.classList.remove('show');
        setTimeout(() => modal.remove(), 300);
    });
    
    modal.querySelector('.btn-confirm').addEventListener('click', () => {
        modal.classList.remove('show');
        setTimeout(() => modal.remove(), 300);
        onConfirm();
    });
    
    modal.querySelector('.modal-backdrop').addEventListener('click', () => {
        modal.classList.remove('show');
        setTimeout(() => modal.remove(), 300);
    });
    
    return modal;
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
