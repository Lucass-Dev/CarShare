/**
 * Payment Form Validation - Version compatible avec trip_payment.css
 * Validation en temps réel des champs de paiement
 */

(function() {
    'use strict';

    // État de validation
    const validationState = {
        card_holder: false,
        card_number: false,
        expiry_date: false,
        cvv: false,
        accept_terms: false
    };

    // Variable pour empêcher les soumissions multiples
    let isSubmitting = false;

    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        initializeForm();
        setupSeatsCountHandler();
        checkForErrors();
        updateSubmitButton(); // Initialiser l'état du bouton
    });

    /**
     * Met à jour l'état du bouton selon la validation
     */
    function updateSubmitButton() {
        const submitBtn = document.getElementById('btn-submit');
        if (!submitBtn) return;

        const allValid = Object.values(validationState).every(v => v === true);
        
        if (allValid) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        } else {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';
        }
    }

    /**
     * Vérifier si la page contient des erreurs au chargement
     * (après soumission du formulaire avec erreurs)
     */
    function checkForErrors() {
        const errorMessage = document.querySelector('.error-message');
        const submitBtn = document.getElementById('btn-submit');
        
        if (errorMessage && submitBtn) {
            // Réinitialiser l'état de soumission et le bouton si des erreurs sont présentes
            isSubmitting = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Confirmer la réservation
            `;
        }
    }

    /**
     * Gestion du nombre de places
     */
    function setupSeatsCountHandler() {
        const seatsSelect = document.getElementById('seats_count');
        const totalAmountEl = document.getElementById('total-amount');
        const selectedSeatsEl = document.getElementById('selected-seats');
        const seatsRow = document.getElementById('seats-row');
        
        if (!seatsSelect || !totalAmountEl) return;

        seatsSelect.addEventListener('change', function() {
            const seatsCount = parseInt(this.value) || 1;
            const unitPrice = parseFloat(totalAmountEl.dataset.unitPrice) || 0;
            const totalPrice = seatsCount * unitPrice;
            
            totalAmountEl.textContent = totalPrice.toFixed(2) + ' €';
            
            if (selectedSeatsEl) {
                selectedSeatsEl.textContent = seatsCount;
            }
            
            if (seatsRow) {
                seatsRow.style.display = seatsCount > 1 ? 'flex' : 'none';
            }
        });
    }

    function initializeForm() {
        const form = document.getElementById('payment-form');
        if (!form) return;

        // Validation des champs
        setupCardHolderValidation();
        setupCardNumberValidation();
        setupExpiryDateValidation();
        setupCVVValidation();
        setupTermsValidation();

        // Soumission du formulaire
        form.addEventListener('submit', handleFormSubmit);
    }

    /**
     * Validation du nom du titulaire
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

        value = value.trim();

        clearValidation(input, errorEl);

        if (value === '') {
            showError(input, errorEl, 'Le nom du titulaire est obligatoire');
            validationState.card_holder = false;
            updateSubmitButton();
            return false;
        }

        if (/[0-9<>{}[\]\\|;:@#$%^&*()+=~`]/.test(value)) {
            showError(input, errorEl, 'Le nom ne doit contenir que des lettres et espaces');
            validationState.card_holder = false;
            return false;
        }

        if (/(\bSELECT\b|\bINSERT\b|\bUPDATE\b|\bDELETE\b|\bDROP\b|--|;|\/\*)/i.test(value)) {
            showError(input, errorEl, 'Format de nom invalide');
            validationState.card_holder = false;
            return false;
        }

        if (value.length < 2) {
            showError(input, errorEl, 'Le nom doit contenir au moins 2 caractères');
            validationState.card_holder = false;
            return false;
        }

        if (value.length > 50) {
            showError(input, errorEl, 'Le nom ne peut pas dépasser 50 caractères');
            validationState.card_holder = false;
            return false;
        }

        showSuccess(input);
        validationState.card_holder = true;
        updateSubmitButton();
        return true;
    }

    /**
     * Validation du numéro de carte
     */
    function setupCardNumberValidation() {
        const input = document.getElementById('card_number');
        if (!input) return;

        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            value = value.replace(/[^0-9]/g, '');
            
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

        clearValidation(input, errorEl);

        if (value === '') {
            showError(input, errorEl, 'Le numéro de carte est obligatoire');
            validationState.card_number = false;
            updateSubmitButton();
            return false;
        }

        if (!/^\d+$/.test(value)) {
            showError(input, errorEl, 'Le numéro de carte ne doit contenir que des chiffres');
            validationState.card_number = false;
            updateSubmitButton();
            return false;
        }

        if (value.length !== 16) {
            showError(input, errorEl, 'Le numéro de carte doit contenir 16 chiffres');
            validationState.card_number = false;
            updateSubmitButton();
            return false;
        }

        if (!luhnCheck(value)) {
            showError(input, errorEl, 'Numéro de carte invalide');
            validationState.card_number = false;
            updateSubmitButton();
            return false;
        }

        showSuccess(input);
        validationState.card_number = true;
        updateSubmitButton();
        return true;
    }

    /**
     * Algorithme de Luhn pour valider le numéro de carte
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
     * Validation de la date d'expiration
     */
    function setupExpiryDateValidation() {
        const input = document.getElementById('expiry_date');
        if (!input) return;

        input.addEventListener('input', function(e) {
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

        clearValidation(input, errorEl);

        if (value === '') {
            showError(input, errorEl, 'La date d\'expiration est obligatoire');
            validationState.expiry_date = false;
            updateSubmitButton();
            return false;
        }

        if (!/^\d{2}\/\d{2}$/.test(value)) {
            showError(input, errorEl, 'Format invalide (MM/AA)');
            validationState.expiry_date = false;
            updateSubmitButton();
            return false;
        }

        const [month, year] = value.split('/').map(v => parseInt(v, 10));

        if (month < 1 || month > 12) {
            showError(input, errorEl, 'Mois invalide (01-12)');
            validationState.expiry_date = false;
            updateSubmitButton();
            return false;
        }

        const now = new Date();
        const currentYear = now.getFullYear() % 100;
        const currentMonth = now.getMonth() + 1;

        if (year < currentYear || (year === currentYear && month < currentMonth)) {
            showError(input, errorEl, 'La carte est expirée');
            validationState.expiry_date = false;
            updateSubmitButton();
            return false;
        }

        if (year > currentYear + 10) {
            showError(input, errorEl, 'Date d\'expiration trop éloignée');
            validationState.expiry_date = false;
            updateSubmitButton();
            return false;
        }

        showSuccess(input);
        validationState.expiry_date = true;
        updateSubmitButton();
        return true;
    }

    /**
     * Validation du CVV
     */
    function setupCVVValidation() {
        const input = document.getElementById('cvv');
        if (!input) return;

        input.addEventListener('input', function(e) {
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

        clearValidation(input, errorEl);

        if (value === '') {
            showError(input, errorEl, 'Le CVV est obligatoire');
            validationState.cvv = false;
            updateSubmitButton();
            return false;
        }

        if (!/^\d+$/.test(value)) {
            showError(input, errorEl, 'Le CVV ne doit contenir que des chiffres');
            validationState.cvv = false;
            updateSubmitButton();
            return false;
        }

        if (value.length < 3 || value.length > 4) {
            showError(input, errorEl, 'Le CVV doit contenir 3 ou 4 chiffres');
            validationState.cvv = false;
            updateSubmitButton();
            return false;
        }

        showSuccess(input);
        validationState.cvv = true;
        updateSubmitButton();
        return true;
    }

    /**
     * Validation des termes
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
        
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.style.display = 'none';
        }
        
        if (!checked) {
            if (errorEl) {
                errorEl.textContent = 'Vous devez accepter les conditions';
                errorEl.style.display = 'block';
            }
            validationState.accept_terms = false;
            updateSubmitButton();
            return false;
        }

        validationState.accept_terms = true;
        updateSubmitButton();
        return true;
    }

    /**
     * Soumission du formulaire
     */
    function handleFormSubmit(e) {
        e.preventDefault();

        // Empêcher les soumissions multiples
        if (isSubmitting) {
            return false;
        }

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
            return false;
        }

        // Marquer comme en cours de soumission
        isSubmitting = true;

        const submitBtn = document.getElementById('btn-submit');
        const originalBtnContent = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span style="display: inline-block; width: 16px; height: 16px; border: 2px solid rgba(5,18,32,.3); border-radius: 50%; border-top-color: #051220; animation: spin 1s linear infinite;"></span> Traitement en cours...';

        // Timeout de sécurité (30 secondes)
        const timeoutId = setTimeout(function() {
            isSubmitting = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
            showGlobalError('La requête a pris trop de temps. Veuillez réessayer.');
        }, 30000);

        // Soumettre le formulaire
        try {
            e.target.submit();
        } catch (error) {
            clearTimeout(timeoutId);
            isSubmitting = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
            showGlobalError('Une erreur est survenue lors de la soumission. Veuillez réessayer.');
        }

        return false;
    }

    /**
     * Fonctions utilitaires
     */
    function clearValidation(input, errorEl) {
        input.style.borderColor = '';
        input.style.background = '';
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.style.display = 'none';
        }
    }

    function showError(input, errorEl, message) {
        input.style.borderColor = '#dc3545';
        input.style.background = 'linear-gradient(135deg, #fff5f5 0%, #fee 100%)';
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.style.display = 'block';
            errorEl.style.color = '#dc3545';
            errorEl.style.fontSize = '13px';
            errorEl.style.marginTop = '6px';
        }
    }

    function showSuccess(input) {
        input.style.borderColor = '#28a745';
        input.style.background = 'linear-gradient(135deg, #f0fff4 0%, #dcfce7 100%)';
    }

    function showGlobalError(message) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        let alert = document.querySelector('.error-message');
        
        if (!alert) {
            alert = document.createElement('div');
            alert.className = 'error-message';
            alert.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span></span>
            `;
            
            const container = document.querySelector('.payment-container');
            container.insertBefore(alert, container.firstChild);
        }
        
        alert.querySelector('span').textContent = message;
        
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }

})();
