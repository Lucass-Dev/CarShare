/**
 * Enhanced Trip Form Validation & Security
 * Protection contre: XSS, SQL injection, JavaScript injection, hexadécimal, binaire, etc.
 */

// Configuration de sécurité
const SecurityConfig = {
    // Patterns de détection d'attaques
    patterns: {
        sqlInjection: /(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE|UNION|SCRIPT)\b|--|;|\/\*|\*\/|xp_|sp_)/gi,
        xss: /<script|<iframe|<object|<embed|javascript:|onerror|onload|onclick|onmouseover/gi,
        hexEncoding: /(\\x[0-9a-fA-F]{2}|%[0-9a-fA-F]{2}){3,}/g,
        binaryEncoding: /[01]{32,}/g,
        htmlEntities: /&(#\d+|#x[0-9a-fA-F]+|[a-zA-Z]+);/g,
        controlChars: /[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/g,
        unicodeExploits: /[\\u][0-9a-fA-F]{4}/gi,
        specialChars: /[<>{}\[\]\\]/g
    },
    
    // Longueurs maximales
    maxLengths: {
        city: 100,
        street: 150,
        streetNumber: 5
    }
};

// Classe pour gérer les notifications modernes
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
    
    show(message, type = 'error', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `notification notification--${type}`;
        
        const icon = this.getIcon(type);
        const closeBtn = '<button class="notification__close" aria-label="Fermer">&times;</button>';
        
        notification.innerHTML = `
            <div class="notification__icon">${icon}</div>
            <div class="notification__content">
                <div class="notification__message">${message}</div>
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
        
        const messagesList = messages.map(msg => `<li>${msg}</li>`).join('');
        
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
}

// Classe de validation sécurisée
class SecureValidator {
    static sanitizeInput(value) {
        if (typeof value !== 'string') return '';
        return value.trim();
    }
    
    static detectSecurityThreats(value) {
        const threats = [];
        
        if (SecurityConfig.patterns.sqlInjection.test(value)) {
            threats.push('sql');
        }
        if (SecurityConfig.patterns.xss.test(value)) {
            threats.push('xss');
        }
        if (SecurityConfig.patterns.hexEncoding.test(value)) {
            threats.push('hex');
        }
        if (SecurityConfig.patterns.binaryEncoding.test(value)) {
            threats.push('binary');
        }
        if (SecurityConfig.patterns.unicodeExploits.test(value)) {
            threats.push('unicode');
        }
        if (SecurityConfig.patterns.controlChars.test(value)) {
            threats.push('control');
        }
        
        return threats;
    }
    
    static validateCity(value, fieldName = 'ville') {
        const sanitized = this.sanitizeInput(value);
        const errors = [];
        
        if (!sanitized) {
            errors.push(`La ${fieldName} est obligatoire`);
            return { valid: false, errors, value: sanitized };
        }
        
        const threats = this.detectSecurityThreats(sanitized);
        if (threats.length > 0) {
            errors.push(`La ${fieldName} contient des caractères interdits ou dangereux`);
            return { valid: false, errors, value: sanitized };
        }
        
        if (sanitized.length > SecurityConfig.maxLengths.city) {
            errors.push(`La ${fieldName} est trop longue (maximum ${SecurityConfig.maxLengths.city} caractères)`);
        }
        
        // Validation: lettres, espaces, tirets, apostrophes uniquement
        if (!/^[a-zA-ZÀ-ÿ\s\-']+$/.test(sanitized)) {
            errors.push(`La ${fieldName} ne doit contenir que des lettres, espaces, tirets et apostrophes`);
        }
        
        return { valid: errors.length === 0, errors, value: sanitized };
    }
    
    static validateStreet(value, fieldName = 'rue') {
        const sanitized = this.sanitizeInput(value);
        
        // Rue optionnelle
        if (!sanitized) {
            return { valid: true, errors: [], value: sanitized };
        }
        
        const errors = [];
        const threats = this.detectSecurityThreats(sanitized);
        
        if (threats.length > 0) {
            errors.push(`La ${fieldName} contient des caractères interdits ou dangereux`);
            return { valid: false, errors, value: sanitized };
        }
        
        if (sanitized.length > SecurityConfig.maxLengths.street) {
            errors.push(`La ${fieldName} est trop longue (maximum ${SecurityConfig.maxLengths.street} caractères)`);
        }
        
        // Validation plus permissive pour les rues
        if (!/^[a-zA-Z0-9À-ÿ\s\-',./]+$/.test(sanitized)) {
            errors.push(`La ${fieldName} contient des caractères non autorisés`);
        }
        
        return { valid: errors.length === 0, errors, value: sanitized };
    }
    
    static validateStreetNumber(value, fieldName = 'numéro de voie') {
        const sanitized = this.sanitizeInput(value);
        
        // Numéro optionnel
        if (!sanitized) {
            return { valid: true, errors: [], value: sanitized };
        }
        
        const errors = [];
        
        // Vérifier d'abord les menaces de sécurité
        const threats = this.detectSecurityThreats(sanitized);
        if (threats.length > 0) {
            errors.push(`Le ${fieldName} contient des caractères interdits ou dangereux`);
            return { valid: false, errors, value: sanitized };
        }
        
        // Validation : chiffres avec suffixes optionnels (bis, ter, A, B, etc.) ou tiret/slash
        // Formats acceptés: 123, 123bis, 12B, 12-14, 12/14
        if (!/^[\d]+[\s]*(bis|ter|quater|[A-Za-z])?[\s]*(-[\d]+)?$/.test(sanitized)) {
            errors.push(`Le ${fieldName} contient des caractères interdits (+ ou autres symboles non autorisés)`);
            return { valid: false, errors, value: sanitized };
        }
        
        // Extraire le premier nombre pour validation de plage
        const firstNumber = parseInt(sanitized.match(/^\d+/)[0], 10);
        
        if (isNaN(firstNumber) || firstNumber < 0 || firstNumber > 99999) {
            errors.push(`Le ${fieldName} doit être entre 0 et 99999`);
        }
        
        if (sanitized.length > 15) {
            errors.push(`Le ${fieldName} est trop long`);
        }
        
        return { valid: errors.length === 0, errors, value: sanitized };
    }
    
    static validateDate(value) {
        const errors = [];
        
        if (!value) {
            errors.push('La date est obligatoire');
            return { valid: false, errors, value };
        }
        
        const selectedDate = new Date(value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (isNaN(selectedDate.getTime())) {
            errors.push('Format de date invalide');
            return { valid: false, errors, value };
        }
        
        if (selectedDate < today) {
            errors.push('La date doit être aujourd\'hui ou dans le futur');
        }
        
        // Vérifier que la date n'est pas trop loin dans le futur (1 an max)
        const maxDate = new Date();
        maxDate.setFullYear(maxDate.getFullYear() + 1);
        
        if (selectedDate > maxDate) {
            errors.push('La date ne peut pas dépasser un an dans le futur');
        }
        
        return { valid: errors.length === 0, errors, value };
    }
    
    static validateTime(value) {
        // Heure optionnelle
        if (!value) {
            return { valid: true, errors: [], value };
        }
        
        const errors = [];
        const timeRegex = /^([01]\d|2[0-3]):([0-5]\d)$/;
        
        if (!timeRegex.test(value)) {
            errors.push('Format d\'heure invalide (HH:MM attendu)');
        }
        
        return { valid: errors.length === 0, errors, value };
    }
    
    static validatePlaces(value) {
        const errors = [];
        
        const number = parseInt(value, 10);
        
        if (isNaN(number)) {
            errors.push('Le nombre de places doit être un nombre');
            return { valid: false, errors, value };
        }
        
        if (number < 1) {
            errors.push('Le nombre de places doit être au minimum de 1');
        } else if (number > 10) {
            errors.push('Le nombre de places ne peut pas dépasser 10');
        }
        
        return { valid: errors.length === 0, errors, value };
    }
    
    static validatePrice(value) {
        // Prix optionnel
        if (!value || value.trim() === '') {
            return { valid: true, errors: [], value };
        }
        
        const errors = [];
        
        // Vérifier si c'est un nombre
        const price = parseFloat(value);
        
        if (isNaN(price)) {
            errors.push('Le prix doit être un nombre valide');
            return { valid: false, errors, value };
        }
        
        if (price < 0) {
            errors.push('Le prix ne peut pas être négatif');
        } else if (price > 9999.99) {
            errors.push('Le prix ne peut pas dépasser 9999.99 €');
        }
        
        // Vérifier le format (max 2 décimales)
        if (!/^\d+(\.\d{1,2})?$/.test(value)) {
            errors.push('Le prix ne peut avoir que 2 décimales maximum');
        }
        
        return { valid: errors.length === 0, errors, value };
    }
}

// Classe pour gérer le style des champs
class FieldStyler {
    static markAsInvalid(field, message = '') {
        // S'assurer que le champ n'est jamais marqué comme valide ET invalide
        field.classList.remove('field--valid');
        field.classList.add('field--invalid');
        
        // Ajouter un message d'erreur inline si fourni
        this.removeErrorMessage(field);
        
        if (message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field__error';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        }
    }
    
    static markAsValid(field) {
        // D'abord supprimer tout message d'erreur
        this.removeErrorMessage(field);
        // Puis marquer comme valide seulement s'il n'y a plus d'erreur
        field.classList.remove('field--invalid');
        field.classList.add('field--valid');
    }
    
    static markAsNeutral(field) {
        field.classList.remove('field--invalid', 'field--valid');
        this.removeErrorMessage(field);
    }
    
    static removeErrorMessage(field) {
        const parent = field.parentNode;
        const existingError = parent.querySelector('.field__error');
        if (existingError) {
            existingError.remove();
        }
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.trip-form');
    
    if (!form) return;
    
    // Empêcher form-enhancements.js de gérer ce formulaire
    form.classList.add('custom-validation');
    
    const notificationManager = new NotificationManager();
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton ? submitButton.textContent : 'Publier';
    
    // Récupération des champs
    const fields = {
        depCity: document.getElementById('dep-city'),
        arrCity: document.getElementById('arr-city'),
        depStreet: document.getElementById('dep-street'),
        arrStreet: document.getElementById('arr-street'),
        depNum: document.getElementById('dep-num'),
        arrNum: document.getElementById('arr-num'),
        date: document.getElementById('date'),
        time: document.getElementById('time'),
        places: document.getElementById('places'),
        price: document.getElementById('price')
    };
    
    // Validation en temps réel pour chaque champ
    setupRealtimeValidation(fields, notificationManager);
    
    // Validation à la soumission
    form.addEventListener('submit', function(e) {
        const allErrors = [];
        let isValid = true;
        
        // Validation de tous les champs
        const validations = {
            depCity: SecureValidator.validateCity(fields.depCity.value, 'ville de départ'),
            arrCity: SecureValidator.validateCity(fields.arrCity.value, 'ville d\'arrivée'),
            depStreet: SecureValidator.validateStreet(fields.depStreet.value, 'rue de départ'),
            arrStreet: SecureValidator.validateStreet(fields.arrStreet.value, 'rue d\'arrivée'),
            depNum: SecureValidator.validateStreetNumber(fields.depNum.value, 'numéro de départ'),
            arrNum: SecureValidator.validateStreetNumber(fields.arrNum.value, 'numéro d\'arrivée'),
            date: SecureValidator.validateDate(fields.date.value),
            time: SecureValidator.validateTime(fields.time.value),
            places: SecureValidator.validatePlaces(fields.places.value),
            price: SecureValidator.validatePrice(fields.price.value)
        };
        
        // Vérifier que les villes sont différentes
        if (validations.depCity.valid && validations.arrCity.valid) {
            if (fields.depCity.value.trim().toLowerCase() === fields.arrCity.value.trim().toLowerCase()) {
                allErrors.push('Les villes de départ et d\'arrivée doivent être différentes');
                FieldStyler.markAsInvalid(fields.depCity);
                FieldStyler.markAsInvalid(fields.arrCity);
                isValid = false;
            }
        }
        
        // Collecter toutes les erreurs et appliquer les styles
        Object.keys(validations).forEach(fieldKey => {
            const validation = validations[fieldKey];
            const field = fields[fieldKey];
            
            if (!validation.valid) {
                isValid = false;
                FieldStyler.markAsInvalid(field);
                allErrors.push(...validation.errors);
            } else {
                FieldStyler.markAsValid(field);
            }
        });
        
        // Affichage des erreurs ou soumission
        if (!isValid) {
            e.preventDefault();            e.stopImmediatePropagation();
            
            // Restaurer le bouton immédiatement
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.classList.remove('loading');
                submitButton.textContent = originalButtonText;
                // Retirer le spinner si présent
                const spinner = submitButton.querySelector('.spinner');
                if (spinner) spinner.remove();
            }
                        notificationManager.showMultiple(allErrors, 'error');
            
            // Scroll vers le premier champ invalide
            const firstInvalidField = form.querySelector('.field--invalid');
            if (firstInvalidField) {
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalidField.focus();
            }
        } else {
            // Tout est valide, laisser le formulaire se soumettre normalement
            notificationManager.show('Vérification en cours...', 'info', 1000);
            // Ne pas appeler e.preventDefault() ni form.submit() 
            // Le formulaire se soumettra naturellement
        }
    });
});

function setupRealtimeValidation(fields, notificationManager) {
    // Ville de départ
    fields.depCity.addEventListener('input', function() {
        if (this.value.trim()) {
            const result = SecureValidator.validateCity(this.value, 'ville de départ');
            if (result.valid) {
                FieldStyler.markAsValid(this);
            }
        } else {
            FieldStyler.markAsNeutral(this);
        }
    });
    
    fields.depCity.addEventListener('blur', function() {
        const result = SecureValidator.validateCity(this.value, 'ville de départ');
        if (!result.valid && this.value.trim()) {
            FieldStyler.markAsInvalid(this, result.errors[0]);
        }
    });
    
    // Ville d'arrivée
    fields.arrCity.addEventListener('input', function() {
        if (this.value.trim()) {
            const result = SecureValidator.validateCity(this.value, 'ville d\'arrivée');
            if (result.valid) {
                FieldStyler.markAsValid(this);
            }
        } else {
            FieldStyler.markAsNeutral(this);
        }
    });
    
    fields.arrCity.addEventListener('blur', function() {
        const result = SecureValidator.validateCity(this.value, 'ville d\'arrivée');
        if (!result.valid && this.value.trim()) {
            FieldStyler.markAsInvalid(this, result.errors[0]);
        }
        
        // Vérifier si les deux villes sont identiques
        if (fields.depCity.value.trim() && this.value.trim() &&
            fields.depCity.value.trim().toLowerCase() === this.value.trim().toLowerCase()) {
            FieldStyler.markAsInvalid(this, 'Les villes doivent être différentes');
            FieldStyler.markAsInvalid(fields.depCity);
        }
    });
    
    // Rues
    fields.depStreet.addEventListener('input', function() {
        if (this.value.trim()) {
            const result = SecureValidator.validateStreet(this.value, 'rue de départ');
            if (result.valid) {
                FieldStyler.markAsValid(this);
            } else {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        } else {
            FieldStyler.markAsNeutral(this);
        }
    });
    
    fields.depStreet.addEventListener('blur', function() {
        if (this.value.trim()) {
            const result = SecureValidator.validateStreet(this.value, 'rue de départ');
            if (!result.valid) {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        }
    });
    
    fields.arrStreet.addEventListener('input', function() {
        if (this.value.trim()) {
            const result = SecureValidator.validateStreet(this.value, 'rue d\'arrivée');
            if (result.valid) {
                FieldStyler.markAsValid(this);
            } else {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        } else {
            FieldStyler.markAsNeutral(this);
        }
    });
    
    fields.arrStreet.addEventListener('blur', function() {
        if (this.value.trim()) {
            const result = SecureValidator.validateStreet(this.value, 'rue d\'arrivée');
            if (!result.valid) {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        }
    });
    
    // Numéros de voie
    fields.depNum.addEventListener('input', function() {
        if (this.value.trim()) {
            const result = SecureValidator.validateStreetNumber(this.value, 'numéro de départ');
            if (result.valid) {
                FieldStyler.markAsValid(this);
            } else {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        } else {
            FieldStyler.markAsNeutral(this);
        }
    });
    
    fields.depNum.addEventListener('blur', function() {
        if (this.value.trim()) {
            const result = SecureValidator.validateStreetNumber(this.value, 'numéro de départ');
            if (!result.valid) {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        }
    });
    
    fields.arrNum.addEventListener('input', function() {
        if (this.value.trim()) {
            const result = SecureValidator.validateStreetNumber(this.value, 'numéro d\'arrivée');
            if (result.valid) {
                FieldStyler.markAsValid(this);
            } else {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        } else {
            FieldStyler.markAsNeutral(this);
        }
    });
    
    fields.arrNum.addEventListener('blur', function() {
        if (this.value.trim()) {
            const result = SecureValidator.validateStreetNumber(this.value, 'numéro d\'arrivée');
            if (!result.valid) {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        }
    });
    
    // Date
    fields.date.addEventListener('change', function() {
        const result = SecureValidator.validateDate(this.value);
        if (!result.valid) {
            FieldStyler.markAsInvalid(this, result.errors[0]);
        } else {
            FieldStyler.markAsValid(this);
        }
    });
    
    // Heure
    fields.time.addEventListener('input', function() {
        if (this.value) {
            const result = SecureValidator.validateTime(this.value);
            if (!result.valid) {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            } else {
                FieldStyler.markAsValid(this);
            }
        } else {
            FieldStyler.markAsNeutral(this);
        }
    });
    
    fields.time.addEventListener('change', function() {
        if (this.value) {
            const result = SecureValidator.validateTime(this.value);
            if (!result.valid) {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            } else {
                FieldStyler.markAsValid(this);
            }
        }
    });
    
    // Places
    fields.places.addEventListener('input', function() {
        if (this.value) {
            const result = SecureValidator.validatePlaces(this.value);
            if (!result.valid) {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            } else {
                FieldStyler.markAsValid(this);
            }
        }
    });
    
    fields.places.addEventListener('change', function() {
        const result = SecureValidator.validatePlaces(this.value);
        if (!result.valid) {
            FieldStyler.markAsInvalid(this, result.errors[0]);
        } else {
            FieldStyler.markAsValid(this);
        }
    });
    
    // Prix
    fields.price.addEventListener('input', function() {
        // Limiter l'input aux nombres et point décimal
        this.value = this.value.replace(/[^0-9.]/g, '');
        
        // Empêcher plus d'un point
        const parts = this.value.split('.');
        if (parts.length > 2) {
            this.value = parts[0] + '.' + parts.slice(1).join('');
        }
        
        if (this.value) {
            const result = SecureValidator.validatePrice(this.value);
            if (result.valid) {
                FieldStyler.markAsValid(this);
            } else {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        } else {
            FieldStyler.markAsNeutral(this);
        }
    });
}
