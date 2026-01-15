// Client-side validation for create-trip form
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.trip-form');
    
    if (!form) return;

    // Real-time field validation on blur (before submit)
    setupRealtimeValidation();

    form.addEventListener('submit', function(e) {
        const errors = [];
        
        // Get form fields
        const depCity = document.getElementById('dep-city');
        const arrCity = document.getElementById('arr-city');
        const depNum = document.getElementById('dep-num');
        const arrNum = document.getElementById('arr-num');
        const depStreet = document.getElementById('dep-street');
        const arrStreet = document.getElementById('arr-street');
        const date = document.getElementById('date');
        const places = document.getElementById('places');
        const price = document.getElementById('price');

        // Validate departure city
        if (!depCity.value.trim()) {
            errors.push('La ville de départ est obligatoire');
            markFieldAsInvalid(depCity);
        } else {
            markFieldAsValid(depCity);
        }

        // Validate arrival city
        if (!arrCity.value.trim()) {
            errors.push('La ville d\'arrivée est obligatoire');
            markFieldAsInvalid(arrCity);
        } else {
            markFieldAsValid(arrCity);
        }

        // Validate cities are different
        if (depCity.value.trim() && arrCity.value.trim() && 
            depCity.value.trim() === arrCity.value.trim()) {
            errors.push('La ville de départ et d\'arrivée doivent être différentes');
            markFieldAsInvalid(depCity);
            markFieldAsInvalid(arrCity);
        }

        // Validate date
        if (!date.value) {
            errors.push('La date est obligatoire');
            markFieldAsInvalid(date);
        } else {
            // Check if date is in the future
            const selectedDate = new Date(date.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                errors.push('La date doit être dans le futur');
                markFieldAsInvalid(date);
            } else {
                markFieldAsValid(date);
            }
        }

        // Validate number of places
        const placesValue = parseInt(places.value);
        if (isNaN(placesValue) || placesValue < 1 || placesValue > 10) {
            errors.push('Le nombre de places doit être entre 1 et 10');
            markFieldAsInvalid(places);
        } else {
            markFieldAsValid(places);
        }

        // Validate street names if provided
        const invalidCharsRegex = /[\x00-\x1F\x7F<>{}\[\]\\]/;
        
        if (depStreet.value && depStreet.value.trim() !== '') {
            if (invalidCharsRegex.test(depStreet.value)) {
                errors.push('Le nom de rue de départ contient des caractères invalides');
                markFieldAsInvalid(depStreet);
            } else if (depStreet.value.length > 100) {
                errors.push('Le nom de rue de départ est trop long (max 100 caractères)');
                markFieldAsInvalid(depStreet);
            } else {
                markFieldAsValid(depStreet);
            }
        } else {
            markFieldAsValid(depStreet);
        }

        if (arrStreet.value && arrStreet.value.trim() !== '') {
            if (invalidCharsRegex.test(arrStreet.value)) {
                errors.push('Le nom de rue d\'arrivée contient des caractères invalides');
                markFieldAsInvalid(arrStreet);
            } else if (arrStreet.value.length > 100) {
                errors.push('Le nom de rue d\'arrivée est trop long (max 100 caractères)');
                markFieldAsInvalid(arrStreet);
            } else {
                markFieldAsValid(arrStreet);
            }
        } else {
            markFieldAsValid(arrStreet);
        }

        // Validate street numbers if provided
        if (depNum.value && depNum.value.trim() !== '') {
            const cleanNum = depNum.value.replace(/[^\d]/g, '');
            const depNumValue = parseInt(cleanNum);
            if (isNaN(depNumValue) || depNumValue < 0 || depNumValue > 99999) {
                errors.push('Le numéro de voie de départ doit être un nombre entre 0 et 99999');
                markFieldAsInvalid(depNum);
            } else {
                markFieldAsValid(depNum);
            }
        } else {
            markFieldAsValid(depNum);
        }

        if (arrNum.value && arrNum.value.trim() !== '') {
            const cleanNum = arrNum.value.replace(/[^\d]/g, '');
            const arrNumValue = parseInt(cleanNum);
            if (isNaN(arrNumValue) || arrNumValue < 0 || arrNumValue > 99999) {
                errors.push('Le numéro de voie d\'arrivée doit être un nombre entre 0 et 99999');
                markFieldAsInvalid(arrNum);
            } else {
                markFieldAsValid(arrNum);
            }
        } else {
            markFieldAsValid(arrNum);
        }

        // Validate price if provided
        if (price.value && price.value.trim() !== '') {
            const priceValue = parseFloat(price.value);
            if (isNaN(priceValue) || priceValue < 0) {
                errors.push('Le prix doit être un nombre positif');
                markFieldAsInvalid(price);
            } else {
                markFieldAsValid(price);
            }
        } else {
            markFieldAsValid(price);
        }

        // If there are errors, prevent submission and show them
        if (errors.length > 0) {
            e.preventDefault();
            if (typeof showError === 'function') {
                showError('Erreurs de validation:\n\n' + errors.join('\n'));
            } else {
                alert('Erreurs de validation:\n\n' + errors.join('\n'));
            }
        }
    });

    // Helper functions for field validation styling
    function markFieldAsInvalid(field) {
        field.style.borderColor = '#d9534f';
        field.style.backgroundColor = '#ffebee';
    }

    function markFieldAsValid(field) {
        field.style.borderColor = '';
        field.style.backgroundColor = '';
    }

    // Real-time validation setup
    function setupRealtimeValidation() {
        const depCity = document.getElementById('dep-city');
        const arrCity = document.getElementById('arr-city');
        const dateInput = document.getElementById('date');
        const placesInput = document.getElementById('places');
        const depNum = document.getElementById('dep-num');
        const arrNum = document.getElementById('arr-num');
        const priceInput = document.getElementById('price');

        // Departure city validation
        if (depCity) {
            depCity.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    markFieldAsInvalid(this);
                } else {
                    markFieldAsValid(this);
                }
            });

            depCity.addEventListener('input', function() {
                if (this.value.trim()) {
                    markFieldAsValid(this);
                }
            });
        }

        // Arrival city validation
        if (arrCity) {
            arrCity.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    markFieldAsInvalid(this);
                } else {
                    markFieldAsValid(this);
                }
            });

            arrCity.addEventListener('input', function() {
                if (this.value.trim()) {
                    markFieldAsValid(this);
                }
            });
        }

        // Date validation
        if (dateInput) {
            dateInput.addEventListener('blur', function() {
                if (!this.value) {
                    markFieldAsInvalid(this);
                } else {
                    const selectedDate = new Date(this.value);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    if (selectedDate < today) {
                        markFieldAsInvalid(this);
                    } else {
                        markFieldAsValid(this);
                    }
                }
            });

            dateInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate >= today) {
                    markFieldAsValid(this);
                }
            });
        }

        // Places validation
        if (placesInput) {
            placesInput.addEventListener('blur', function() {
                const value = parseInt(this.value);
                if (isNaN(value) || value < 1 || value > 10) {
                    markFieldAsInvalid(this);
                } else {
                    markFieldAsValid(this);
                }
            });

            placesInput.addEventListener('change', function() {
                const value = parseInt(this.value);
                if (!isNaN(value) && value >= 1 && value <= 10) {
                    markFieldAsValid(this);
                }
            });
        }

        // Street name validation (real-time)
        const depStreet = document.getElementById('dep-street');
        const arrStreet = document.getElementById('arr-street');
        const invalidCharsRegex = /[\x00-\x1F\x7F<>{}\[\]\\]/;

        if (depStreet) {
            depStreet.addEventListener('input', function() {
                if (this.value === '') {
                    markFieldAsValid(this);
                } else if (invalidCharsRegex.test(this.value)) {
                    markFieldAsInvalid(this);
                } else if (this.value.length > 100) {
                    markFieldAsInvalid(this);
                } else {
                    markFieldAsValid(this);
                }
            });

            depStreet.addEventListener('blur', function() {
                if (this.value !== '' && (invalidCharsRegex.test(this.value) || this.value.length > 100)) {
                    markFieldAsInvalid(this);
                }
            });
        }

        if (arrStreet) {
            arrStreet.addEventListener('input', function() {
                if (this.value === '') {
                    markFieldAsValid(this);
                } else if (invalidCharsRegex.test(this.value)) {
                    markFieldAsInvalid(this);
                } else if (this.value.length > 100) {
                    markFieldAsInvalid(this);
                } else {
                    markFieldAsValid(this);
                }
            });

            arrStreet.addEventListener('blur', function() {
                if (this.value !== '' && (invalidCharsRegex.test(this.value) || this.value.length > 100)) {
                    markFieldAsInvalid(this);
                }
            });
        }

        // Number fields validation (improved)
        if (depNum) {
            depNum.addEventListener('input', function() {
                const cleanValue = this.value.replace(/[^\d]/g, '');
                if (this.value === '') {
                    markFieldAsValid(this);
                } else if (cleanValue === '' || parseInt(cleanValue) < 0 || parseInt(cleanValue) > 99999) {
                    markFieldAsInvalid(this);
                } else {
                    markFieldAsValid(this);
                }
            });
        }

        if (arrNum) {
            arrNum.addEventListener('input', function() {
                const cleanValue = this.value.replace(/[^\d]/g, '');
                if (this.value === '') {
                    markFieldAsValid(this);
                } else if (cleanValue === '' || parseInt(cleanValue) < 0 || parseInt(cleanValue) > 99999) {
                    markFieldAsInvalid(this);
                } else {
                    markFieldAsValid(this);
                }
            });
        }

        if (priceInput) {
            priceInput.addEventListener('input', function() {
                if (this.value === '' || (!isNaN(parseFloat(this.value)) && parseFloat(this.value) >= 0)) {
                    markFieldAsValid(this);
                }
            });
        }
    }
});
