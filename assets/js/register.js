/**
 * Register Page - Initialize password validation
 */
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmInput = document.querySelector('input[name="confirm_password"]');
    const submitBtn = document.getElementById('register-submit-btn');
    const form = document.getElementById('register-form');

    let validator = null;

    if (passwordInput) {
        // Initialize password validator
        validator = new PasswordValidator(passwordInput, confirmInput);

        // Enhance form submission
        if (form) {
            form.addEventListener('submit', (e) => {
                if (!validator.isValid()) {
                    e.preventDefault();
                    if (typeof FormEnhancer !== 'undefined') {
                        FormEnhancer.showError('Veuillez corriger les erreurs dans le formulaire');
                    }
                }
            });
        }
    }

    // ✅ Fonction de validation complète du formulaire
    function validateAllFields() {
        if (!form || !submitBtn) return;

        const lastName = form.querySelector('input[name="last_name"]');
        const firstName = form.querySelector('input[name="first_name"]');
        const email = form.querySelector('input[name="email"]');
        const emailConfirm = form.querySelector('input[name="email_confirm"]');
        const password = form.querySelector('input[name="password"]');
        const confirmPassword = form.querySelector('input[name="confirm_password"]');
        const cguCheckbox = form.querySelector('input[name="accept_terms"]');

        // Vérifier que tous les champs obligatoires sont remplis
        const isLastNameValid = lastName && lastName.value.trim().length >= 2;
        const isFirstNameValid = firstName && firstName.value.trim().length >= 2;
        const isEmailValid = email && isValidEmail(email.value.trim()) && email.validity.valid;
        const isEmailConfirmValid = emailConfirm && email && emailConfirm.value === email.value && emailConfirm.value.trim() !== '';
        const isPasswordValid = password && password.value.length >= 12;
        const isPasswordConfirmValid = confirmPassword && password && confirmPassword.value === password.value && confirmPassword.value !== '';
        
        // Vérifier la force du mot de passe si validator existe
        let isPasswordStrongEnough = true;
        if (validator && password && password.value) {
            const pwd = password.value;
            isPasswordStrongEnough = pwd.length >= 12 &&
                /[A-Z]/.test(pwd) &&
                /[a-z]/.test(pwd) &&
                /[0-9]/.test(pwd) &&
                /[!@#$%^&*(),.?":{}|<>]/.test(pwd);
        }

        // Pour formulaire normal : vérifier CGU
        let isCguAccepted = true;
        if (cguCheckbox) {
            isCguAccepted = cguCheckbox.checked;
        }

        // Tous les champs doivent être valides
        const allValid = isLastNameValid && 
                        isFirstNameValid && 
                        isEmailValid && 
                        isEmailConfirmValid && 
                        isPasswordValid && 
                        isPasswordStrongEnough &&
                        isPasswordConfirmValid &&
                        isCguAccepted;

        // Activer/désactiver le bouton
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

    // Désactiver le bouton au départ
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        submitBtn.style.cursor = 'not-allowed';
    }

    // Écouter tous les changements dans le formulaire
    if (form) {
        const allInputs = form.querySelectorAll('input, textarea, select');
        allInputs.forEach(input => {
            input.addEventListener('input', validateAllFields);
            input.addEventListener('change', validateAllFields);
            input.addEventListener('blur', validateAllFields);
        });

        // Vérifier immédiatement au chargement
        setTimeout(validateAllFields, 100);
    }

    // Add character counter to name fields
    const nameInputs = document.querySelectorAll('input[name="first_name"], input[name="last_name"]');
    nameInputs.forEach(input => {
        input.addEventListener('input', () => {
            // Remove numbers and special characters
            input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s-]/g, '');
            
            // Capitalize first letter
            if (input.value.length > 0) {
                input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1);
            }
        });
    });

    // Email duplicate check (AJAX)
    const emailInput = document.querySelector('input[name="email"]');
    const emailConfirmInput = document.querySelector('input[name="email_confirm"]');
    
    if (emailInput) {
        let emailCheckTimeout;
        const emailError = document.getElementById('email-error') || createErrorElement(emailInput);
        
        emailInput.addEventListener('input', () => {
            clearTimeout(emailCheckTimeout);
            const email = emailInput.value.trim();
            
            // Basic email format validation
            if (!email || !isValidEmail(email)) {
                emailError.textContent = '';
                return;
            }
            
            // Debounce API call
            emailCheckTimeout = setTimeout(async () => {
                try {
                    const basePath = window.APP_CONFIG?.basePath || '';
                    const response = await fetch(basePath + 'assets/api/check-email.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ email: email })
                    });
                    
                    const result = await response.json();
                    
                    if (!result.available) {
                        emailError.textContent = result.message || 'Cet email est déjà utilisé';
                        emailError.style.color = '#dc2626';
                        emailInput.setCustomValidity('Email déjà utilisé');
                    } else {
                        emailError.textContent = '✓ Email disponible';
                        emailError.style.color = '#16a34a';
                        emailInput.setCustomValidity('');
                    }
                } catch (error) {
                    console.error('Erreur lors de la vérification de l\'email:', error);
                    emailError.textContent = '';
                }
            }, 500);
        });
    }

    // Show welcome animation on successful validation
    if (window.location.search.includes('success')) {
        FormEnhancer.showSuccess('Inscription réussie ! Bienvenue sur CarShare 🎉');
    }
});

// Helper functions
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function createErrorElement(inputElement) {
    let errorSpan = inputElement.nextElementSibling;
    if (!errorSpan || !errorSpan.classList.contains('validation-error')) {
        errorSpan = document.createElement('span');
        errorSpan.className = 'validation-error';
        errorSpan.id = inputElement.name + '-error';
        inputElement.parentNode.insertBefore(errorSpan, inputElement.nextSibling);
    }
    return errorSpan;
}
