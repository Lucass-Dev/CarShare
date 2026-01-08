/**
 * Modern Form Enhancements - 2025 UI/UX
 * Real-time validation, loading states, and smooth animations
 */

class FormEnhancer {
    constructor(formElement) {
        this.form = formElement;
        this.submitButton = this.form.querySelector('button[type="submit"]');
        this.init();
    }

    init() {
        this.addLoadingState();
        this.addInputAnimations();
        this.addRealTimeEmailValidation();
        this.preventDoubleSubmit();
    }

    // Add loading state to submit button
    addLoadingState() {
        if (!this.submitButton) return;

        this.form.addEventListener('submit', (e) => {
            if (this.form.classList.contains('submitting')) {
                e.preventDefault();
                return;
            }

            this.submitButton.disabled = true;
            this.submitButton.classList.add('loading');
            
            const originalText = this.submitButton.textContent;
            this.submitButton.innerHTML = `
                <span class="spinner"></span>
                <span>Chargement...</span>
            `;

            // Restore button if there's an error (handled by backend)
            setTimeout(() => {
                if (this.form.querySelector('.error-message')) {
                    this.submitButton.disabled = false;
                    this.submitButton.classList.remove('loading');
                    this.submitButton.textContent = originalText;
                }
            }, 1000);
        });
    }

    // Add smooth animations to inputs
    addInputAnimations() {
        const inputs = this.form.querySelectorAll('input:not([type="submit"])');
        
        inputs.forEach(input => {
            // Floating label effect
            const wrapper = document.createElement('div');
            wrapper.className = 'input-wrapper';
            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(input);

            // Add focus/blur animations
            input.addEventListener('focus', () => {
                wrapper.classList.add('focused');
                this.addRippleEffect(input);
            });

            input.addEventListener('blur', () => {
                if (!input.value) {
                    wrapper.classList.remove('focused');
                }
            });

            // Initial state
            if (input.value) {
                wrapper.classList.add('focused');
            }
        });
    }

    // Add ripple effect on focus
    addRippleEffect(input) {
        const ripple = document.createElement('span');
        ripple.className = 'input-ripple';
        
        const wrapper = input.closest('.input-wrapper');
        if (wrapper) {
            wrapper.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        }
    }

    // Real-time email validation
    addRealTimeEmailValidation() {
        const emailInput = this.form.querySelector('input[type="email"]');
        if (!emailInput) return;

        let validationTimeout;
        const validationIndicator = document.createElement('div');
        validationIndicator.className = 'email-validation';
        emailInput.parentNode.appendChild(validationIndicator);

        emailInput.addEventListener('input', () => {
            clearTimeout(validationTimeout);
            
            const email = emailInput.value.trim();
            
            if (email.length === 0) {
                validationIndicator.className = 'email-validation';
                validationIndicator.textContent = '';
                return;
            }

            validationIndicator.className = 'email-validation checking';
            validationIndicator.innerHTML = '<span class="spinner-small"></span> Vérification...';

            validationTimeout = setTimeout(() => {
                if (this.isValidEmail(email)) {
                    validationIndicator.className = 'email-validation valid';
                    validationIndicator.innerHTML = '<span class="icon">✓</span> Email valide';
                } else {
                    validationIndicator.className = 'email-validation invalid';
                    validationIndicator.innerHTML = '<span class="icon">✗</span> Email invalide';
                }
            }, 500);
        });
    }

    isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Prevent double submit
    preventDoubleSubmit() {
        this.form.addEventListener('submit', (e) => {
            if (this.form.classList.contains('submitting')) {
                e.preventDefault();
                return false;
            }
            this.form.classList.add('submitting');
        });
    }

    // Show success message with animation
    static showSuccess(message, duration = 3000) {
        const notification = document.createElement('div');
        notification.className = 'notification success';
        notification.innerHTML = `
            <span class="notification-icon">✓</span>
            <span class="notification-message">${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => notification.classList.add('show'), 10);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }

    // Show error message with animation
    static showError(message, duration = 4000) {
        const notification = document.createElement('div');
        notification.className = 'notification error';
        notification.innerHTML = `
            <span class="notification-icon">✗</span>
            <span class="notification-message">${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => notification.classList.add('show'), 10);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }
}

// Auto-init on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize form enhancements
    const forms = document.querySelectorAll('form:not(.no-enhance)');
    forms.forEach(form => new FormEnhancer(form));

    // Add smooth scroll to anchors
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
});
