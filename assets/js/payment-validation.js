/**
 * Payment Form Validation - Real-time validation for card payment
 * Validates card holder, card number, expiry date, and CVV with security checks
 */

(function() {
    'use strict';

    // Validation states
    const validationState = {
        card_holder: false,
        card_number: false,
        expiry_date: false,
        cvv: false,
        accept_terms: false
    };

    let currentStep = 1;

    // Initialize on DOM load
    document.addEventListener('DOMContentLoaded', function() {
        initializeForm();
    });

    function initializeForm() {
        const form = document.getElementById('payment-form');
        if (!form) return;

        // Setup field validators
        setupCardHolderValidation();
        setupCardNumberValidation();
        setupExpiryDateValidation();
        setupCVVValidation();
        setupTermsValidation();

        // Setup navigation
        setupStepNavigation();

        // Setup form submission
        form.addEventListener('submit', handleFormSubmit);
    }

    /**
     * Card Holder Name Validation
     */
    function setupCardHolderValidation() {
        const input = document.getElementById('card_holder');
        if (!input) return;

        input.addEventListener('input', function() {
            validateCardHolder(this.value);
        });

        input.addEventListener('blur', function() {
            validateCardHolder(this.value);
        });
    }

    function validateCardHolder(value) {
        const input = document.getElementById('card_holder');
        const errorEl = document.getElementById('error-card_holder');
        const successEl = document.getElementById('success-card_holder');

        // Sanitize input
        value = value.trim();

        // Remove validation classes first
        input.classList.remove('valid', 'invalid');
        errorEl.classList.remove('show');
        successEl.classList.remove('show');

        if (value === '') {
            showError(input, errorEl, 'Le nom du titulaire est obligatoire');
            validationState.card_holder = false;
            return false;
        }

        // Check for special characters and numbers (basic security)
        if (/[0-9<>{}[\]\\|;:@#$%^&*()+=~`]/.test(value)) {
            showError(input, errorEl, 'Le nom ne doit contenir que des lettres et espaces');
            validationState.card_holder = false;
            return false;
        }

        // Check for SQL injection patterns
        if (/(\bSELECT\b|\bINSERT\b|\bUPDATE\b|\bDELETE\b|\bDROP\b|--|;|\/\*)/i.test(value)) {
            showError(input, errorEl, 'Format de nom invalide');
            validationState.card_holder = false;
            return false;
        }

        // Minimum 2 characters
        if (value.length < 2) {
            showError(input, errorEl, 'Le nom doit contenir au moins 2 caractères');
            validationState.card_holder = false;
            return false;
        }

        // Maximum 50 characters
        if (value.length > 50) {
            showError(input, errorEl, 'Le nom ne peut pas dépasser 50 caractères');
            validationState.card_holder = false;
            return false;
        }

        // Valid
        showSuccess(input, successEl, 'Nom valide');
        validationState.card_holder = true;
        return true;
    }

    /**
     * Card Number Validation (Luhn algorithm)
     */
    function setupCardNumberValidation() {
        const input = document.getElementById('card_number');
        if (!input) return;

        input.addEventListener('input', function(e) {
            // Format card number with spaces
            let value = e.target.value.replace(/\s/g, '');
            value = value.replace(/[^0-9]/g, '');
            
            // Add spaces every 4 digits
            const formatted = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formatted;

            validateCardNumber(value);
        });

        input.addEventListener('blur', function() {
            const value = this.value.replace(/\s/g, '');
            validateCardNumber(value);
        });
    }

    function validateCardNumber(value) {
        const input = document.getElementById('card_number');
        const errorEl = document.getElementById('error-card_number');
        const successEl = document.getElementById('success-card_number');

        // Remove validation classes
        input.classList.remove('valid', 'invalid');
        errorEl.classList.remove('show');
        successEl.classList.remove('show');

        if (value === '') {
            showError(input, errorEl, 'Le numéro de carte est obligatoire');
            validationState.card_number = false;
            return false;
        }

        // Check if only digits
        if (!/^\d+$/.test(value)) {
            showError(input, errorEl, 'Le numéro de carte ne doit contenir que des chiffres');
            validationState.card_number = false;
            return false;
        }

        // Check length (16 digits for most cards)
        if (value.length !== 16) {
            showError(input, errorEl, 'Le numéro de carte doit contenir 16 chiffres');
            validationState.card_number = false;
            return false;
        }

        // Luhn algorithm validation
        if (!luhnCheck(value)) {
            showError(input, errorEl, 'Numéro de carte invalide');
            validationState.card_number = false;
            return false;
        }

        // Valid
        showSuccess(input, successEl, 'Numéro de carte valide');
        validationState.card_number = true;
        return true;
    }

    /**
     * Luhn algorithm for card validation
     */
    function luhnCheck(cardNumber) {
        let sum = 0;
        let isEven = false;

        for (let i = cardNumber.length - 1; i >= 0; i--) {
            let digit = parseInt(cardNumber.charAt(i), 10);

            if (isEven) {
                digit *= 2;
                if (digit > 9) {
                    digit -= 9;
                }
            }

            sum += digit;
            isEven = !isEven;
        }

        return (sum % 10) === 0;
    }

    /**
     * Expiry Date Validation
     */
    function setupExpiryDateValidation() {
        const input = document.getElementById('expiry_date');
        if (!input) return;

        input.addEventListener('input', function(e) {
            // Format MM/YY
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            
            e.target.value = value;
            validateExpiryDate(value);
        });

        input.addEventListener('blur', function() {
            validateExpiryDate(this.value);
        });
    }

    function validateExpiryDate(value) {
        const input = document.getElementById('expiry_date');
        const errorEl = document.getElementById('error-expiry_date');
        const successEl = document.getElementById('success-expiry_date');

        // Remove validation classes
        input.classList.remove('valid', 'invalid');
        errorEl.classList.remove('show');
        successEl.classList.remove('show');

        if (value === '') {
            showError(input, errorEl, 'La date d\'expiration est obligatoire');
            validationState.expiry_date = false;
            return false;
        }

        // Check format MM/YY
        if (!/^\d{2}\/\d{2}$/.test(value)) {
            showError(input, errorEl, 'Format invalide (MM/AA)');
            validationState.expiry_date = false;
            return false;
        }

        const [month, year] = value.split('/').map(v => parseInt(v, 10));

        // Validate month
        if (month < 1 || month > 12) {
            showError(input, errorEl, 'Mois invalide (01-12)');
            validationState.expiry_date = false;
            return false;
        }

        // Check if card is expired
        const now = new Date();
        const currentYear = now.getFullYear() % 100; // Last 2 digits
        const currentMonth = now.getMonth() + 1;

        if (year < currentYear || (year === currentYear && month < currentMonth)) {
            showError(input, errorEl, 'La carte est expirée');
            validationState.expiry_date = false;
            return false;
        }

        // Check if expiry is too far in future (10 years max)
        if (year > currentYear + 10) {
            showError(input, errorEl, 'Date d\'expiration trop éloignée');
            validationState.expiry_date = false;
            return false;
        }

        // Valid
        showSuccess(input, successEl, 'Date valide');
        validationState.expiry_date = true;
        return true;
    }

    /**
     * CVV Validation
     */
    function setupCVVValidation() {
        const input = document.getElementById('cvv');
        if (!input) return;

        input.addEventListener('input', function(e) {
            // Only allow digits
            e.target.value = e.target.value.replace(/\D/g, '');
            validateCVV(e.target.value);
        });

        input.addEventListener('blur', function() {
            validateCVV(this.value);
        });
    }

    function validateCVV(value) {
        const input = document.getElementById('cvv');
        const errorEl = document.getElementById('error-cvv');
        const successEl = document.getElementById('success-cvv');

        // Remove validation classes
        input.classList.remove('valid', 'invalid');
        errorEl.classList.remove('show');
        successEl.classList.remove('show');

        if (value === '') {
            showError(input, errorEl, 'Le CVV est obligatoire');
            validationState.cvv = false;
            return false;
        }

        // Check if only digits
        if (!/^\d+$/.test(value)) {
            showError(input, errorEl, 'Le CVV ne doit contenir que des chiffres');
            validationState.cvv = false;
            return false;
        }

        // Check length (3 or 4 digits)
        if (value.length < 3 || value.length > 4) {
            showError(input, errorEl, 'Le CVV doit contenir 3 ou 4 chiffres');
            validationState.cvv = false;
            return false;
        }

        // Valid
        showSuccess(input, successEl, 'CVV valide');
        validationState.cvv = true;
        return true;
    }

    /**
     * Terms Checkbox Validation
     */
    function setupTermsValidation() {
        const checkbox = document.getElementById('accept_terms');
        if (!checkbox) return;

        checkbox.addEventListener('change', function() {
            validateTerms(this.checked);
        });
    }

    function validateTerms(checked) {
        const errorEl = document.getElementById('error-accept_terms');
        
        errorEl.classList.remove('show');
        
        if (!checked) {
            errorEl.textContent = 'Vous devez accepter les conditions';
            errorEl.classList.add('show');
            validationState.accept_terms = false;
            return false;
        }

        validationState.accept_terms = true;
        return true;
    }

    /**
     * Step Navigation
     */
    function setupStepNavigation() {
        const btnNext = document.getElementById('btn-next-step');
        const btnPrev = document.getElementById('btn-prev-step');

        if (btnNext) {
            btnNext.addEventListener('click', function() {
                if (validateStep1()) {
                    goToStep(2);
                }
            });
        }

        if (btnPrev) {
            btnPrev.addEventListener('click', function() {
                goToStep(1);
            });
        }
    }

    function validateStep1() {
        // Trigger validation on all fields
        const cardHolder = document.getElementById('card_holder').value;
        const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
        const expiryDate = document.getElementById('expiry_date').value;
        const cvv = document.getElementById('cvv').value;

        const isValid = 
            validateCardHolder(cardHolder) &&
            validateCardNumber(cardNumber) &&
            validateExpiryDate(expiryDate) &&
            validateCVV(cvv);

        if (!isValid) {
            // Show error message
            showGlobalError('Veuillez corriger les erreurs avant de continuer');
        }

        return isValid;
    }

    function goToStep(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.form-step').forEach(step => {
            step.classList.remove('active');
        });

        // Show target step
        const targetStep = document.querySelector(`.form-step[data-step="${stepNumber}"]`);
        if (targetStep) {
            targetStep.classList.add('active');
        }

        // Update progress
        updateProgress(stepNumber);
        currentStep = stepNumber;
    }

    function updateProgress(stepNumber) {
        document.querySelectorAll('.step').forEach((step, index) => {
            const num = index + 1;
            if (num < stepNumber) {
                step.classList.add('completed');
                step.classList.remove('active');
            } else if (num === stepNumber) {
                step.classList.add('active');
                step.classList.remove('completed');
            } else {
                step.classList.remove('active', 'completed');
            }
        });

        document.querySelectorAll('.step-separator').forEach((sep, index) => {
            if (index < stepNumber - 1) {
                sep.classList.add('completed');
            } else {
                sep.classList.remove('completed');
            }
        });
    }

    /**
     * Form Submission
     */
    function handleFormSubmit(e) {
        e.preventDefault();

        // Validate all fields
        const cardHolder = document.getElementById('card_holder').value;
        const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
        const expiryDate = document.getElementById('expiry_date').value;
        const cvv = document.getElementById('cvv').value;
        const termsChecked = document.getElementById('accept_terms').checked;

        const isValid = 
            validateCardHolder(cardHolder) &&
            validateCardNumber(cardNumber) &&
            validateExpiryDate(expiryDate) &&
            validateCVV(cvv) &&
            validateTerms(termsChecked);

        if (!isValid) {
            showGlobalError('Veuillez corriger toutes les erreurs avant de soumettre');
            return;
        }

        // Disable submit button
        const submitBtn = document.getElementById('btn-submit');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading"></span> Traitement en cours...';

        // Submit form
        e.target.submit();
    }

    /**
     * Helper Functions
     */
    function showError(input, errorEl, message) {
        input.classList.add('invalid');
        input.classList.remove('valid');
        errorEl.textContent = message;
        errorEl.classList.add('show');
    }

    function showSuccess(input, successEl, message) {
        input.classList.add('valid');
        input.classList.remove('invalid');
        successEl.textContent = message;
        successEl.classList.add('show');
    }

    function showGlobalError(message) {
        // Check if alert already exists
        let alert = document.querySelector('.alert-error');
        
        if (!alert) {
            alert = document.createElement('div');
            alert.className = 'alert alert-error';
            alert.innerHTML = `
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span></span>
            `;
            
            const container = document.querySelector('.payment-container');
            const header = document.querySelector('.payment-header');
            container.insertBefore(alert, header.nextSibling);
        }
        
        alert.querySelector('span').textContent = message;
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }

})();
