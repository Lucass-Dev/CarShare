/**
 * Global Search - Modern 2026 Real-time Search
 * Search for trips and users dynamically
 */

class GlobalSearch {
    constructor() {
        this.searchInput = document.getElementById('global-search');
        this.searchResults = document.getElementById('search-results');
        this.searchTimeout = null;
        this.currentQuery = '';
        this.init();
    }

    init() {
        if (!this.searchInput) return;

        // Real-time search
        this.searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            
            clearTimeout(this.searchTimeout);
            
            if (query.length < 2) {
                this.hideResults();
                return;
            }

            this.searchTimeout = setTimeout(() => {
                this.performSearch(query);
            }, 300);
        });

        // Focus/blur handlers
        this.searchInput.addEventListener('focus', () => {
            if (this.currentQuery.length >= 2) {
                this.showResults();
            }
        });

        // Click outside to close
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.header-search')) {
                this.hideResults();
            }
        });

        // Enter key to search page
        this.searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && this.currentQuery) {
                window.location.href = `/CarShare/index.php?action=search&q=${encodeURIComponent(this.currentQuery)}`;
            }
        });
    }

    async performSearch(query) {
        this.currentQuery = query;
        
        try {
            const response = await fetch(`/CarShare/api/search.php?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            this.displayResults(data);
        } catch (error) {
            console.error('Search error:', error);
            this.displayError();
        }
    }

    displayResults(data) {
        if (!data.users || data.users.length === 0) {
            this.displayNoResults();
            return;
        }

        let html = '';

        // Users section only (header search is for users only)
        html += '<div class="search-category">👤 Utilisateurs</div>';
        data.users.forEach(user => {
            html += this.createUserItem(user);
        });

        this.searchResults.innerHTML = html;
        this.showResults();
        this.attachResultHandlers();
    }

    createUserItem(user) {
        const rating = user.global_rating ? `⭐ ${parseFloat(user.global_rating).toFixed(1)}` : 'Nouveau';
        const vehicleInfo = user.car_brand ? '🚗 Conducteur' : '👥 Passager';
        
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

    createTripItem(trip) {
        const date = new Date(trip.start_date);
        const formattedDate = date.toLocaleDateString('fr-FR', { 
            day: 'numeric', 
            month: 'short',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        return `
            <div class="search-item" data-type="trip" data-id="${trip.id}">
                <div class="search-item-icon">🚗</div>
                <div class="search-item-content">
                    <div class="search-item-title">${this.escapeHtml(trip.start_location)} → ${this.escapeHtml(trip.end_location)}</div>
                    <div class="search-item-subtitle">${formattedDate} • ${trip.price}€ • ${trip.available_places} place(s)</div>
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

    displayError() {
        this.searchResults.innerHTML = `
            <div style="padding: 40px; text-align: center; color: #ef4444;">
                <div style="font-size: 48px; margin-bottom: 16px;">⚠️</div>
                <div style="font-weight: 600;">Erreur de recherche</div>
                <div style="font-size: 14px;">Veuillez réessayer</div>
            </div>
        `;
        this.showResults();
    }

    attachResultHandlers() {
        document.querySelectorAll('.search-item').forEach(item => {
            item.addEventListener('click', () => {
                const type = item.getAttribute('data-type');
                const id = item.getAttribute('data-id');
                
                if (type === 'user') {
                    window.location.href = `/CarShare/index.php?action=user_profile&id=${id}`;
                } else if (type === 'trip') {
                    window.location.href = `/CarShare/index.php?action=trip_details&id=${id}`;
                }
            });
        });
    }

    showResults() {
        this.searchResults.classList.add('active');
    }

    hideResults() {
        this.searchResults.classList.remove('active');
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new GlobalSearch();
});
