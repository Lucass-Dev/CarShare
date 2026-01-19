/**
 * Système d'autosuggestion pour les recherches admin
 */

class AdminAutoSuggest {
    constructor(inputElement, type, onSelect) {
        this.input = inputElement;
        this.type = type;
        this.onSelect = onSelect || ((value) => { this.input.value = value; });
        this.suggestionsContainer = null;
        this.currentFocus = -1;
        this.debounceTimer = null;
        this.minChars = 2;
        
        this.init();
    }
    
    init() {
        // Créer le conteneur des suggestions
        this.createSuggestionsContainer();
        
        // Écouter la saisie
        this.input.addEventListener('input', (e) => {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.handleInput(e.target.value);
            }, 300); // Debounce 300ms
        });
        
        // Navigation au clavier
        this.input.addEventListener('keydown', (e) => {
            this.handleKeyDown(e);
        });
        
        // Fermer en cliquant ailleurs
        document.addEventListener('click', (e) => {
            if (e.target !== this.input) {
                this.closeSuggestions();
            }
        });
    }
    
    createSuggestionsContainer() {
        this.suggestionsContainer = document.createElement('div');
        this.suggestionsContainer.className = 'admin-suggestions';
        this.suggestionsContainer.style.cssText = `
            position: absolute;
            background: white;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            width: ${this.input.offsetWidth}px;
            margin-top: -1px;
        `;
        
        // Positionner sous l'input
        this.input.parentNode.style.position = 'relative';
        this.input.parentNode.appendChild(this.suggestionsContainer);
    }
    
    async handleInput(value) {
        if (value.length < this.minChars) {
            this.closeSuggestions();
            return;
        }
        
        try {
            const suggestions = await this.fetchSuggestions(value);
            this.displaySuggestions(suggestions);
        } catch (error) {
            console.error('Erreur autosuggestion:', error);
        }
    }
    
    async fetchSuggestions(query) {
        const response = await fetch(
            `assets/api/admin_search_suggestions.php?type=${this.type}&q=${encodeURIComponent(query)}`
        );
        
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        
        return await response.json();
    }
    
    displaySuggestions(suggestions) {
        this.suggestionsContainer.innerHTML = '';
        
        if (suggestions.length === 0) {
            this.closeSuggestions();
            return;
        }
        
        suggestions.forEach((suggestion, index) => {
            const item = document.createElement('div');
            item.className = 'suggestion-item';
            item.style.cssText = `
                padding: 0.75rem 1rem;
                cursor: pointer;
                border-bottom: 1px solid #f3f4f6;
                transition: background-color 0.2s;
            `;
            
            // Label principal
            const label = document.createElement('div');
            label.style.cssText = 'font-weight: 500; color: #1f2937;';
            label.textContent = suggestion.label;
            item.appendChild(label);
            
            // Subtitle si présent
            if (suggestion.subtitle) {
                const subtitle = document.createElement('div');
                subtitle.style.cssText = 'font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;';
                subtitle.textContent = suggestion.subtitle;
                item.appendChild(subtitle);
            }
            
            // Badge vérifié pour les users
            if (suggestion.verified !== undefined) {
                const badge = document.createElement('span');
                badge.style.cssText = `
                    display: inline-block;
                    margin-left: 0.5rem;
                    padding: 0.125rem 0.5rem;
                    font-size: 0.75rem;
                    border-radius: 9999px;
                    background-color: ${suggestion.verified ? '#d1fae5' : '#fed7aa'};
                    color: ${suggestion.verified ? '#065f46' : '#92400e'};
                `;
                badge.textContent = suggestion.verified ? '✓ Vérifié' : 'Non vérifié';
                label.appendChild(badge);
            }
            
            // Hover effect
            item.addEventListener('mouseenter', () => {
                item.style.backgroundColor = '#f9fafb';
                this.currentFocus = index;
            });
            
            item.addEventListener('mouseleave', () => {
                item.style.backgroundColor = 'white';
            });
            
            // Sélection
            item.addEventListener('click', () => {
                this.selectSuggestion(suggestion);
            });
            
            this.suggestionsContainer.appendChild(item);
        });
        
        this.suggestionsContainer.style.display = 'block';
        this.currentFocus = -1;
    }
    
    selectSuggestion(suggestion) {
        const value = suggestion.value || suggestion.label;
        this.onSelect(value, suggestion);
        this.closeSuggestions();
    }
    
    handleKeyDown(e) {
        const items = this.suggestionsContainer.querySelectorAll('.suggestion-item');
        
        if (items.length === 0) return;
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            this.currentFocus++;
            if (this.currentFocus >= items.length) this.currentFocus = 0;
            this.setActive(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            this.currentFocus--;
            if (this.currentFocus < 0) this.currentFocus = items.length - 1;
            this.setActive(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (this.currentFocus > -1 && items[this.currentFocus]) {
                items[this.currentFocus].click();
            }
        } else if (e.key === 'Escape') {
            this.closeSuggestions();
        }
    }
    
    setActive(items) {
        items.forEach((item, index) => {
            if (index === this.currentFocus) {
                item.style.backgroundColor = '#eff6ff';
                item.style.borderLeft = '3px solid #6f9fe6';
            } else {
                item.style.backgroundColor = 'white';
                item.style.borderLeft = 'none';
            }
        });
    }
    
    closeSuggestions() {
        this.suggestionsContainer.style.display = 'none';
        this.suggestionsContainer.innerHTML = '';
        this.currentFocus = -1;
    }
}

// Initialisation automatique au chargement
document.addEventListener('DOMContentLoaded', () => {
    // Users search
    const usersSearch = document.querySelector('[data-autosuggest="users"]');
    if (usersSearch) {
        new AdminAutoSuggest(usersSearch, 'users');
    }
    
    // Cities search (trips)
    const citiesSearch = document.querySelector('[data-autosuggest="cities"]');
    if (citiesSearch) {
        new AdminAutoSuggest(citiesSearch, 'cities');
    }
    
    // Vehicles search
    const vehiclesSearch = document.querySelector('[data-autosuggest="vehicles"]');
    if (vehiclesSearch) {
        new AdminAutoSuggest(vehiclesSearch, 'vehicles');
    }
});
