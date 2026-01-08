/**
 * Password Validator - Modern 2025 UI
 * Real-time password validation with visual feedback
 */

class PasswordValidator {
    constructor(passwordInput, confirmInput = null) {
        this.passwordInput = passwordInput;
        this.confirmInput = confirmInput;
        this.init();
    }

    init() {
        this.createValidationUI();
        this.attachEventListeners();
    }

    createValidationUI() {
        // Create validation container
        const container = document.createElement('div');
        container.className = 'password-validation';
        container.innerHTML = `
            <div class="password-strength">
                <div class="strength-bar">
                    <div class="strength-bar-fill"></div>
                </div>
                <span class="strength-text">Force du mot de passe</span>
            </div>
            <ul class="validation-rules">
                <li data-rule="length">
                    <span class="rule-icon">○</span>
                    <span class="rule-text">Au moins 12 caractères</span>
                </li>
                <li data-rule="uppercase">
                    <span class="rule-icon">○</span>
                    <span class="rule-text">Une lettre majuscule</span>
                </li>
                <li data-rule="lowercase">
                    <span class="rule-icon">○</span>
                    <span class="rule-text">Une lettre minuscule</span>
                </li>
                <li data-rule="number">
                    <span class="rule-icon">○</span>
                    <span class="rule-text">Un chiffre</span>
                </li>
                <li data-rule="special">
                    <span class="rule-icon">○</span>
                    <span class="rule-text">Un caractère spécial (!@#$%^&*)</span>
                </li>
            </ul>
        `;
        
        this.passwordInput.parentNode.insertBefore(container, this.passwordInput.nextSibling);
        this.validationContainer = container;

        // Create match indicator for confirm password
        if (this.confirmInput) {
            const matchIndicator = document.createElement('div');
            matchIndicator.className = 'password-match';
            matchIndicator.innerHTML = `
                <span class="match-icon">○</span>
                <span class="match-text">Les mots de passe correspondent</span>
            `;
            this.confirmInput.parentNode.insertBefore(matchIndicator, this.confirmInput.nextSibling);
            this.matchIndicator = matchIndicator;
        }
    }

    attachEventListeners() {
        // Real-time validation
        this.passwordInput.addEventListener('input', () => {
            this.validatePassword();
        });

        this.passwordInput.addEventListener('focus', () => {
            this.validationContainer.classList.add('active');
        });

        if (this.confirmInput) {
            this.confirmInput.addEventListener('input', () => {
                this.checkPasswordMatch();
            });

            this.confirmInput.addEventListener('focus', () => {
                if (this.matchIndicator) {
                    this.matchIndicator.classList.add('active');
                }
            });
        }
    }

    validatePassword() {
        const password = this.passwordInput.value;
        const rules = {
            length: password.length >= 12,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };

        // Update visual feedback for each rule
        Object.keys(rules).forEach(rule => {
            const ruleElement = this.validationContainer.querySelector(`[data-rule="${rule}"]`);
            if (ruleElement) {
                if (rules[rule]) {
                    ruleElement.classList.add('valid');
                    ruleElement.classList.remove('invalid');
                    ruleElement.querySelector('.rule-icon').textContent = '✓';
                } else {
                    ruleElement.classList.remove('valid');
                    ruleElement.classList.add('invalid');
                    ruleElement.querySelector('.rule-icon').textContent = '✗';
                }
            }
        });

        // Calculate strength
        const strength = this.calculateStrength(password, rules);
        this.updateStrengthBar(strength);

        // Check password match if confirm input exists
        if (this.confirmInput && this.confirmInput.value) {
            this.checkPasswordMatch();
        }

        return Object.values(rules).every(v => v);
    }

    calculateStrength(password, rules) {
        let strength = 0;
        const validRules = Object.values(rules).filter(v => v).length;
        
        strength = (validRules / 5) * 100;

        // Bonus for length
        if (password.length >= 16) strength += 10;
        if (password.length >= 20) strength += 10;

        return Math.min(strength, 100);
    }

    updateStrengthBar(strength) {
        const fill = this.validationContainer.querySelector('.strength-bar-fill');
        const text = this.validationContainer.querySelector('.strength-text');
        
        fill.style.width = strength + '%';
        
        let strengthLabel = 'Très faible';
        let strengthClass = 'very-weak';
        
        if (strength >= 80) {
            strengthLabel = 'Excellent';
            strengthClass = 'excellent';
        } else if (strength >= 60) {
            strengthLabel = 'Bon';
            strengthClass = 'good';
        } else if (strength >= 40) {
            strengthLabel = 'Moyen';
            strengthClass = 'medium';
        } else if (strength >= 20) {
            strengthLabel = 'Faible';
            strengthClass = 'weak';
        }
        
        fill.className = `strength-bar-fill ${strengthClass}`;
        text.textContent = `Force: ${strengthLabel}`;
    }

    checkPasswordMatch() {
        if (!this.matchIndicator) return;

        const password = this.passwordInput.value;
        const confirm = this.confirmInput.value;

        if (confirm.length === 0) {
            this.matchIndicator.classList.remove('match', 'no-match');
            return;
        }

        if (password === confirm) {
            this.matchIndicator.classList.add('match');
            this.matchIndicator.classList.remove('no-match');
            this.matchIndicator.querySelector('.match-icon').textContent = '✓';
            this.matchIndicator.querySelector('.match-text').textContent = 'Les mots de passe correspondent';
        } else {
            this.matchIndicator.classList.remove('match');
            this.matchIndicator.classList.add('no-match');
            this.matchIndicator.querySelector('.match-icon').textContent = '✗';
            this.matchIndicator.querySelector('.match-text').textContent = 'Les mots de passe ne correspondent pas';
        }
    }

    isValid() {
        return this.validatePassword() && (!this.confirmInput || this.passwordInput.value === this.confirmInput.value);
    }
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PasswordValidator;
}
