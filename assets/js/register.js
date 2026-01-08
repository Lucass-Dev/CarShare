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

    // Show welcome animation on successful validation
    if (window.location.search.includes('success')) {
        FormEnhancer.showSuccess('Inscription réussie ! Bienvenue sur CarShare 🎉');
    }
});
