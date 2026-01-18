/**
 * Login Page - Modern enhancements
 */
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const emailInput = document.querySelector('input[type="email"]');
    const passwordInput = document.querySelector('input[type="password"]');

    // Remember email feature
    const savedEmail = localStorage.getItem('carshare_email');
    if (savedEmail && emailInput) {
        emailInput.value = savedEmail;
        emailInput.closest('.input-wrapper')?.classList.add('focused');
    }

    // Save email on successful login
    if (form) {
        form.addEventListener('submit', () => {
            if (emailInput && emailInput.value) {
                localStorage.setItem('carshare_email', emailInput.value);
            }
        });
    }

    // Show error animation if error exists
    const errorMessage = document.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.style.animation = 'errorShake 0.4s ease';
        FormEnhancer.showError(errorMessage.textContent);
    }

    // Auto-focus on empty field
    setTimeout(() => {
        if (emailInput && !emailInput.value) {
            emailInput.focus();
        } else if (passwordInput && !passwordInput.value) {
            passwordInput.focus();
        }
    }, 100);
});
