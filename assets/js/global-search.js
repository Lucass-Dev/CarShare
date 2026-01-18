/**
 * Global Search - Modern 2026 Real-time Search with Auto-suggestion
 * Version optimisée avec affichage instantané des suggestions et URLs dynamiques
 */

class GlobalSearch {
    constructor() {
        this.searchInput = document.getElementById('global-search');
        this.searchResults = document.getElementById('search-results');
        this.searchTimeout = null;
        this.currentQuery = '';
        this.isSearching = false;
        this.cache = new Map(); // Cache des résultats pour performance
        
        console.log('[GlobalSearch] Initialisation...', {
            searchInput: !!this.searchInput,
            searchResults: !!this.searchResults
        });
        
        this.init();
    }

    init() {
        if (!this.searchInput) {
            console.error('[GlobalSearch] Input de recherche non trouvé !');
            return;
        }
        
        if (!this.searchResults) {
            console.error('[GlobalSearch] Container de résultats non trouvé !');
            return;
        }

        console.log('[GlobalSearch] Attachement des événements...');

        // Real-time search avec affichage IMMÉDIAT des suggestions
        this.searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            
            console.log('[GlobalSearch] Input event:', { query, length: query.length });
            
            clearTimeout(this.searchTimeout);
            
            if (query.length < 2) {
                this.hideResults();
                this.currentQuery = '';
                return;
            }

            // Afficher immédiatement un loader
            this.showLoader();
            
            // DÉBOUNCE RÉDUIT à 150ms pour réactivité maximale
            this.searchTimeout = setTimeout(() => {
                console.log('[GlobalSearch] Performing search for:', query);
                this.performSearch(query);
            }, 150);
        });

        // Focus - réafficher les résultats si disponibles
        this.searchInput.addEventListener('focus', () => {
            console.log('[GlobalSearch] Focus event');
            if (this.currentQuery.length >= 2 && this.searchResults.innerHTML) {
                this.showResults();
            }
        });

        // Click outside to close
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.header-search')) {
                this.hideResults();
            }
        });

        // Enter key to user search page
        this.searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && this.searchInput.value.trim()) {
                const query = this.searchInput.value.trim();
                console.log('[GlobalSearch] Enter pressed, redirecting to:', query);
                window.location.href = url(`index.php?action=user_search&q=${encodeURIComponent(query)}`);
            }
        });

        console.log('[GlobalSearch] Initialisation complète ✓');
    }

    showLoader() {
        this.searchResults.innerHTML = `
            <div style="padding: 30px; text-align: center; color: #6b7280;">
                <div class="spinner" style="display: inline-block; width: 30px; height: 30px; border: 3px solid #f3f4f6; border-top: 3px solid #3b82f6; border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                <div style="margin-top: 10px; font-size: 14px;">Recherche en cours...</div>
            </div>
        `;
        this.showResults();
    }

    async performSearch(query) {
        this.currentQuery = query;
        
        // Vérifier le cache
        if (this.cache.has(query)) {
            console.log('[GlobalSearch] Résultat en cache');
            this.displayResults(this.cache.get(query));
            return;
        }
        
        if (this.isSearching) {
            console.log('[GlobalSearch] Recherche déjà en cours, ignorée');
            return;
        }
        
        this.isSearching = true;
        
        try {
            console.log('[GlobalSearch] Fetching:', apiUrl(`search.php?q=${encodeURIComponent(query)}`));
            
            const response = await fetch(apiUrl(`search.php?q=${encodeURIComponent(query)}`), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            console.log('[GlobalSearch] Résultats reçus:', data);
            
            // Mettre en cache
            this.cache.set(query, data);
            
            this.displayResults(data);
        } catch (error) {
            console.error('[GlobalSearch] Erreur de recherche:', error);
            this.displayError(error.message);
        } finally {
            this.isSearching = false;
        }
    }

    displayResults(data) {
        if (!data.users || data.users.length === 0) {
            console.log('[GlobalSearch] Aucun résultat');
            this.displayNoResults();
            return;
        }

        console.log('[GlobalSearch] Affichage de', data.users.length, 'résultats');
        
        let html = '';

        // Users section with quick preview
        html += `<div class="search-category">👤 ${data.users.length} Utilisateur${data.users.length > 1 ? 's' : ''} trouvé${data.users.length > 1 ? 's' : ''}</div>`;
        
        // Limiter à 5 résultats pour l'autosuggestion
        const displayUsers = data.users.slice(0, 5);
        displayUsers.forEach(user => {
            html += this.createUserItem(user);
        });
        
        // Afficher un lien "Voir tous les résultats" si plus de 5
        if (data.users.length > 5) {
            const seeAllUrl = url(`index.php?action=user_search&q=${encodeURIComponent(this.currentQuery)}`);
            html += `
                <div class="search-see-all" onclick="window.location.href='${seeAllUrl}'">
                    <div style="padding: 15px; text-align: center; color: #3b82f6; font-weight: 600; cursor: pointer; border-top: 1px solid #e5e7eb;">
                        👉 Voir tous les résultats (${data.users.length})
                    </div>
                </div>
            `;
        }

        this.searchResults.innerHTML = html;
        this.showResults();
        this.attachResultHandlers();
    }

    createUserItem(user) {
        const rating = user.global_rating ? `⭐ ${parseFloat(user.global_rating).toFixed(1)}` : 'Nouveau';
        const vehicleInfo = user.car_brand ? `🚗 ${this.escapeHtml(user.car_brand)} ${this.escapeHtml(user.car_model || '')}` : '👥 Passager';
        
        return `
            <div class="search-item" data-type="user" data-id="${user.id}">
                <div class="search-item-icon">👤</div>
                <div class="search-item-content">
                    <div class="search-item-title">${this.escapeHtml(user.first_name)} ${this.escapeHtml(user.last_name)}</div>
                    <div class="search-item-subtitle">${vehicleInfo} • ${rating}</div>
                </div>
            </div>
        `;
    }

    displayNoResults() {
        this.searchResults.innerHTML = `
            <div style="padding: 40px; text-align: center; color: #6b7280;">
                <div style="font-size: 48px; margin-bottom: 16px;">🔍</div>
                <div style="font-weight: 600; margin-bottom: 8px;">Aucun résultat</div>
                <div style="font-size: 14px;">Essayez avec d'autres mots-clés</div>
            </div>
        `;
        this.showResults();
    }

    displayError(errorMsg) {
        console.error('[GlobalSearch] Affichage erreur:', errorMsg);
        this.searchResults.innerHTML = `
            <div style="padding: 40px; text-align: center; color: #ef4444;">
                <div style="font-size: 48px; margin-bottom: 16px;">⚠️</div>
                <div style="font-weight: 600; margin-bottom: 8px;">Erreur de recherche</div>
                <div style="font-size: 14px;">${this.escapeHtml(errorMsg)}</div>
                <div style="font-size: 12px; margin-top: 8px; color: #666;">Veuillez réessayer</div>
            </div>
        `;
        this.showResults();
    }

    attachResultHandlers() {
        document.querySelectorAll('.search-item').forEach(item => {
            item.addEventListener('click', () => {
                const type = item.getAttribute('data-type');
                const id = item.getAttribute('data-id');
                
                console.log('[GlobalSearch] Click sur résultat:', { type, id });
                
                if (type === 'user') {
                    window.location.href = url(`index.php?action=user_profile&id=${id}`);
                } else if (type === 'trip') {
                    window.location.href = url(`index.php?action=trip_details&id=${id}`);
                }
            });
        });
    }

    showResults() {
        if (this.searchResults) {
            this.searchResults.classList.add('active');
            console.log('[GlobalSearch] Résultats affichés');
        }
    }

    hideResults() {
        if (this.searchResults) {
            this.searchResults.classList.remove('active');
            console.log('[GlobalSearch] Résultats masqués');
        }
    }

    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize global search
let searchInstance = null;

function initGlobalSearch() {
    if (!document.getElementById('global-search')) {
        console.log('[GlobalSearch] Élément non trouvé, attente...');
        return;
    }
    
    if (!searchInstance) {
        console.log('[GlobalSearch] Création nouvelle instance');
        searchInstance = new GlobalSearch();
    }
}

// Ajouter les styles CSS pour l'animation du spinner
if (!document.querySelector('style[data-global-search]')) {
    const style = document.createElement('style');
    style.setAttribute('data-global-search', 'true');
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
}

// Multiple initialization strategies
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGlobalSearch);
} else {
    initGlobalSearch();
}

window.addEventListener('load', initGlobalSearch);
setTimeout(initGlobalSearch, 0);
setTimeout(initGlobalSearch, 500);

// Exposer pour debugging
window.globalSearchDebug = {
    getInstance: () => searchInstance,
    reinit: initGlobalSearch
};
