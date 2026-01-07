// Client-side validation for create-trip form
async function createTrip() {
    const form = document.querySelector('.trip-form');
    
    if (!form) return;

    form.addEventListener('submit', function(e) {
        const errors = [];
        
        // Get form fields
        const depCity = document.getElementById('dep-city');
        const arrCity = document.getElementById('arr-city');
        const depNum = document.getElementById('dep-num');
        const arrNum = document.getElementById('arr-num');
        const date = document.getElementById('date');
        const places = document.getElementById('places');
        const price = document.getElementById('price');

        // Validate departure city
        if (!depCity.value.trim()) {
            errors.push('La ville de départ est obligatoire');
            depCity.style.borderColor = 'red';
        } else {
            depCity.style.borderColor = '';
        }

        // Validate arrival city
        if (!arrCity.value.trim()) {
            errors.push('La ville d\'arrivée est obligatoire');
            arrCity.style.borderColor = 'red';
        } else {
            arrCity.style.borderColor = '';
        }

        // Validate cities are different
        if (depCity.value.trim() && arrCity.value.trim() && 
            depCity.value.trim() === arrCity.value.trim()) {
            errors.push('La ville de départ et d\'arrivée doivent être différentes');
            depCity.style.borderColor = 'red';
            arrCity.style.borderColor = 'red';
        }

        // Validate date
        if (!date.value) {
            errors.push('La date est obligatoire');
            date.style.borderColor = 'red';
        } else {
            // Check if date is in the future
            const selectedDate = new Date(date.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                errors.push('La date doit être dans le futur');
                date.style.borderColor = 'red';
            } else {
                date.style.borderColor = '';
            }
        }

        // Validate number of places
        const placesValue = parseInt(places.value);
        if (isNaN(placesValue) || placesValue < 1 || placesValue > 10) {
            errors.push('Le nombre de places doit être entre 1 et 10');
            places.style.borderColor = 'red';
        } else {
            places.style.borderColor = '';
        }

        // Validate street numbers if provided
        if (depNum.value && depNum.value.trim() !== '') {
            const depNumValue = parseInt(depNum.value);
            if (isNaN(depNumValue) || depNumValue < 0) {
                errors.push('Le numéro de voie de départ doit être un nombre positif');
                depNum.style.borderColor = 'red';
            } else {
                depNum.style.borderColor = '';
            }
        } else {
            depNum.style.borderColor = '';
        }

        if (arrNum.value && arrNum.value.trim() !== '') {
            const arrNumValue = parseInt(arrNum.value);
            if (isNaN(arrNumValue) || arrNumValue < 0) {
                errors.push('Le numéro de voie d\'arrivée doit être un nombre positif');
                arrNum.style.borderColor = 'red';
            } else {
                arrNum.style.borderColor = '';
            }
        } else {
            arrNum.style.borderColor = '';
        }

        // Validate price if provided
        if (price.value && price.value.trim() !== '') {
            const priceValue = parseFloat(price.value);
            if (isNaN(priceValue) || priceValue < 0) {
                errors.push('Le prix doit être un nombre positif');
                price.style.borderColor = 'red';
            } else {
                price.style.borderColor = '';
            }
        } else {
            price.style.borderColor = '';
        }

        // If there are errors, prevent submission and show them
        if (errors.length > 0) {
            e.preventDefault();
            alert('Erreurs de validation:\n\n' + errors.join('\n'));
        }
    });

    // Real-time validation feedback
    const depCity = document.getElementById('dep-city');
    const arrCity = document.getElementById('arr-city');
    
    if (depCity && arrCity) {
        depCity.addEventListener('input', function() {
            if (this.value.trim()) {
                this.style.borderColor = '';
            }
        });
        
        arrCity.addEventListener('input', function() {
            if (this.value.trim()) {
                this.style.borderColor = '';
            }
        });
    }

    const dateInput = document.getElementById('date');
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate >= today) {
                this.style.borderColor = '';
            }
        });
    }

    const placesInput = document.getElementById('places');
    if (placesInput) {
        placesInput.addEventListener('change', function() {
            const value = parseInt(this.value);
            if (!isNaN(value) && value >= 1 && value <= 10) {
                this.style.borderColor = '';
            }
        });
    }

    const depNum = document.getElementById('dep-num');
    if (depNum) {
        depNum.addEventListener('input', function() {
            if (this.value === '' || (!isNaN(parseInt(this.value)) && parseInt(this.value) >= 0)) {
                this.style.borderColor = '';
            }
        });
    }

    const arrNum = document.getElementById('arr-num');
    if (arrNum) {
        arrNum.addEventListener('input', function() {
            if (this.value === '' || (!isNaN(parseInt(this.value)) && parseInt(this.value) >= 0)) {
                this.style.borderColor = '';
            }
        });
    }

    const priceInput = document.getElementById('price');
    if (priceInput) {
        priceInput.addEventListener('input', function() {
            if (this.value === '' || (!isNaN(parseFloat(this.value)) && parseFloat(this.value) >= 0)) {
                this.style.borderColor = '';
            }
        });
    }
}
