/**
 * Système de notifications moderne - Global
 * Remplace les alert() natifs par des toasts élégants
 */

class NotificationManager {
    constructor() {
        this.container = this.createContainer();
    }
    
    createContainer() {
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'notification-container';
            document.body.appendChild(container);
        }
        return container;
    }
    
    show(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `notification notification--${type}`;
        
        const icon = this.getIcon(type);
        const closeBtn = '<button class="notification__close" aria-label="Fermer">&times;</button>';
        
        notification.innerHTML = `
            <div class="notification__icon">${icon}</div>
            <div class="notification__content">
                <div class="notification__message">${this.escapeHtml(message)}</div>
            </div>
            ${closeBtn}
        `;
        
        this.container.appendChild(notification);
        
        // Animation d'entrée
        setTimeout(() => notification.classList.add('notification--show'), 10);
        
        // Gestion de la fermeture
        const closeButton = notification.querySelector('.notification__close');
        closeButton.addEventListener('click', () => this.hide(notification));
        
        // Auto-fermeture
        if (duration > 0) {
            setTimeout(() => this.hide(notification), duration);
        }
        
        return notification;
    }
    
    hide(notification) {
        notification.classList.remove('notification--show');
        setTimeout(() => notification.remove(), 300);
    }
    
    getIcon(type) {
        const icons = {
            error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
            warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
        };
        return icons[type] || icons.info;
    }
    
    showMultiple(messages, type = 'error') {
        const notification = document.createElement('div');
        notification.className = `notification notification--${type} notification--multi`;
        
        const icon = this.getIcon(type);
        const closeBtn = '<button class="notification__close" aria-label="Fermer">&times;</button>';
        
        const messagesList = messages.map(msg => `<li>${this.escapeHtml(msg)}</li>`).join('');
        
        notification.innerHTML = `
            <div class="notification__icon">${icon}</div>
            <div class="notification__content">
                <div class="notification__title">Veuillez corriger les erreurs suivantes :</div>
                <ul class="notification__list">${messagesList}</ul>
            </div>
            ${closeBtn}
        `;
        
        this.container.appendChild(notification);
        
        setTimeout(() => notification.classList.add('notification--show'), 10);
        
        const closeButton = notification.querySelector('.notification__close');
        closeButton.addEventListener('click', () => this.hide(notification));
        
        setTimeout(() => this.hide(notification), 8000);
        
        return notification;
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Instance globale
window.notificationManager = new NotificationManager();

// Alias pour remplacer alert()
window.showNotification = (message, type = 'info', duration = 5000) => {
    window.notificationManager.show(message, type, duration);
};
