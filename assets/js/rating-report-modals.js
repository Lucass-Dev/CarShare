/**
 * Modern Rating & Report System - Modal-based 2026
 * Dynamic modals instead of separate pages
 */

class RatingReportSystem {
    constructor() {
        this.init();
    }

    init() {
        this.attachRatingHandlers();
        this.attachReportHandlers();
    }

    attachRatingHandlers() {
        document.querySelectorAll('[data-action="rate-user"]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                this.openRatingModal(userId, userName);
            });
        });
    }

    attachReportHandlers() {
        document.querySelectorAll('[data-action="report-user"]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                this.openReportModal(userId, userName);
            });
        });
    }

    openRatingModal(userId, userName) {
        const modal = this.createModal(`
            <div class="modal-header">
                <h3>⭐ Noter ${userName}</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form class="rating-form" data-user-id="${userId}">
                <div class="rating-section">
                    <label>Note globale</label>
                    <div class="star-rating" data-rating="0">
                        ${[1,2,3,4,5].map(i => `<span class="star" data-value="${i}">★</span>`).join('')}
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Commentaire</label>
                    <textarea name="comment" rows="4" placeholder="Partagez votre expérience..." required></textarea>
                </div>

                <div class="criteria-ratings">
                    <div class="criterion">
                        <label>Ponctualité</label>
                        <div class="star-rating-small" data-criterion="punctuality" data-rating="0">
                            ${[1,2,3,4,5].map(i => `<span class="star-small" data-value="${i}">★</span>`).join('')}
                        </div>
                    </div>
                    
                    <div class="criterion">
                        <label>Amabilité</label>
                        <div class="star-rating-small" data-criterion="friendliness" data-rating="0">
                            ${[1,2,3,4,5].map(i => `<span class="star-small" data-value="${i}">★</span>`).join('')}
                        </div>
                    </div>
                    
                    <div class="criterion">
                        <label>Sécurité</label>
                        <div class="star-rating-small" data-criterion="safety" data-rating="0">
                            ${[1,2,3,4,5].map(i => `<span class="star-small" data-value="${i}">★</span>`).join('')}
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary modal-cancel">Annuler</button>
                    <button type="submit" class="btn-primary">Envoyer la note</button>
                </div>
            </form>
        `);

        this.initStarRating(modal);
        this.handleRatingSubmit(modal, userId);
    }

    openReportModal(userId, userName) {
        const modal = this.createModal(`
            <div class="modal-header">
                <h3>⚠️ Signaler ${userName}</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form class="report-form" data-user-id="${userId}">
                <div class="form-group">
                    <label>Motif du signalement *</label>
                    <select name="reason" required>
                        <option value="">-- Choisir un motif --</option>
                        <option value="inappropriate_behavior">Comportement inapproprié</option>
                        <option value="no_show">Absence sans prévenir</option>
                        <option value="unsafe_driving">Conduite dangereuse</option>
                        <option value="fraud">Fraude ou arnaque</option>
                        <option value="harassment">Harcèlement</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Description détaillée *</label>
                    <textarea name="description" rows="5" placeholder="Décrivez la situation..." required></textarea>
                </div>

                <div class="alert alert-info">
                    <strong>ℹ️ Information</strong>
                    <p>Les signalements sont examinés par notre équipe. Les informations restent confidentielles.</p>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary modal-cancel">Annuler</button>
                    <button type="submit" class="btn-danger">Envoyer le signalement</button>
                </div>
            </form>
        `);

        this.handleReportSubmit(modal, userId);
    }

    createModal(content) {
        const modal = document.createElement('div');
        modal.className = 'dynamic-modal';
        modal.innerHTML = `
            <div class="modal-overlay"></div>
            <div class="modal-container">
                ${content}
            </div>
        `;

        document.body.appendChild(modal);
        setTimeout(() => modal.classList.add('active'), 10);

        // Close handlers
        modal.querySelector('.modal-close')?.addEventListener('click', () => this.closeModal(modal));
        modal.querySelector('.modal-cancel')?.addEventListener('click', () => this.closeModal(modal));
        modal.querySelector('.modal-overlay')?.addEventListener('click', () => this.closeModal(modal));

        return modal;
    }

    initStarRating(modal) {
        modal.querySelectorAll('.star-rating, .star-rating-small').forEach(container => {
            const stars = container.querySelectorAll('.star, .star-small');
            
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    const rating = index + 1;
                    container.setAttribute('data-rating', rating);
                    
                    stars.forEach((s, i) => {
                        if (i < rating) {
                            s.classList.add('active');
                        } else {
                            s.classList.remove('active');
                        }
                    });
                });

                star.addEventListener('mouseenter', () => {
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.classList.add('hover');
                        } else {
                            s.classList.remove('hover');
                        }
                    });
                });
            });

            container.addEventListener('mouseleave', () => {
                stars.forEach(s => s.classList.remove('hover'));
            });
        });
    }

    handleRatingSubmit(modal, userId) {
        const form = modal.querySelector('.rating-form');
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const globalRating = modal.querySelector('.star-rating').getAttribute('data-rating');
            const comment = form.querySelector('[name="comment"]').value;
            const punctuality = modal.querySelector('[data-criterion="punctuality"]').getAttribute('data-rating');
            const friendliness = modal.querySelector('[data-criterion="friendliness"]').getAttribute('data-rating');
            const safety = modal.querySelector('[data-criterion="safety"]').getAttribute('data-rating');

            if (globalRating === '0') {
                alert('Veuillez sélectionner une note');
                return;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Envoi...';

            try {
                const response = await fetch('/CarShare/api/rating.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        user_id: userId,
                        rating: globalRating,
                        comment: comment,
                        punctuality: punctuality,
                        friendliness: friendliness,
                        safety: safety
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    this.closeModal(modal);
                    this.showNotification('✅ Note envoyée avec succès !', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(result.message || 'Erreur');
                }
            } catch (error) {
                alert('Erreur lors de l\'envoi: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Envoyer la note';
            }
        });
    }

    handleReportSubmit(modal, userId) {
        const form = modal.querySelector('.report-form');
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const reason = form.querySelector('[name="reason"]').value;
            const description = form.querySelector('[name="description"]').value;

            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Envoi...';

            try {
                const response = await fetch('/CarShare/api/report.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        user_id: userId,
                        reason: reason,
                        description: description
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    this.closeModal(modal);
                    this.showNotification('✅ Signalement envoyé. Notre équipe va l\'examiner.', 'success');
                } else {
                    throw new Error(result.message || 'Erreur');
                }
            } catch (error) {
                alert('Erreur lors de l\'envoi: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Envoyer le signalement';
            }
        });
    }

    closeModal(modal) {
        modal.classList.remove('active');
        setTimeout(() => modal.remove(), 300);
    }

    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => notification.classList.add('show'), 10);
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new RatingReportSystem();
});

// Export for manual triggering
window.openRatingModal = (userId, userName) => {
    const system = new RatingReportSystem();
    system.openRatingModal(userId, userName);
};

window.openReportModal = (userId, userName) => {
    const system = new RatingReportSystem();
    system.openReportModal(userId, userName);
};
