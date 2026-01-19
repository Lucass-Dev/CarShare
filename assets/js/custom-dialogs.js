/**
 * Custom Dialog System - Modern Modal Dialogs
 * Replaces native browser confirm(), alert(), prompt() with styled modals
 */

class CustomDialog {
    constructor() {
        this.activeModal = null;
    }

    /**
     * Custom Confirm Dialog
     * @param {string} message - The message to display
     * @param {object} options - Configuration options
     * @returns {Promise<boolean>} - Resolves to true if confirmed, false if cancelled
     */
    confirm(message, options = {}) {
        return new Promise((resolve) => {
            const config = {
                title: options.title || 'Confirmation',
                confirmText: options.confirmText || 'OK',
                cancelText: options.cancelText || 'Annuler',
                confirmClass: options.confirmClass || 'btn-confirm',
                icon: options.icon || '⚠️',
                ...options
            };

            const modal = this.createModal({
                title: config.title,
                message: message,
                icon: config.icon,
                buttons: [
                    {
                        text: config.cancelText,
                        class: 'btn-cancel',
                        action: () => {
                            this.closeModal(modal);
                            resolve(false);
                        }
                    },
                    {
                        text: config.confirmText,
                        class: config.confirmClass,
                        action: () => {
                            this.closeModal(modal);
                            resolve(true);
                        }
                    }
                ]
            });

            this.showModal(modal);
        });
    }

    /**
     * Custom Alert Dialog
     * @param {string} message - The message to display
     * @param {object} options - Configuration options
     * @returns {Promise<void>}
     */
    alert(message, options = {}) {
        return new Promise((resolve) => {
            const config = {
                title: options.title || 'Information',
                buttonText: options.buttonText || 'OK',
                icon: options.icon || 'ℹ️',
                type: options.type || 'info', // info, success, warning, error
                ...options
            };

            const modal = this.createModal({
                title: config.title,
                message: message,
                icon: config.icon,
                type: config.type,
                buttons: [
                    {
                        text: config.buttonText,
                        class: 'btn-primary',
                        action: () => {
                            this.closeModal(modal);
                            resolve();
                        }
                    }
                ]
            });

            this.showModal(modal);
        });
    }

    /**
     * Custom Prompt Dialog
     * @param {string} message - The message to display
     * @param {object} options - Configuration options
     * @returns {Promise<string|null>} - Resolves to input value or null if cancelled
     */
    prompt(message, options = {}) {
        return new Promise((resolve) => {
            const config = {
                title: options.title || 'Saisie',
                defaultValue: options.defaultValue || '',
                placeholder: options.placeholder || '',
                confirmText: options.confirmText || 'OK',
                cancelText: options.cancelText || 'Annuler',
                inputType: options.inputType || 'text',
                ...options
            };

            const inputId = 'custom-prompt-input-' + Date.now();
            const messageWithInput = `
                <p>${this.escapeHtml(message)}</p>
                <input 
                    type="${config.inputType}" 
                    id="${inputId}"
                    class="custom-prompt-input" 
                    placeholder="${this.escapeHtml(config.placeholder)}"
                    value="${this.escapeHtml(config.defaultValue)}"
                />
            `;

            const modal = this.createModal({
                title: config.title,
                message: messageWithInput,
                icon: '✏️',
                isPrompt: true,
                buttons: [
                    {
                        text: config.cancelText,
                        class: 'btn-cancel',
                        action: () => {
                            this.closeModal(modal);
                            resolve(null);
                        }
                    },
                    {
                        text: config.confirmText,
                        class: 'btn-primary',
                        action: () => {
                            const input = modal.querySelector(`#${inputId}`);
                            const value = input ? input.value : null;
                            this.closeModal(modal);
                            resolve(value);
                        }
                    }
                ]
            });

            this.showModal(modal);

            // Focus on input after modal is shown
            setTimeout(() => {
                const input = modal.querySelector(`#${inputId}`);
                if (input) {
                    input.focus();
                    input.select();
                }
            }, 100);
        });
    }

