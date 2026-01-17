// Registration Form Enhanced Validation and Security
class RegistrationFormHandler {
    constructor() {
        this.form = document.querySelector('form[action*="register"]');
        if (!this.form) return;

        this.emailInput = this.form.querySelector('input[name="email"]');
        this.emailConfirmInput = this.form.querySelector('input[name="email_confirm"]');
        this.passwordInput = this.form.querySelector('input[name="password"]');
        this.confirmPasswordInput = this.form.querySelector('input[name="confirm_password"]');
        this.firstNameInput = this.form.querySelector('input[name="first_name"]');
        this.lastNameInput = this.form.querySelector('input[name="last_name"]');

        this.emailCheckTimeout = null;
        this.init();
    }

    init() {
        this.addRealTimeEmailValidation();
        this.addEmailConfirmationValidation();
        this.addPasswordStrengthIndicator();
        this.addSecurityValidation();
        this.preventDoubleSubmit();
        this.addFormValidation();
    }

    // Real-time email availability check
    addRealTimeEmailValidation() {
        if (!this.emailInput) return;

        // Create validation indicator
        const indicator = document.createElement('div');
        indicator.className = 'email-validation-indicator';
        this.emailInput.parentNode.insertBefore(indicator, this.emailInput.nextSibling);

        this.emailInput.addEventListener('input', (e) => {
            clearTimeout(this.emailCheckTimeout);
            const email = e.target.value.trim();

            if (email.length === 0) {
                indicator.className = 'email-validation-indicator';
                indicator.textContent = '';
                return;
            }

            // Check email format first
            if (!this.isValidEmail(email)) {
                indicator.className = 'email-validation-indicator invalid';
                indicator.innerHTML = '<span class="icon">✗</span> Format d\'email invalide';
                return;
            }

            // Show checking state
            indicator.className = 'email-validation-indicator checking';
            indicator.innerHTML = '<span class="spinner"></span> Vérification...';

            // Check availability in database
            this.emailCheckTimeout = setTimeout(() => {
                this.checkEmailAvailability(email, indicator);
            }, 800);
        });
    }

