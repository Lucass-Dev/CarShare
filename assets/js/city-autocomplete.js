/**
 * City Autocomplete - Autosuggestion des villes françaises
 * Réutilisable sur tous les formulaires avec champs de ville
 */

class CityAutocomplete {
    constructor(inputElement, options = {}) {
        this.input = inputElement;
        this.options = {
            minChars: 2,
            maxResults: 8,
            debounce: 150,
            placeholder: 'Entrez une ville...',
            ...options
        };
        
        this.timeout = null;
        this.resultsContainer = null;
        this.currentFocus = -1;
        this.cache = new Map();
        
        this.init();
    }

    init() {
        if (!this.input) {
            console.error('[CityAutocomplete] Input element not found');
            return;
        }

        // Créer le container de résultats
        this.createResultsContainer();
        
        // Attacher les événements
        this.attachEvents();
        
        console.log('[CityAutocomplete] Initialized for', this.input.name || this.input.id);
    }

    createResultsContainer() {
        this.resultsContainer = document.createElement('div');
        this.resultsContainer.className = 'city-autocomplete-results';
        this.resultsContainer.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        `;
        
        // Positionner le container
        const inputParent = this.input.parentElement;
        if (getComputedStyle(inputParent).position === 'static') {
            inputParent.style.position = 'relative';
        }
        inputParent.appendChild(this.resultsContainer);
    }

    attachEvents() {
        // Input event avec debounce
        this.input.addEventListener('input', (e) => {
            clearTimeout(this.timeout);
            const query = e.target.value.trim();
            
            if (query.length < this.options.minChars) {
                this.hideResults();
                return;
            }
            
            this.timeout = setTimeout(() => {
                this.searchCities(query);
            }, this.options.debounce);
        });

        // Navigation clavier
        this.input.addEventListener('keydown', (e) => {
            const items = this.resultsContainer.querySelectorAll('.city-autocomplete-item');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.currentFocus++;
                this.setActive(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.currentFocus--;
                this.setActive(items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (this.currentFocus > -1 && items[this.currentFocus]) {
                    items[this.currentFocus].click();
                }
            } else if (e.key === 'Escape') {
                this.hideResults();
            }
        });

        // Blur - fermer avec délai pour permettre le clic
        this.input.addEventListener('blur', () => {
            setTimeout(() => this.hideResults(), 200);
        });

        // Focus - réafficher si des résultats existent
        this.input.addEventListener('focus', () => {
            if (this.resultsContainer.children.length > 0 && this.input.value.length >= this.options.minChars) {
                this.showResults();
            }
        });
    }

    async searchCities(query) {
        const cacheKey = query.toLowerCase();
        
        // Vérifier le cache
        if (this.cache.has(cacheKey)) {
            this.displayResults(this.cache.get(cacheKey));
            return;
        }

        try {
            // API gouvernementale française des communes
            const response = await fetch(
                `https://geo.api.gouv.fr/communes?nom=${encodeURIComponent(query)}&fields=nom,code,codesPostaux,population&limit=${this.options.maxResults}`,
                {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                }
            );
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            const cities = await response.json();
            
            // Mettre en cache
            this.cache.set(cacheKey, cities);
            
            this.displayResults(cities);
        } catch (error) {
            console.error('[CityAutocomplete] Search error:', error);
            this.displayError();
        }
    }

    displayResults(cities) {
        if (!cities || cities.length === 0) {
            this.displayNoResults();
            return;
        }

        let html = '';
        cities.forEach(city => {
            const postalCodes = city.codesPostaux?.join(', ') || '';
            const population = city.population ? this.formatNumber(city.population) : '';
            
            html += `
                <div class="city-autocomplete-item" data-city="${this.escapeHtml(city.nom)}" data-postal="${postalCodes}">
                    <div style="font-weight: 600; color: #1f2937;">${this.highlightQuery(city.nom)}</div>
                    <div style="font-size: 12px; color: #6b7280;">
                        ${postalCodes ? `📮 ${postalCodes}` : ''}
                        ${population ? ` • 👥 ${population} hab.` : ''}
                    </div>
                </div>
            `;
        });

        this.resultsContainer.innerHTML = html;
        this.showResults();
        this.attachItemHandlers();
    }

    displayNoResults() {
        this.resultsContainer.innerHTML = `
            <div style="padding: 20px; text-align: center; color: #6b7280; font-size: 14px;">
                🔍 Aucune ville trouvée
            </div>
        `;
        this.showResults();
    }

    displayError() {
        this.resultsContainer.innerHTML = `
            <div style="padding: 20px; text-align: center; color: #ef4444; font-size: 14px;">
                ⚠️ Erreur de recherche
            </div>
        `;
        this.showResults();
    }

    attachItemHandlers() {
        const items = this.resultsContainer.querySelectorAll('.city-autocomplete-item');
        
        items.forEach((item, index) => {
            item.style.cssText = `
                padding: 12px 16px;
                cursor: pointer;
                border-bottom: 1px solid #f3f4f6;
                transition: background-color 0.15s;
            `;
            
            item.addEventListener('mouseenter', () => {
                this.currentFocus = index;
                this.setActive(items);
            });
            
            item.addEventListener('click', () => {
                const cityName = item.getAttribute('data-city');
                this.input.value = cityName;
                
                // Dispatch change event pour les formulaires
                this.input.dispatchEvent(new Event('change', { bubbles: true }));
                
                this.hideResults();
            });
        });
    }

    setActive(items) {
        if (!items || items.length === 0) return;
        
        // Retirer toutes les classes active
        items.forEach(item => {
            item.style.backgroundColor = 'white';
        });
        
        // Ajuster le focus
        if (this.currentFocus >= items.length) this.currentFocus = 0;
        if (this.currentFocus < 0) this.currentFocus = items.length - 1;
        
        // Activer l'élément courant
        if (items[this.currentFocus]) {
            items[this.currentFocus].style.backgroundColor = '#f3f4f6';
            items[this.currentFocus].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        }
    }

    highlightQuery(text) {
        const query = this.input.value.trim();
        if (!query) return this.escapeHtml(text);
        
        const regex = new RegExp(`(${query})`, 'gi');
        return this.escapeHtml(text).replace(regex, '<strong style="color: #3b82f6;">$1</strong>');
    }

    showResults() {
        this.resultsContainer.style.display = 'block';
    }

    hideResults() {
        this.resultsContainer.style.display = 'none';
        this.currentFocus = -1;
    }

    formatNumber(num) {
        return new Intl.NumberFormat('fr-FR').format(num);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    destroy() {
        if (this.resultsContainer) {
            this.resultsContainer.remove();
        }
        this.cache.clear();
    }
}

// Fonction helper pour initialiser sur plusieurs inputs
function initCityAutocomplete(selector, options = {}) {
    const inputs = typeof selector === 'string' 
        ? document.querySelectorAll(selector) 
        : [selector];
    
    const instances = [];
    inputs.forEach(input => {
        if (input) {
            instances.push(new CityAutocomplete(input, options));
        }
    });
    
    return instances.length === 1 ? instances[0] : instances;
}

// Export pour utilisation globale
window.CityAutocomplete = CityAutocomplete;
window.initCityAutocomplete = initCityAutocomplete;

// Auto-initialisation sur les champs avec data-city-autocomplete
document.addEventListener('DOMContentLoaded', () => {
    const cityInputs = document.querySelectorAll('[data-city-autocomplete]');
    cityInputs.forEach(input => {
        new CityAutocomplete(input);
    });
});