    /**
     * Create modal HTML structure
     */
    createModal(config) {
        const modal = document.createElement('div');
        modal.className = 'custom-dialog-modal';
        
        const typeClass = config.type ? `custom-dialog-modal--${config.type}` : '';
        if (typeClass) modal.classList.add(typeClass);

        const buttonsHtml = config.buttons.map(btn => `
            <button class="custom-dialog-btn ${btn.class}" data-action="${btn.text}">
                ${btn.text}
            </button>
        `).join('');

        modal.innerHTML = `
            <div class="custom-dialog-backdrop"></div>
            <div class="custom-dialog-container">
                <div class="custom-dialog-content">
                    <div class="custom-dialog-icon">${config.icon}</div>
                    <h3 class="custom-dialog-title">${this.escapeHtml(config.title)}</h3>
                    <div class="custom-dialog-message">${config.isPrompt ? config.message : this.formatMessage(config.message)}</div>
                </div>
                <div class="custom-dialog-actions">
                    ${buttonsHtml}
                </div>
            </div>
        `;

        // Attach event listeners
        config.buttons.forEach((btn, index) => {
            const btnElement = modal.querySelectorAll('.custom-dialog-btn')[index];
            btnElement.addEventListener('click', btn.action);
        });

        // Close on backdrop click
        const backdrop = modal.querySelector('.custom-dialog-backdrop');
        backdrop.addEventListener('click', () => {
            const cancelBtn = config.buttons.find(b => b.class.includes('cancel'));
            if (cancelBtn) {
                cancelBtn.action();
            }
        });

        // Handle ESC key
        const escHandler = (e) => {
            if (e.key === 'Escape' && this.activeModal === modal) {
                const cancelBtn = config.buttons.find(b => b.class.includes('cancel'));
                if (cancelBtn) {
                    cancelBtn.action();
                } else {
                    config.buttons[config.buttons.length - 1].action();
                }
                document.removeEventListener('keydown', escHandler);
            }
        };
        document.addEventListener('keydown', escHandler);

        return modal;
    }

    /**
     * Show modal with animation
     */
    showModal(modal) {
        document.body.appendChild(modal);
        this.activeModal = modal;
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        
        // Trigger animation
        requestAnimationFrame(() => {
            modal.classList.add('custom-dialog-modal--active');
        });
    }

    /**
     * Close modal with animation
     */
    closeModal(modal) {
        modal.classList.remove('custom-dialog-modal--active');
        
        setTimeout(() => {
            modal.remove();
            this.activeModal = null;
            document.body.style.overflow = '';
        }, 300);
    }

    /**
     * Format message with proper line breaks
     */
    formatMessage(message) {
        return this.escapeHtml(message)
            .replace(/\n/g, '<br>')
            .replace(/- /g, '<br>• ');
    }

    /**
     * Escape HTML to prevent XSS
     */
    escapeHtml(text) {
        if (typeof text !== 'string') return text;
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Global instance
window.customDialog = new CustomDialog();

// Override native functions with custom dialogs
window.customConfirm = (message, options) => window.customDialog.confirm(message, options);
window.customAlert = (message, options) => window.customDialog.alert(message, options);
window.customPrompt = (message, options) => window.customDialog.prompt(message, options);

// Helper for common dialogs
window.showSuccess = (message, title = 'Succès') => {
    return window.customDialog.alert(message, {
        title: title,
        icon: '✅',
        type: 'success'
    });
};

window.showError = (message, title = 'Erreur') => {
    return window.customDialog.alert(message, {
        title: title,
        icon: '❌',
        type: 'error'
    });
};

window.showWarning = (message, title = 'Attention') => {
    return window.customDialog.alert(message, {
        title: title,
        icon: '⚠️',
        type: 'warning'
    });
};

window.showInfo = (message, title = 'Information') => {
    return window.customDialog.alert(message, {
        title: title,
        icon: 'ℹ️',
        type: 'info'
    });
};

window.confirmDelete = (itemName = 'cet élément') => {
    return window.customDialog.confirm(
        `Êtes-vous sûr de vouloir supprimer ${itemName} ?\n\nCette action est irréversible.`,
        {
            title: 'Confirmer la suppression',
            icon: '🗑️',
            confirmText: 'Supprimer',
            confirmClass: 'btn-danger',
            cancelText: 'Annuler'
        }
    );
};

console.log('✓ Custom Dialog System loaded');
