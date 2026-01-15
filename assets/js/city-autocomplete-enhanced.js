/**
 * City Autocomplete - Real-time search with debouncing
 * Searches cities from database as user types
 */

class CityAutocomplete {
    constructor(inputElement, options = {}) {
        this.input = inputElement;
        this.options = {
            minChars: 2,
            debounceDelay: 300,
            apiUrl: '/CarShare/api/cities.php',
            ...options
        };
        
        this.resultsContainer = null;
        this.debounceTimer = null;
        this.currentFocus = -1;
        this.cities = [];
        
        this.init();
    }
    
    init() {
        // Create results container
        this.resultsContainer = document.createElement('div');
        this.resultsContainer.className = 'city-autocomplete-results';
        this.input.parentNode.style.position = 'relative';
        this.input.parentNode.appendChild(this.resultsContainer);
        
        // Event listeners
        this.input.addEventListener('input', (e) => this.handleInput(e));
        this.input.addEventListener('keydown', (e) => this.handleKeydown(e));
        this.input.addEventListener('focus', (e) => {
            if (this.input.value.length >= this.options.minChars) {
                this.handleInput(e);
            }
        });
        
        // Close on click outside
        document.addEventListener('click', (e) => {
            if (e.target !== this.input) {
                this.close();
            }
        });
    }
    
    handleInput(e) {
        const value = this.input.value.trim();
        
        // Remove the selectedFromList flag when user types manually
        if (this.input.dataset.selectedFromList) {
            delete this.input.dataset.selectedFromList;
        }
        
        // Clear previous timer
        clearTimeout(this.debounceTimer);
        
        if (value.length < this.options.minChars) {
            this.close();
            return;
        }
        
        // Debounce the search
        this.debounceTimer = setTimeout(() => {
            this.searchCities(value);
        }, this.options.debounceDelay);
    }
    
    async searchCities(query) {
        try {
            const response = await fetch(`${this.options.apiUrl}?q=${encodeURIComponent(query)}`);
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.error || 'Erreur de recherche');
            }
            
            const data = await response.json();
            
            // Check if error in response
            if (data.error) {
                throw new Error(data.error);
            }
            
            this.cities = Array.isArray(data) ? data : [];
            this.renderResults();
            
        } catch (error) {
            console.error('Erreur lors de la recherche de villes:', error);
            this.showError(error.message || 'Erreur lors de la recherche');
        }
    }
    
    renderResults() {
        this.resultsContainer.innerHTML = '';
        this.currentFocus = -1;
        
        if (this.cities.length === 0) {
            this.resultsContainer.innerHTML = '<div class="city-autocomplete-item no-results">Aucune ville trouvée</div>';
            this.resultsContainer.style.display = 'block';
            return;
        }
        
        this.cities.forEach((city, index) => {
            const item = document.createElement('div');
            item.className = 'city-autocomplete-item';
            item.innerHTML = `
                <div class="city-name">${this.highlightMatch(city.name, this.input.value)}</div>
                <div class="city-postal">${city.postal_code}</div>
            `;
            
            item.addEventListener('click', () => {
                this.selectCity(city);
            });
            
            this.resultsContainer.appendChild(item);
        });
        
        this.resultsContainer.style.display = 'block';
    }
    
    highlightMatch(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<strong>$1</strong>');
    }
    
    selectCity(city) {
        // Update value and metadata first
        this.input.value = city.name;
        this.input.dataset.cityId = city.id;
        this.input.dataset.cityName = city.name;
        this.input.dataset.postalCode = city.postal_code;
        this.input.dataset.selectedFromList = 'true';
        
        // Remove any error styling
        this.input.classList.remove('field--invalid');
        const errorMsg = this.input.parentElement.querySelector('.field-error');
        if (errorMsg) errorMsg.remove();
        
        // Mark as valid immediately
        this.input.classList.add('field--valid');
        
        this.close();
        
        // Trigger validation events after value and flag are set
        // Use requestAnimationFrame to ensure DOM is updated
        requestAnimationFrame(() => {
            this.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }
    
    handleKeydown(e) {
        const items = this.resultsContainer.querySelectorAll('.city-autocomplete-item:not(.no-results)');
        
        if (items.length === 0) return;
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.currentFocus++;
                if (this.currentFocus >= items.length) this.currentFocus = 0;
                this.setActive(items);
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                this.currentFocus--;
                if (this.currentFocus < 0) this.currentFocus = items.length - 1;
                this.setActive(items);
                break;
                
            case 'Enter':
                e.preventDefault();
                if (this.currentFocus > -1 && items[this.currentFocus]) {
                    items[this.currentFocus].click();
                }
                break;
                
            case 'Escape':
                this.close();
                break;
        }
    }
    
    setActive(items) {
        items.forEach(item => item.classList.remove('active'));
        if (items[this.currentFocus]) {
            items[this.currentFocus].classList.add('active');
            items[this.currentFocus].scrollIntoView({ block: 'nearest' });
        }
    }
    
    showError(message) {
        this.resultsContainer.innerHTML = `<div class="city-autocomplete-item error">${message}</div>`;
        this.resultsContainer.style.display = 'block';
    }
    
    close() {
        this.resultsContainer.style.display = 'none';
        this.resultsContainer.innerHTML = '';
        this.currentFocus = -1;
    }
}

// Initialize autocomplete on all city input fields
document.addEventListener('DOMContentLoaded', function() {
    // Home page search
    const homeStartPlace = document.querySelector('input[name="start_place"]');
    const homeEndPlace = document.querySelector('input[name="end_place"]');
    
    if (homeStartPlace) new CityAutocomplete(homeStartPlace);
    if (homeEndPlace) new CityAutocomplete(homeEndPlace);
    
    // Search page
    const searchStartPlace = document.getElementById('start_place');
    const searchEndPlace = document.getElementById('end_place');
    
    if (searchStartPlace) new CityAutocomplete(searchStartPlace);
    if (searchEndPlace) new CityAutocomplete(searchEndPlace);
    
    // Trip form
    const depCity = document.getElementById('dep-city');
    const arrCity = document.getElementById('arr-city');
    
    if (depCity) new CityAutocomplete(depCity);
    if (arrCity) new CityAutocomplete(arrCity);
});
