/**
 * Validation Message Hub
 * Unifie les messages d'erreur des formulaires et remplace les bulles natives
 * par des toasts + messages inline cohérents sur tout le site.
 */

class ValidationMessageHub {
    constructor() {
        this.lastMessage = '';
        this.lastMessageTime = 0;
        this.handleInvalid = this.handleInvalid.bind(this);
        this.handleInput = this.handleInput.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleThreat = this.handleThreat.bind(this);
        this.registerListeners();
    }

    registerListeners() {
        document.addEventListener('invalid', this.handleInvalid, true);
        document.addEventListener('input', this.handleInput, true);
        document.addEventListener('change', this.handleInput, true);
        document.addEventListener('submit', this.handleSubmit, true);
        document.addEventListener('security:threat-detected', this.handleThreat);
    }

    handleInvalid(event) {
        const field = event.target;
        if (!this.shouldHandle(field)) {
            return;
        }

        event.preventDefault();
        const message = this.getMessage(field);
        this.showNotification(message, 'error');
        this.showInlineMessage(field, message);
        this.focusField(field);
    }

    handleInput(event) {
        const field = event.target;
        if (!field.classList || !field.classList.contains('input-global-error')) {
            return;
        }

        if (field.validity && field.validity.valid) {
            this.clearField(field);
        }
    }

    handleSubmit(event) {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        form.querySelectorAll('.input-global-error').forEach(field => this.clearField(field));
    }

    handleThreat(event) {
        const detail = event.detail || {};
        const message = detail.message || 'Comportement suspect détecté.';
        const severity = detail.severity || 'warning';
        this.showNotification(message, severity);
    }

    shouldHandle(field) {
        if (!(field instanceof HTMLElement)) {
            return false;
        }

        if (!field.validity || field.type === 'hidden' || field.disabled) {
            return false;
        }

        if (field.dataset.skipCustomValidation === 'true') {
            return false;
        }

        const form = field.closest('form');
        if (form && form.classList.contains('custom-validation')) {
            return false;
        }

        return true;
    }

    showInlineMessage(field, message) {
        field.classList.add('input-global-error');
        const wrapper = this.getWrapper(field);
        if (!wrapper) {
            return;
        }

        let bubble = wrapper.querySelector('.field-error-global');
        if (!bubble) {
            bubble = document.createElement('div');
            bubble.className = 'field-error-global';
            bubble.setAttribute('role', 'alert');
            wrapper.appendChild(bubble);
        }

        bubble.textContent = message;
        bubble.classList.add('field-error-global--visible');
    }

    clearField(field) {
        field.classList.remove('input-global-error');
        const wrapper = this.getWrapper(field);
        if (!wrapper) {
            return;
        }

        const bubble = wrapper.querySelector('.field-error-global');
        if (bubble) {
            bubble.classList.remove('field-error-global--visible');
            bubble.textContent = '';
        }
    }

    getWrapper(field) {
        return field.closest('.form-field, .form__group, .input-wrapper, .form-group, .field-group') || field.parentElement;
    }

    getMessage(field) {
        if (field.dataset.errorMessage) {
            return field.dataset.errorMessage;
        }

        const label = this.getLabel(field) || 'ce champ';
        const validity = field.validity;

        if (validity.valueMissing) {
            return `Le champ ${label} est obligatoire.`;
        }

        if (validity.patternMismatch) {
            return field.getAttribute('title') || `Le champ ${label} ne respecte pas le format attendu.`;
        }

        if (validity.typeMismatch) {
            return `Le format saisi pour ${label} n'est pas valide.`;
        }

        if (validity.tooShort && field.getAttribute('minlength')) {
            return `${label} doit contenir au moins ${field.getAttribute('minlength')} caractères.`;
        }

        if (validity.tooLong && field.getAttribute('maxlength')) {
            return `${label} ne peut pas dépasser ${field.getAttribute('maxlength')} caractères.`;
        }

        if (validity.rangeUnderflow && field.getAttribute('min') !== null) {
            return `${label} doit être supérieur ou égal à ${field.getAttribute('min')}.`;
        }

        if (validity.rangeOverflow && field.getAttribute('max') !== null) {
            return `${label} doit être inférieur ou égal à ${field.getAttribute('max')}.`;
        }

        if (validity.stepMismatch) {
            return `${label} doit respecter le pas défini.`;
        }

        if (validity.badInput) {
            return `La valeur saisie pour ${label} n'est pas valide.`;
        }

        return field.getAttribute('title') || field.validationMessage || `Valeur invalide pour ${label}.`;
    }

    getLabel(field) {
        if (field.dataset.label) {
            return field.dataset.label;
        }

        const ariaLabel = field.getAttribute('aria-label');
        if (ariaLabel) {
            return ariaLabel;
        }

        if (field.id) {
            const selector = `label[for="${this.escapeCss(field.id)}"]`;
            const explicitLabel = document.querySelector(selector);
            if (explicitLabel) {
                return explicitLabel.textContent.trim();
            }
        }

        const parentLabel = field.closest('label');
        if (parentLabel) {
            return parentLabel.textContent.trim();
        }

        if (field.placeholder) {
            return field.placeholder;
        }

        if (field.name) {
            return field.name;
        }

        return '';
    }

    escapeCss(value) {
        if (window.CSS && typeof window.CSS.escape === 'function') {
            return window.CSS.escape(value);
        }
            return value.replace(/([ !"#$%&'()*+,./:;<=>?@[\\\]^`{|}~])/g, '\\$1');
    }

    showNotification(message, type = 'error') {
        const now = Date.now();
        if (this.lastMessage === message && now - this.lastMessageTime < 1200) {
            return;
        }

        this.lastMessage = message;
        this.lastMessageTime = now;

        if (window.notificationManager && typeof window.notificationManager.show === 'function') {
            window.notificationManager.show(message, type, 6000);
        } else if (typeof window.showNotification === 'function') {
            window.showNotification(message, type, 6000);
        } else {
            console.warn(message);
        }
    }

    focusField(field) {
        if (typeof field.focus === 'function') {
            field.focus();
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.validationMessageHub = new ValidationMessageHub();
});
