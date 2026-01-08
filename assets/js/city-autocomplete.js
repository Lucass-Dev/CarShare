/**
 * City Autocomplete - Autocomplétion dynamique pour les champs de ville
 */
class CityAutocomplete {
    constructor() {
        this.initAutocomplete();
    }

    initAutocomplete() {
        const startInput = document.getElementById('start_place');
        const endInput = document.getElementById('end_place');
        const startSuggestionBox = document.getElementById('start-suggestion-box');
        const endSuggestionBox = document.getElementById('end-suggestion-box');
        const formStartInput = document.getElementById('form_start_input');
        const formEndInput = document.getElementById('form_end_input');

        if (startInput && startSuggestionBox) {
            this.setupAutocomplete(startInput, startSuggestionBox, formStartInput);
        }

        if (endInput && endSuggestionBox) {
            this.setupAutocomplete(endInput, endSuggestionBox, formEndInput);
        }
    }

    setupAutocomplete(input, suggestionBox, hiddenInput) {
        let debounceTimer;

        input.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();

            if (query.length < 2) {
                suggestionBox.style.display = 'none';
                return;
            }

            debounceTimer = setTimeout(() => {
                this.fetchCities(query, suggestionBox, input, hiddenInput);
            }, 300);
        });

        // Fermer les suggestions au clic extérieur
        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !suggestionBox.contains(e.target)) {
                suggestionBox.style.display = 'none';
            }
        });
    }

    async fetchCities(query, suggestionBox, input, hiddenInput) {
        try {
            const response = await fetch(`index.php?controller=search&action=get_cities&query=${encodeURIComponent(query)}`);
            const data = await response.json();

            if (data.cities && data.cities.length > 0) {
                this.displaySuggestions(data.cities, suggestionBox, input, hiddenInput);
            } else {
                suggestionBox.style.display = 'none';
            }
        } catch (error) {
            console.error('Erreur lors de la récupération des villes:', error);
            suggestionBox.style.display = 'none';
        }
    }

    displaySuggestions(cities, suggestionBox, input, hiddenInput) {
        suggestionBox.innerHTML = '';
        
        cities.forEach(city => {
            const div = document.createElement('div');
            div.className = 'suggestion-item';
            div.textContent = city.name;
            div.dataset.id = city.id;

            div.addEventListener('click', () => {
                input.value = city.name;
                if (hiddenInput) {
                    hiddenInput.value = city.id;
                }
                suggestionBox.style.display = 'none';
            });

            suggestionBox.appendChild(div);
        });

        suggestionBox.style.display = 'block';
    }
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    new CityAutocomplete();
});
