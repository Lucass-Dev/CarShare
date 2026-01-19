/**
 * Pagination System - Système de pagination réutilisable style Google
 * À utiliser sur toutes les pages avec listes longues
 */

class Pagination {
    constructor(options = {}) {
        this.options = {
            container: null,          // Container contenant les éléments
            items: [],               // Array des éléments à paginer
            itemsPerPage: 10,        // Nombre d'éléments par page
            maxButtons: 7,           // Nombre max de boutons de pagination
            currentPage: 1,          // Page actuelle
            onChange: null,          // Callback appelé lors du changement de page
            ...options
        };
        
        this.totalPages = Math.ceil(this.options.items.length / this.options.itemsPerPage);
        this.paginationContainer = null;
        
        this.init();
    }

    init() {
        if (!this.options.container) {
            console.error('[Pagination] Container non fourni');
            return;
        }

        // Créer le container de pagination
        this.createPaginationContainer();
        
        // Afficher la première page
        this.goToPage(this.options.currentPage);
    }

    createPaginationContainer() {
        this.paginationContainer = document.createElement('div');
        this.paginationContainer.className = 'pagination-container';
        this.paginationContainer.style.cssText = `
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin: 40px 0 20px 0;
            flex-wrap: wrap;
        `;
        
        // Insérer après le container
        this.options.container.parentNode.insertBefore(
            this.paginationContainer, 
            this.options.container.nextSibling
        );
    }

    goToPage(pageNumber) {
        if (pageNumber < 1 || pageNumber > this.totalPages) return;
        
        this.options.currentPage = pageNumber;
        
        // Calculer les indices
        const startIndex = (pageNumber - 1) * this.options.itemsPerPage;
        const endIndex = startIndex + this.options.itemsPerPage;
        
        // Masquer tous les éléments
        this.options.items.forEach(item => {
            item.style.display = 'none';
        });
        
        // Afficher les éléments de la page actuelle
        const pageItems = this.options.items.slice(startIndex, endIndex);
        pageItems.forEach(item => {
            item.style.display = '';
        });
        
        // Mettre à jour les boutons
        this.renderButtons();
        
        // Scroller en haut
        this.options.container.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Callback
        if (typeof this.options.onChange === 'function') {
            this.options.onChange({
                currentPage: pageNumber,
                totalPages: this.totalPages,
                startIndex,
                endIndex,
                pageItems
            });
        }
    }

    renderButtons() {
        if (this.totalPages <= 1) {
            this.paginationContainer.innerHTML = '';
            return;
        }

        let html = '';
        
        // Info de page
        const startItem = ((this.options.currentPage - 1) * this.options.itemsPerPage) + 1;
        const endItem = Math.min(this.options.currentPage * this.options.itemsPerPage, this.options.items.length);
        
        html += `
            <div class="pagination-info" style="margin-right: 20px; color: #6b7280; font-size: 14px; white-space: nowrap;">
                ${startItem}-${endItem} sur ${this.options.items.length}
            </div>
        `;
        
        // Bouton Précédent
        html += this.createButton('« Précédent', 'prev', this.options.currentPage === 1);
        
        // Boutons de pages
        const pages = this.calculatePageButtons();
        pages.forEach(page => {
            if (page === '...') {
                html += `<span style="padding: 0 8px; color: #9ca3af;">...</span>`;
            } else {
                html += this.createButton(
                    page, 
                    'page', 
                    false, 
                    page === this.options.currentPage
                );
            }
        });
        
        // Bouton Suivant
        html += this.createButton('Suivant »', 'next', this.options.currentPage === this.totalPages);
        
        this.paginationContainer.innerHTML = html;
        this.attachButtonHandlers();
    }

    calculatePageButtons() {
        const current = this.options.currentPage;
        const total = this.totalPages;
        const max = this.options.maxButtons;
        
        if (total <= max) {
            return Array.from({ length: total }, (_, i) => i + 1);
        }
        
        const pages = [];
        const half = Math.floor(max / 2);
        
        // Toujours afficher la première page
        pages.push(1);
        
        let start = Math.max(2, current - half);
        let end = Math.min(total - 1, current + half);
        
        // Ajuster si on est près du début
        if (current <= half + 1) {
            end = max - 1;
        }
        
        // Ajuster si on est près de la fin
        if (current >= total - half) {
            start = total - max + 2;
        }
        
        // Ajouter ... si nécessaire au début
        if (start > 2) {
            pages.push('...');
        }
        
        // Ajouter les pages du milieu
        for (let i = start; i <= end; i++) {
            pages.push(i);
        }
        
        // Ajouter ... si nécessaire à la fin
        if (end < total - 1) {
            pages.push('...');
        }
        
        // Toujours afficher la dernière page
        if (total > 1) {
            pages.push(total);
        }
        
        return pages;
    }

    createButton(label, type, disabled = false, active = false) {
        const baseStyle = `
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: white;
            color: #374151;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            white-space: nowrap;
        `;
        
        let style = baseStyle;
        let extraClass = '';
        
        if (disabled) {
            style += `
                opacity: 0.4;
                cursor: not-allowed;
            `;
            extraClass = 'disabled';
        } else if (active) {
            style += `
                background: #3b82f6;
                color: white;
                border-color: #3b82f6;
            `;
            extraClass = 'active';
        } else {
            style += `
                &:hover {
                    background: #f3f4f6;
                    border-color: #d1d5db;
                }
            `;
        }
        
        return `
            <button 
                class="pagination-btn ${extraClass}" 
                data-type="${type}" 
                data-page="${label}"
                style="${style}"
                ${disabled ? 'disabled' : ''}
            >
                ${label}
            </button>
        `;
    }

    attachButtonHandlers() {
        const buttons = this.paginationContainer.querySelectorAll('.pagination-btn:not(.disabled)');
        
        buttons.forEach(btn => {
            // Ajouter hover styles
            if (!btn.classList.contains('active')) {
                btn.addEventListener('mouseenter', () => {
                    btn.style.background = '#f3f4f6';
                    btn.style.borderColor = '#d1d5db';
                });
                btn.addEventListener('mouseleave', () => {
                    btn.style.background = 'white';
                    btn.style.borderColor = '#e5e7eb';
                });
            }
            
            btn.addEventListener('click', () => {
                const type = btn.getAttribute('data-type');
                const page = btn.getAttribute('data-page');
                
                if (type === 'prev') {
                    this.goToPage(this.options.currentPage - 1);
                } else if (type === 'next') {
                    this.goToPage(this.options.currentPage + 1);
                } else if (type === 'page') {
                    this.goToPage(parseInt(page));
                }
            });
        });
    }

    updateItems(newItems) {
        this.options.items = newItems;
        this.totalPages = Math.ceil(newItems.length / this.options.itemsPerPage);
        this.goToPage(1);
    }

    destroy() {
        if (this.paginationContainer) {
            this.paginationContainer.remove();
        }
        
        // Réafficher tous les éléments
        this.options.items.forEach(item => {
            item.style.display = '';
        });
    }
}

// Fonction helper pour initialiser rapidement
function initPagination(containerSelector, itemsSelector, options = {}) {
    const container = document.querySelector(containerSelector);
    const items = Array.from(document.querySelectorAll(itemsSelector));
    
    if (!container || items.length === 0) {
        console.warn('[Pagination] Container ou items non trouvés');
        return null;
    }
    
    return new Pagination({
        container,
        items,
        ...options
    });
}

// Export pour utilisation globale
window.Pagination = Pagination;
window.initPagination = initPagination;
