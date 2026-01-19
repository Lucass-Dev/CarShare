/**
 * Profile Page - Edit Mode Toggle (GitHub-style)
 * Handles switching between view and edit modes
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Profile Edit
    const editProfileBtn = document.getElementById('editProfileBtn');
    const profileForm = document.getElementById('profileForm');
    const cancelBtn = document.getElementById('cancelBtn');
    
    if (editProfileBtn && profileForm) {
        const profileDisplays = profileForm.querySelectorAll('.info-display');
        const profileInputs = profileForm.querySelectorAll('.form-input');
        const profileFooter = profileForm.querySelector('.card-footer');
        
        // Store original values
        let originalValues = {};
        
        editProfileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Store original values before editing
            profileInputs.forEach(input => {
                originalValues[input.name] = input.value;
            });
            
            // Switch to edit mode
            profileDisplays.forEach(display => display.style.display = 'none');
            profileInputs.forEach(input => input.style.display = 'block');
            profileFooter.style.display = 'flex';
            editProfileBtn.style.display = 'none';
        });
        
        cancelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Restore original values
            profileInputs.forEach(input => {
                if (originalValues[input.name] !== undefined) {
                    input.value = originalValues[input.name];
                }
            });
            
            // Switch back to view mode
            profileDisplays.forEach(display => display.style.display = 'block');
            profileInputs.forEach(input => input.style.display = 'none');
            profileFooter.style.display = 'none';
            editProfileBtn.style.display = 'inline-flex';
        });
    }
    
    // Vehicle Edit
    const editVehicleBtn = document.getElementById('editVehicleBtn');
    const vehicleForm = document.getElementById('vehicleForm');
    const cancelVehicleBtn = document.getElementById('cancelVehicleBtn');
    
    if (editVehicleBtn && vehicleForm) {
        const vehicleDisplays = vehicleForm.querySelectorAll('.info-display');
        const vehicleInputs = vehicleForm.querySelectorAll('.form-input');
        const vehicleFooter = vehicleForm.querySelector('.card-footer');
        
        // Store original values
        let originalVehicleValues = {};
        
        editVehicleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Store original values before editing
            vehicleInputs.forEach(input => {
                originalVehicleValues[input.name] = input.value;
            });
            
            // Switch to edit mode
            vehicleDisplays.forEach(display => display.style.display = 'none');
            vehicleInputs.forEach(input => input.style.display = 'block');
            vehicleFooter.style.display = 'flex';
            editVehicleBtn.style.display = 'none';
        });
        
        cancelVehicleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Restore original values
            vehicleInputs.forEach(input => {
                if (originalVehicleValues[input.name] !== undefined) {
                    input.value = originalVehicleValues[input.name];
                }
            });
            
            // Switch back to view mode
            vehicleDisplays.forEach(display => display.style.display = 'block');
            vehicleInputs.forEach(input => input.style.display = 'none');
            vehicleFooter.style.display = 'none';
            editVehicleBtn.style.display = 'inline-flex';
        });
    }
    
    // Password Edit with strength indicator
    const editPasswordBtn = document.getElementById('editPasswordBtn');
    const passwordForm = document.getElementById('passwordForm');
    const cancelPasswordBtn = document.getElementById('cancelPasswordBtn');
    
    if (editPasswordBtn && passwordForm) {
        const passwordInfo = document.getElementById('passwordInfo');
        const passwordFields = passwordForm.querySelector('.password-fields');
        const passwordFooter = passwordForm.querySelector('.card-footer');
        const newPasswordInput = passwordForm.querySelector('input[name="new_password"]');
        const confirmPasswordInput = passwordForm.querySelector('input[name="confirm_new_password"]');
        const currentPasswordInput = passwordForm.querySelector('input[name="current_password"]');
        
        editPasswordBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Switch to edit mode
            if (passwordInfo) passwordInfo.style.display = 'none';
            passwordFields.style.display = 'block';
            passwordFooter.style.display = 'flex';
            editPasswordBtn.style.display = 'none';
        });
        
        cancelPasswordBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Clear all fields
            passwordForm.querySelectorAll('input[type="password"]').forEach(input => {
                input.value = '';
            });
            
            // Reset strength indicator
            const strengthMeter = passwordForm.querySelector('.password-strength-meter');
            if (strengthMeter) {
                const fill = strengthMeter.querySelector('.strength-bar-fill');
                const text = strengthMeter.querySelector('.strength-text');
                if (fill) fill.style.width = '0%';
                if (text) text.textContent = '';
                strengthMeter.querySelectorAll('[data-req]').forEach(item => {
                    item.classList.remove('met');
                });
            }
            
            // Reset match indicator
            const matchIndicator = passwordForm.querySelector('.password-match-indicator');
            if (matchIndicator) {
                matchIndicator.className = 'password-match-indicator';
                matchIndicator.textContent = '';
            }
            
            // Switch back to view mode
            if (passwordInfo) passwordInfo.style.display = 'block';
            passwordFields.style.display = 'none';
            passwordFooter.style.display = 'none';
            editPasswordBtn.style.display = 'inline-flex';
        });
        
        // Password strength indicator
        if (newPasswordInput) {
            newPasswordInput.addEventListener('input', function() {
                updatePasswordStrength(this.value, passwordForm);
            });
        }
        
        // Password match indicator
        if (confirmPasswordInput && newPasswordInput) {
            const matchIndicator = passwordForm.querySelector('.password-match-indicator');
            
            const checkMatch = () => {
                const newPass = newPasswordInput.value;
                const confirmPass = confirmPasswordInput.value;
                
                if (confirmPass.length === 0) {
                    matchIndicator.className = 'password-match-indicator';
                    matchIndicator.textContent = '';
                    return;
                }
                
                if (newPass === confirmPass) {
                    matchIndicator.className = 'password-match-indicator valid';
                    matchIndicator.innerHTML = '<span class="icon">✓</span> Les mots de passe correspondent';
                } else {
                    matchIndicator.className = 'password-match-indicator invalid';
                    matchIndicator.innerHTML = '<span class="icon">✗</span> Les mots de passe ne correspondent pas';
                }
            };
            
            newPasswordInput.addEventListener('input', checkMatch);
            confirmPasswordInput.addEventListener('input', checkMatch);
        }
        
        // Form validation
        passwordForm.addEventListener('submit', function(e) {
            const currentPass = currentPasswordInput.value;
            const newPass = newPasswordInput.value;
            const confirmPass = confirmPasswordInput.value;
            
            if (!currentPass) {
                e.preventDefault();
                showPasswordError('Veuillez entrer votre mot de passe actuel');
                return;
            }
            
            if (!validatePassword(newPass)) {
                e.preventDefault();
                showPasswordError('Le nouveau mot de passe ne respecte pas les critères de sécurité');
                return;
            }
            
            if (newPass !== confirmPass) {
                e.preventDefault();
                showPasswordError('Les nouveaux mots de passe ne correspondent pas');
                return;
            }
            
            if (currentPass === newPass) {
                e.preventDefault();
                showPasswordError('Le nouveau mot de passe doit être différent de l\'ancien');
                return;
            }
        });
    }
    
    // Helper function to update password strength
    function updatePasswordStrength(password, form) {
        const requirements = {
            length: password.length >= 12,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };

        const met = Object.values(requirements).filter(Boolean).length;
        const percentage = (met / 5) * 100;

        const meter = form.querySelector('.password-strength-meter');
        if (!meter) return;
        
        const fill = meter.querySelector('.strength-bar-fill');
        const text = meter.querySelector('.strength-text');

        if (fill) fill.style.width = percentage + '%';

        // Update color and text based on strength
        if (percentage < 40) {
            if (fill) fill.style.background = '#dc3545';
            if (text) {
                text.textContent = 'Faible';
                text.style.color = '#dc3545';
            }
        } else if (percentage < 80) {
            if (fill) fill.style.background = '#ffc107';
            if (text) {
                text.textContent = 'Moyen';
                text.style.color = '#ffc107';
            }
        } else {
            if (fill) fill.style.background = '#28a745';
            if (text) {
                text.textContent = 'Fort';
                text.style.color = '#28a745';
            }
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
    
    // Helper function to validate password
    function validatePassword(password) {
        return password.length >= 12 &&
               /[A-Z]/.test(password) &&
               /[a-z]/.test(password) &&
               /[0-9]/.test(password) &&
               /[^A-Za-z0-9]/.test(password);
    }
    
    // Helper function to show password error
    function showPasswordError(message) {
        const passwordForm = document.getElementById('passwordForm');
        if (!passwordForm) return;
        
        // Remove existing error
        const existingError = passwordForm.querySelector('.password-error');
        if (existingError) existingError.remove();
        
        // Create error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'password-error';
        errorDiv.innerHTML = `<strong>⚠️ ${message}</strong>`;
        
        passwordForm.insertBefore(errorDiv, passwordForm.querySelector('.card-body'));
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            errorDiv.style.transition = 'opacity 0.3s';
            errorDiv.style.opacity = '0';
            setTimeout(() => errorDiv.remove(), 300);
        }, 5000);
    }
    
    // Auto-hide messages after 5 seconds
    const messages = document.querySelectorAll('.message-box');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.transition = 'opacity 0.3s, transform 0.3s';
            message.style.opacity = '0';
            message.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                message.remove();
            }, 300);
        }, 5000);
    });
});