    async checkEmailAvailability(email, indicator) {
        try {
            const response = await fetch('/CarShare/api/check-email.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email })
            });

            const data = await response.json();

            if (data.available) {
                indicator.className = 'email-validation-indicator valid';
                indicator.innerHTML = '<span class="icon">✓</span> Email disponible';
            } else {
                indicator.className = 'email-validation-indicator invalid';
                indicator.innerHTML = '<span class="icon">✗</span> ' + data.message;
            }
        } catch (error) {
            console.error('Email check error:', error);
            indicator.className = 'email-validation-indicator';
            indicator.textContent = '';
        }
    }

    // Email confirmation validation
    addEmailConfirmationValidation() {
        if (!this.emailConfirmInput || !this.emailInput) return;

        const indicator = document.createElement('div');
        indicator.className = 'email-confirm-indicator';
        this.emailConfirmInput.parentNode.insertBefore(indicator, this.emailConfirmInput.nextSibling);

        const validateMatch = () => {
            const email = this.emailInput.value.trim();
            const emailConfirm = this.emailConfirmInput.value.trim();

            if (emailConfirm.length === 0) {
                indicator.className = 'email-confirm-indicator';
                indicator.textContent = '';
                return;
            }

            if (email === emailConfirm) {
                indicator.className = 'email-confirm-indicator valid';
                indicator.innerHTML = '<span class="icon">✓</span> Les emails correspondent';
            } else {
                indicator.className = 'email-confirm-indicator invalid';
                indicator.innerHTML = '<span class="icon">✗</span> Les emails ne correspondent pas';
            }
        };

        this.emailInput.addEventListener('input', validateMatch);
        this.emailConfirmInput.addEventListener('input', validateMatch);
    }

    // Password strength indicator
    addPasswordStrengthIndicator() {
        if (!this.passwordInput) return;

        const strengthMeter = document.createElement('div');
        strengthMeter.className = 'password-strength-meter';
        strengthMeter.innerHTML = `
            <div class="strength-bar">
                <div class="strength-bar-fill"></div>
            </div>
            <div class="strength-text"></div>
            <ul class="strength-requirements">
                <li data-req="length">Au moins 12 caractères</li>
                <li data-req="uppercase">Une majuscule</li>
                <li data-req="lowercase">Une minuscule</li>
                <li data-req="number">Un chiffre</li>
                <li data-req="special">Un caractère spécial</li>
            </ul>
        `;
        this.passwordInput.parentNode.insertBefore(strengthMeter, this.passwordInput.nextSibling);

        this.passwordInput.addEventListener('input', (e) => {
            this.updatePasswordStrength(e.target.value, strengthMeter);
        });
    }

    updatePasswordStrength(password, meter) {
        const requirements = {
            length: password.length >= 12,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };

        const met = Object.values(requirements).filter(Boolean).length;
        const percentage = (met / 5) * 100;

        const fill = meter.querySelector('.strength-bar-fill');
        const text = meter.querySelector('.strength-text');

        fill.style.width = percentage + '%';

        // Update color and text based on strength
        if (percentage < 40) {
            fill.style.background = '#dc3545';
            text.textContent = 'Faible';
            text.style.color = '#dc3545';
        } else if (percentage < 80) {
            fill.style.background = '#ffc107';
            text.textContent = 'Moyen';
            text.style.color = '#ffc107';
        } else {
            fill.style.background = '#28a745';
            text.textContent = 'Fort';
            text.style.color = '#28a745';
        }

        // Update requirements list
        meter.querySelectorAll('[data-req]').forEach(item => {
            const req = item.getAttribute('data-req');
            if (requirements[req]) {
                item.classList.add('met');
            } else {
                item.classList.remove('met');
            }
        });
    }

    // Security validation for all inputs
    addSecurityValidation() {
        const textInputs = [this.firstNameInput, this.lastNameInput];
        
        textInputs.forEach(input => {
            if (!input) return;

            input.addEventListener('input', (e) => {
                this.validateInputSecurity(e.target);
            });
        });
    }

    validateInputSecurity(input) {
        const value = input.value;
        
        // Patterns dangereux
        const dangerousPatterns = [
            /<script|<iframe|<object|<embed/i,  // XSS
            /(\b(SELECT|INSERT|UPDATE|DELETE|DROP|UNION)\b|--|;)/i,  // SQL Injection
            /javascript:|onerror|onload|onclick/i,  // Event handlers
            /[<>]/  // HTML tags
        ];

        let isDangerous = false;
        dangerousPatterns.forEach(pattern => {
            if (pattern.test(value)) {
                isDangerous = true;
            }
        });

        if (isDangerous) {
            input.classList.add('input-error');
            this.showFieldError(input, 'Caractères interdits détectés');
        } else {
            input.classList.remove('input-error');
            this.hideFieldError(input);
        }

        return !isDangerous;
    }

    showFieldError(input, message) {
        let errorDiv = input.parentNode.querySelector('.field-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            input.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }

    hideFieldError(input) {
        const errorDiv = input.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    // Form validation before submit
    addFormValidation() {
        this.form.addEventListener('submit', (e) => {
            const errors = [];

            // Validate first name and last name
            if (this.firstNameInput && !this.validateName(this.firstNameInput.value)) {
                errors.push('Le prénom est invalide');
            }
            if (this.lastNameInput && !this.validateName(this.lastNameInput.value)) {
                errors.push('Le nom est invalide');
            }

            // Validate emails
            if (this.emailInput && !this.isValidEmail(this.emailInput.value)) {
                errors.push('L\'email est invalide');
            }
            if (this.emailConfirmInput && this.emailInput.value !== this.emailConfirmInput.value) {
                errors.push('Les emails ne correspondent pas');
            }

            // Validate password
            if (this.passwordInput && !this.validatePassword(this.passwordInput.value)) {
                errors.push('Le mot de passe ne respecte pas les critères de sécurité');
            }
            if (this.confirmPasswordInput && this.passwordInput.value !== this.confirmPasswordInput.value) {
                errors.push('Les mots de passe ne correspondent pas');
            }

            // Check security on all inputs
            const allInputs = [this.firstNameInput, this.lastNameInput, this.emailInput];
            allInputs.forEach(input => {
                if (input && !this.validateInputSecurity(input)) {
                    errors.push('Des caractères dangereux ont été détectés');
                }
            });

            if (errors.length > 0) {
                e.preventDefault();
                this.displayErrors(errors);
                return false;
            }
        });
    }

    validateName(name) {
        // Only letters, hyphens, spaces, and apostrophes
        const namePattern = /^[a-zA-ZÀ-ÿ\s'-]{2,50}$/;
        return namePattern.test(name.trim());
    }

    validatePassword(password) {
        return password.length >= 12 &&
               /[A-Z]/.test(password) &&
               /[a-z]/.test(password) &&
               /[0-9]/.test(password) &&
               /[^A-Za-z0-9]/.test(password);
    }

    isValidEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }

    displayErrors(errors) {
        // Remove existing error display
        const existingError = this.form.querySelector('.form-errors');
        if (existingError) {
            existingError.remove();
        }

        // Create error display
        const errorDiv = document.createElement('div');
        errorDiv.className = 'form-errors';
        errorDiv.innerHTML = `
            <strong>⚠️ Erreurs détectées :</strong>
            <ul>
                ${errors.map(err => `<li>${err}</li>`).join('')}
            </ul>
        `;

        this.form.insertBefore(errorDiv, this.form.firstChild);
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    preventDoubleSubmit() {
        this.form.addEventListener('submit', (e) => {
            if (this.form.classList.contains('submitting')) {
                e.preventDefault();
                return false;
            }
            this.form.classList.add('submitting');
            
            const submitBtn = this.form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Inscription en cours...';
            }
        });
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new RegistrationFormHandler();
    });
} else {
    new RegistrationFormHandler();
}
