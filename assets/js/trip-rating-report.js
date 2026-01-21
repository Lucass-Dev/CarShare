/**
 * Rating & Report System - Spécifique aux trajets
 * Formulaires et notifications pour noter/signaler un trajet
 */

class TripRatingReportSystem {
    constructor() {
        this.init();
    }

    init() {
        this.attachTripRatingHandlers();
        this.attachTripReportHandlers();
    }

    attachTripRatingHandlers() {
        document.querySelectorAll('[data-action="rate-user"][data-carpooling-id]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const carpoolingId = button.getAttribute('data-carpooling-id');
                const userName = button.getAttribute('data-user-name');
                this.openTripRatingModal(carpoolingId, userName);
            });
        });
    }

    attachTripReportHandlers() {
        document.querySelectorAll('[data-action="report-user"][data-carpooling-id]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const carpoolingId = button.getAttribute('data-carpooling-id');
                const userName = button.getAttribute('data-user-name');
                this.openTripReportModal(carpoolingId, userName);
            });
        });
    }

    openTripRatingModal(carpoolingId, userName) {
        const modal = this.createModal(`
            <div class="modal-header">
                <h3>⭐ Noter le conducteur : ${userName}</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form class="rating-form" data-carpooling-id="${carpoolingId}">
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

                <div class="modal-actions">
                    <button type="button" class="btn-secondary modal-cancel">Annuler</button>
                    <button type="submit" class="btn-primary">Envoyer la note</button>
                </div>
            </form>
        `);

        this.initStarRating(modal);
        this.handleTripRatingSubmit(modal, carpoolingId);
    }

    openTripReportModal(carpoolingId, userName) {
        const modal = this.createModal(`
            <div class="modal-header">
                <h3>⚠️ Signaler le conducteur : ${userName}</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form class="report-form" data-carpooling-id="${carpoolingId}">
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

        this.handleTripReportSubmit(modal, carpoolingId);
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

        modal.querySelector('.modal-close')?.addEventListener('click', () => this.closeModal(modal));
        modal.querySelector('.modal-cancel')?.addEventListener('click', () => this.closeModal(modal));
        modal.querySelector('.modal-overlay')?.addEventListener('click', () => this.closeModal(modal));

        return modal;
    }

    initStarRating(modal) {
        modal.querySelectorAll('.star-rating').forEach(container => {
            const stars = container.querySelectorAll('.star');
            
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

    handleTripRatingSubmit(modal, carpoolingId) {
        const form = modal.querySelector('.rating-form');
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const globalRating = modal.querySelector('.star-rating').getAttribute('data-rating');
            const comment = form.querySelector('[name="comment"]').value;

            if (globalRating === '0') {
                this.showBigNotification('⚠️ Veuillez sélectionner une note', 'warning');
                return;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Envoi...';

            try {
                const response = await fetch(apiUrl('rating_trajet.php'), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        carpooling_id: carpoolingId,
                        rating: globalRating,
                        content: comment
                    })
                });

                const result = await response.json();
                
                this.closeModal(modal);
                
                if (result.success) {
                    this.showBigNotification('✅ Note enregistrée avec succès !', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    this.showBigNotification('⚠️ ' + result.message, 'warning');
                }
            } catch (error) {
                this.closeModal(modal);
                this.showBigNotification('❌ Erreur : ' + error.message, 'error');
            }
        });
    }

    handleTripReportSubmit(modal, carpoolingId) {
        const form = modal.querySelector('.report-form');
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const reason = form.querySelector('[name="reason"]').value;
            const description = form.querySelector('[name="description"]').value;

            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Envoi...';

            try {
                const response = await fetch(apiUrl('report_trajet.php'), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        carpooling_id: carpoolingId,
                        reason: reason,
                        description: description
                    })
                });

                const result = await response.json();
                
                this.closeModal(modal);
                
                if (result.success) {
                    this.showBigNotification('✅ Signalement enregistré', 'success');
                } else {
                    this.showBigNotification('⚠️ ' + result.message, 'warning');
                }
            } catch (error) {
                this.closeModal(modal);
                this.showBigNotification('❌ Erreur : ' + error.message, 'error');
            }
        });
    }

    closeModal(modal) {
        modal.classList.remove('active');
        setTimeout(() => modal.remove(), 300);
    }

    showBigNotification(message, type = 'success') {
        // Supprimer les anciennes notifications
        document.querySelectorAll('.big-notification').forEach(n => n.remove());
        
        const colors = {
            success: { bg: '#10b981', border: '#059669' },
            warning: { bg: '#f59e0b', border: '#d97706' },
            error: { bg: '#ef4444', border: '#dc2626' }
        };
        
        const icons = {
            success: '✅',
            warning: '⚠️',
            error: '❌'
        };
        
        const notification = document.createElement('div');
        notification.className = 'big-notification';
        notification.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            z-index: 10000;
            border-left: 5px solid ${colors[type].border};
            min-width: 300px;
            max-width: 500px;
            text-align: center;
            animation: slideInBig 0.3s ease-out;
        `;
        
        notification.innerHTML = `
            <div style="font-size: 48px; margin-bottom: 15px;">${icons[type]}</div>
            <div style="font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 10px;">${message}</div>
            <div style="width: 100%; height: 4px; background: ${colors[type].bg}; border-radius: 2px; margin-top: 20px;"></div>
        `;
        
        // Ajouter le style d'animation si pas déjà présent
        if (!document.getElementById('big-notification-styles')) {
            const style = document.createElement('style');
            style.id = 'big-notification-styles';
            style.textContent = `
                @keyframes slideInBig {
                    from {
                        opacity: 0;
                        transform: translate(-50%, -60%);
                    }
                    to {
                        opacity: 1;
                        transform: translate(-50%, -50%);
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translate(-50%, -40%)';
            notification.style.transition = 'all 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 1000);
    }
}

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', () => {
    new TripRatingReportSystem();
});
