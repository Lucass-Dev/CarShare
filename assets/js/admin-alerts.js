/**
 * Système d'alertes personnalisées pour l'espace administrateur
 */

class AdminAlert {
    constructor() {
        this.createContainer();
    }

    createContainer() {
        if (!document.getElementById('admin-alert-container')) {
            const container = document.createElement('div');
            container.id = 'admin-alert-container';
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                max-width: 400px;
            `;
            document.body.appendChild(container);
        }
    }

    show(message, type = 'info', duration = 4000) {
        const container = document.getElementById('admin-alert-container');
        
        const alert = document.createElement('div');
        alert.className = `admin-alert admin-alert-${type}`;
        
        const colors = {
            success: { bg: '#d1fae5', border: '#10b981', icon: '✓', color: '#065f46' },
            error: { bg: '#fee2e2', border: '#ef4444', icon: '✕', color: '#991b1b' },
            warning: { bg: '#fef3c7', border: '#f59e0b', icon: '⚠', color: '#92400e' },
            info: { bg: '#dbeafe', border: '#3b82f6', icon: 'ℹ', color: '#1e40af' }
        };
        
        const style = colors[type] || colors.info;
        
        alert.innerHTML = `
            <div style="
                background: ${style.bg};
                color: ${style.color};
                padding: 1rem 1.25rem;
                border-radius: 8px;
                border-left: 4px solid ${style.border};
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                gap: 12px;
                animation: slideInRight 0.3s ease-out;
                position: relative;
            ">
                <span style="
                    font-size: 1.5rem;
                    font-weight: bold;
                    color: ${style.border};
                ">${style.icon}</span>
                <span style="flex: 1; font-size: 0.95rem;">${message}</span>
                <button onclick="this.closest('[class*=admin-alert]').remove()" style="
                    background: transparent;
                    border: none;
                    color: ${style.color};
                    font-size: 1.5rem;
                    cursor: pointer;
                    padding: 0;
                    width: 24px;
                    height: 24px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    opacity: 0.6;
                    transition: opacity 0.2s;
                " onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'">×</button>
            </div>
        `;
        
        container.appendChild(alert);
        
        // Auto-remove after duration
        if (duration > 0) {
            setTimeout(() => {
                alert.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => alert.remove(), 300);
            }, duration);
        }
    }

    success(message, duration = 4000) {
        this.show(message, 'success', duration);
    }

    error(message, duration = 5000) {
        this.show(message, 'error', duration);
    }

    warning(message, duration = 4500) {
        this.show(message, 'warning', duration);
    }

    info(message, duration = 4000) {
        this.show(message, 'info', duration);
    }

    confirm(message, onConfirm, onCancel = null) {
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10001;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.2s ease-out;
        `;
        
        overlay.innerHTML = `
            <div style="
                background: white;
                border-radius: 12px;
                padding: 2rem;
                max-width: 450px;
                width: 90%;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                animation: scaleIn 0.3s ease-out;
            ">
                <div style="
                    font-size: 3rem;
                    text-align: center;
                    color: #f59e0b;
                    margin-bottom: 1rem;
                ">⚠</div>
                <h3 style="
                    margin: 0 0 1rem 0;
                    color: #333;
                    font-size: 1.25rem;
                    text-align: center;
                ">Confirmation</h3>
                <p style="
                    color: #666;
                    margin: 0 0 1.5rem 0;
                    text-align: center;
                    line-height: 1.5;
                ">${message}</p>
                <div style="
                    display: flex;
                    gap: 1rem;
                    justify-content: center;
                ">
                    <button id="admin-confirm-cancel" style="
                        padding: 0.75rem 1.5rem;
                        border: 2px solid #d1d5db;
                        background: white;
                        color: #6b7280;
                        border-radius: 6px;
                        cursor: pointer;
                        font-size: 1rem;
                        font-weight: 500;
                        transition: all 0.2s;
                    " onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='white'">Annuler</button>
                    <button id="admin-confirm-ok" style="
                        padding: 0.75rem 1.5rem;
                        border: none;
                        background: #3b82f6;
                        color: white;
                        border-radius: 6px;
                        cursor: pointer;
                        font-size: 1rem;
                        font-weight: 500;
                        transition: all 0.2s;
                    " onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">Confirmer</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(overlay);
        
        const removeOverlay = () => {
            overlay.style.animation = 'fadeOut 0.2s ease-out';
            setTimeout(() => overlay.remove(), 200);
        };
        
        document.getElementById('admin-confirm-ok').onclick = () => {
            removeOverlay();
            if (onConfirm) onConfirm();
        };
        
        document.getElementById('admin-confirm-cancel').onclick = () => {
            removeOverlay();
            if (onCancel) onCancel();
        };
        
        overlay.onclick = (e) => {
            if (e.target === overlay) {
                removeOverlay();
                if (onCancel) onCancel();
            }
        };
    }
}

// Ajouter les animations CSS
if (!document.getElementById('admin-alert-animations')) {
    const style = document.createElement('style');
    style.id = 'admin-alert-animations';
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
}

// Instance globale
window.adminAlert = new AdminAlert();

// Surcharger alert() pour l'admin
window.originalAlert = window.alert;
window.alert = function(message) {
    if (window.location.href.includes('admin') || document.body.classList.contains('admin-page')) {
        window.adminAlert.info(message);
    } else {
        window.originalAlert(message);
    }
};

// Surcharger confirm() pour l'admin
window.originalConfirm = window.confirm;
window.confirm = function(message) {
    if (window.location.href.includes('admin') || document.body.classList.contains('admin-page')) {
        return new Promise((resolve) => {
            window.adminAlert.confirm(message, () => resolve(true), () => resolve(false));
        });
    } else {
        return window.originalConfirm(message);
    }
};
