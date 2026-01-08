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
