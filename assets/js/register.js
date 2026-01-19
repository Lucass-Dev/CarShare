/**
 * Register Page - Initialize password validation
 */
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmInput = document.querySelector('input[name="confirm_password"]');

    if (passwordInput) {
        // Initialize password validator
        const validator = new PasswordValidator(passwordInput, confirmInput);

        // Enhance form submission
        const form = passwordInput.closest('form');
        if (form) {
            form.addEventListener('submit', (e) => {
                if (!validator.isValid()) {
                    e.preventDefault();
                    FormEnhancer.showError('Veuillez corriger les erreurs dans le formulaire');
                }
            });
        }
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
