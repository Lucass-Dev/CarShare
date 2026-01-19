// Validation for signalement form
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.report-form');
    const descriptionField = document.getElementById('description');
    const reasonField = document.getElementById('reason');

    if (!form) return;

    form.addEventListener('submit', function(e) {
        const errors = [];

        // Validate reason
        if (!reasonField.value) {
            errors.push('Veuillez sélectionner un motif');
            reasonField.style.borderColor = 'red';
        } else {
            reasonField.style.borderColor = '';
        }

        // Validate description
        if (!descriptionField.value.trim()) {
            errors.push('La description est obligatoire');
            descriptionField.style.borderColor = 'red';
        } else if (descriptionField.value.trim().length < 10) {
            errors.push('La description doit contenir au moins 10 caractères');
            descriptionField.style.borderColor = 'red';
        } else {
            descriptionField.style.borderColor = '';
        }

        // If there are errors, prevent submission
        if (errors.length > 0) {
            e.preventDefault();
            if (window.notificationManager) {
                window.notificationManager.showMultiple(errors, 'error');
            } else {
                showNotification('Erreurs de validation:\n\n' + errors.join('\n'), 'error', 8000);
            }
        }
    });

    // Real-time validation feedback
    if (descriptionField) {
        descriptionField.addEventListener('input', function() {
            if (this.value.trim().length >= 10) {
                this.style.borderColor = '';
            }
        });
    }

    if (reasonField) {
        reasonField.addEventListener('change', function() {
            if (this.value) {
                this.style.borderColor = '';
            }
        });
    }
});
