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
    
    static validateCity(value, fieldName = 'ville', inputElement = null) {
        const sanitized = this.sanitizeInput(value);
        const errors = [];
        
        if (!sanitized) {
            errors.push(`La ${fieldName} est obligatoire`);
            return { valid: false, errors, value: sanitized };
        }
        
        // Si un champ est fourni, obliger la sélection via la liste officielle
        if (inputElement) {
            // Accepter si sélectionné via liste OU si pré-rempli par le serveur (initial load)
            if (inputElement.dataset.selectedFromList === 'true' || inputElement.dataset.serverProvided === 'true') {
                return { valid: true, errors: [], value: sanitized };
            }
            errors.push(`Veuillez sélectionner la ${fieldName} dans la liste proposée`);
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
        
        // Validation: lettres, espaces, tirets, apostrophes, chiffres (pour Saint-Ouen-93, etc.)
        if (!/^[a-zA-Z0-9À-ÿ\s\-']+$/.test(sanitized)) {
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
        
        // Validation format de base
        if (!/^[a-zA-Z0-9À-ÿ\s\-',./]+$/.test(sanitized)) {
            errors.push(`La ${fieldName} contient des caractères non autorisés`);
        }
        
        // VALIDATION MINIMALE : Au moins 2 lettres consécutives
        if (!/[a-zA-ZÀ-ÿ]{2,}/.test(sanitized)) {
            errors.push(`La ${fieldName} doit contenir au moins 2 lettres consécutives`);
        }
        
        // Bloquer séquences de chiffres trop longues (> 5)
        if (/\d{6,}/.test(sanitized)) {
            errors.push(`La ${fieldName} contient une séquence de chiffres trop longue (max 5 chiffres consécutifs)`);
        }
        
        // Bloquer répétitions excessives du même caractère (> 3 fois)
        if (/(.)\1{3,}/.test(sanitized)) {
            errors.push(`La ${fieldName} contient une répétition excessive de caractères`);
        }
        
        // Bloquer alternance trop fréquente chiffres/lettres
        const letterDigitSwitches = (sanitized.match(/[a-zA-Z]\d|\d[a-zA-Z]/g) || []).length;
        if (letterDigitSwitches > 3) {
            errors.push(`La ${fieldName} a un format invalide (trop d'alternances lettres/chiffres)`);
        }
        
        // Vérifier présence de voyelles (au moins 20% des lettres)
        const letters = sanitized.match(/[a-zA-ZÀ-ÿ]/g) || [];
        const vowels = sanitized.match(/[aeiouyàâäéèêëïîôùûüÿœæAEIOUYÀÂÄÉÈÊËÏÎÔÙÛÜŸŒÆ]/g) || [];
        if (letters.length > 4 && vowels.length / letters.length < 0.2) {
            errors.push(`La ${fieldName} a un format invalide (pas assez de voyelles)`);
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
        selectedDate.setHours(0, 0, 0, 0);
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
    
    static validateTime(value, dateValue = null) {
        // Heure optionnelle
        if (!value) {
            return { valid: true, errors: [], value };
        }
        
        const errors = [];
        const timeRegex = /^([01]\d|2[0-3]):([0-5]\d)$/;
        
        if (!timeRegex.test(value)) {
            errors.push('Format d\'heure invalide (HH:MM attendu)');
            return { valid: false, errors, value };
        }
        
        // Si une date est fournie, vérifier que date+heure n'est pas dans le passé
        if (dateValue) {
            const selectedDate = new Date(dateValue);
            selectedDate.setHours(0, 0, 0, 0);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            // Si la date est dans le passé, l'heure est invalide aussi
            if (selectedDate < today) {
                errors.push('La date sélectionnée est dans le passé');
                return { valid: false, errors, value };
            }
            
            // Si c'est aujourd'hui, vérifier que l'heure n'est pas passée
            if (selectedDate.getTime() === today.getTime()) {
                const [hours, minutes] = value.split(':').map(Number);
                const selectedDateTime = new Date();
                selectedDateTime.setHours(hours, minutes, 0, 0);
                const now = new Date();
                
                if (selectedDateTime < now) {
                    errors.push('L\'heure doit être dans le futur pour aujourd\'hui');
                }
            }
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
        } else if (price > 250) {
            errors.push('Le prix ne peut pas dépasser 250 € (participation aux frais)');
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
    
    // Appliquer le filtrage de sécurité sur tous les champs texte
    if (typeof SecurityValidator !== 'undefined') {
        // Numéros de rue - bloquer décimaux et caractères dangereux
        const streetNumbers = form.querySelectorAll('#dep-num, #arr-num');
        streetNumbers.forEach(input => {
            SecurityValidator.setupInputFiltering(input, {
                allowedPattern: /[0-9a-zA-Z\s\-]/,  // Chiffres, lettres, espaces, tirets uniquement
                maxLength: 10,
                blockDangerous: true
            });
        });
        
        // Rues - avec points, virgules, slashes autorisés
        const streets = form.querySelectorAll('#dep-street, #arr-street');
        streets.forEach(input => {
            SecurityValidator.setupInputFiltering(input, {
                allowedPattern: /[a-zA-Z0-9À-ÿ\s\-'.,\/]/,  // Lettres, chiffres, espaces, tirets, apostrophes, points, virgules, slashes
                maxLength: 150,
                blockDangerous: true
            });
        });
        
        // Villes - lettres et chiffres (pour codes postaux)
        const cities = form.querySelectorAll('#dep-city, #arr-city');
        cities.forEach(input => {
            SecurityValidator.setupInputFiltering(input, {
                allowedPattern: /[a-zA-Z0-9À-ÿ\s\-']/,  // Lettres et chiffres (codes postaux)
                maxLength: 100,
                blockDangerous: true
            });
        });
        
        // Prix - seulement chiffres et point
        const priceInput = form.querySelector('#price');
        if (priceInput) {
            SecurityValidator.setupInputFiltering(priceInput, {
                allowedPattern: /[0-9.]/,
                maxLength: 6,  // 250.99
                blockDangerous: true
            });
        }
    }
    
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
    
    // Marquer les villes pré-remplies par le serveur comme valides
    [fields.depCity, fields.arrCity].forEach(cityField => {
        if (cityField && cityField.value.trim()) {
            cityField.dataset.serverProvided = 'true';
            cityField.dataset.initialValue = cityField.value;
        }
    });
    
    // Validation en temps réel pour chaque champ
    setupRealtimeValidation(fields, notificationManager);
    
    // Validation à la soumission
    form.addEventListener('submit', function(e) {
        const allErrors = [];
        let isValid = true;
        
        // Validation de tous les champs
        const validations = {
            depCity: SecureValidator.validateCity(fields.depCity.value, 'ville de départ', fields.depCity),
            arrCity: SecureValidator.validateCity(fields.arrCity.value, 'ville d\'arrivée', fields.arrCity),
            depStreet: SecureValidator.validateStreet(fields.depStreet.value, 'rue de départ'),
            arrStreet: SecureValidator.validateStreet(fields.arrStreet.value, 'rue d\'arrivée'),
            depNum: SecureValidator.validateStreetNumber(fields.depNum.value, 'numéro de départ'),
            arrNum: SecureValidator.validateStreetNumber(fields.arrNum.value, 'numéro d\'arrivée'),
            date: SecureValidator.validateDate(fields.date.value),
            time: SecureValidator.validateTime(fields.time.value, fields.date.value),
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
            e.preventDefault();
            e.stopImmediatePropagation();
            
            // Restaurer le bouton immédiatement en cas d'erreur
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
            
            // Sécurité: restaurer le bouton après 10 secondes en cas de problème réseau
            if (submitButton) {
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.classList.remove('loading');
                    submitButton.textContent = originalButtonText;
                }, 10000);
            }
            // Ne pas appeler e.preventDefault() ni form.submit() 
            // Le formulaire se soumettra naturellement
        }
    });
    
    // Restaurer le bouton au chargement de la page (en cas d'erreur serveur)
    window.addEventListener('pageshow', () => {
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
            submitButton.textContent = originalButtonText;
        }
    });
});

function setupRealtimeValidation(fields, notificationManager) {
    const enforceDistinctCities = (showErrors = true) => {
        if (!fields.depCity || !fields.arrCity) return true;
        const depValue = (fields.depCity.value || '').trim();
        const arrValue = (fields.arrCity.value || '').trim();

        if (depValue && arrValue && depValue.toLowerCase() === arrValue.toLowerCase()) {
            if (showErrors) {
                FieldStyler.markAsInvalid(fields.depCity, 'Les villes de départ et d\'arrivée doivent être différentes');
                fields.depCity.dataset.duplicateCityError = 'true';
                FieldStyler.markAsInvalid(fields.arrCity);
                fields.arrCity.dataset.duplicateCityError = 'true';
            }
            return false;
        }

        [fields.depCity, fields.arrCity].forEach((field, index) => {
            if (!field || !field.dataset.duplicateCityError) return;
            delete field.dataset.duplicateCityError;
            const label = index === 0 ? 'ville de départ' : 'ville d\'arrivée';
            const result = SecureValidator.validateCity(field.value, label, field);
            if (result.valid) {
                if (field.dataset.selectedFromList === 'true') {
                    FieldStyler.markAsValid(field);
                }
            } else if (field.value.trim()) {
                FieldStyler.markAsInvalid(field, result.errors[0]);
            } else {
                FieldStyler.markAsNeutral(field);
            }
        });

        return true;
    };
    
    // Ville de départ
    const validateDepCity = function(event) {
        // Retirer le flag server-provided si l'utilisateur modifie manuellement
        if (event && event.isTrusted && this.dataset.initialValue && this.value !== this.dataset.initialValue) {
            delete this.dataset.serverProvided;
        }
        
        if (this.value.trim()) {
            // Si sélectionné depuis la liste, toujours valide
            if (this.dataset.selectedFromList === 'true' || this.dataset.serverProvided === 'true') {
                FieldStyler.markAsValid(this);
                enforceDistinctCities();
                return;
            }
            
            const result = SecureValidator.validateCity(this.value, 'ville de départ', this);
            if (result.valid) {
                FieldStyler.markAsValid(this);
            } else {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        } else {
            FieldStyler.markAsNeutral(this);
        }
        enforceDistinctCities();
    };
    
    fields.depCity.addEventListener('input', validateDepCity);
    fields.depCity.addEventListener('change', validateDepCity);
    
    fields.depCity.addEventListener('blur', function() {
        // Si sélectionné depuis la liste, toujours valide
        if (this.dataset.selectedFromList === 'true') {
            FieldStyler.markAsValid(this);
            return;
        }
        
        const result = SecureValidator.validateCity(this.value, 'ville de départ', this);
        if (!result.valid && this.value.trim()) {
            FieldStyler.markAsInvalid(this, result.errors[0]);
        }
        enforceDistinctCities();
    });
    
    // Ville d'arrivée
    const validateArrCity = function(event) {
        // Retirer le flag server-provided si l'utilisateur modifie manuellement
        if (event && event.isTrusted && this.dataset.initialValue && this.value !== this.dataset.initialValue) {
            delete this.dataset.serverProvided;
        }
        
        if (this.value.trim()) {
            // Si sélectionné depuis la liste, toujours valide
            if (this.dataset.selectedFromList === 'true' || this.dataset.serverProvided === 'true') {
                FieldStyler.markAsValid(this);
                enforceDistinctCities();
                return;
            }
            
            const result = SecureValidator.validateCity(this.value, 'ville d\'arrivée', this);
            if (result.valid) {
                FieldStyler.markAsValid(this);
            } else {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        } else {
            FieldStyler.markAsNeutral(this);
        }
        enforceDistinctCities();
    };
    
    fields.arrCity.addEventListener('input', validateArrCity);
    fields.arrCity.addEventListener('change', validateArrCity);
    
    fields.arrCity.addEventListener('blur', function() {
        // Si sélectionné depuis la liste, toujours valide
        if (this.dataset.selectedFromList === 'true') {
            FieldStyler.markAsValid(this);
            return;
        }
        
        const result = SecureValidator.validateCity(this.value, 'ville d\'arrivée', this);
        if (!result.valid && this.value.trim()) {
            FieldStyler.markAsInvalid(this, result.errors[0]);
        }
        enforceDistinctCities();
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
    fields.date.addEventListener('input', function() {
        if (this.value) {
            const result = SecureValidator.validateDate(this.value);
            if (!result.valid) {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            } else {
                FieldStyler.markAsValid(this);
            }
            // Revalider l'heure si elle existe
            if (fields.time && fields.time.value) {
                const timeResult = SecureValidator.validateTime(fields.time.value, this.value);
                if (!timeResult.valid) {
                    FieldStyler.markAsInvalid(fields.time, timeResult.errors[0]);
                } else {
                    FieldStyler.markAsValid(fields.time);
                }
            } else if (fields.time && !fields.time.value && result.valid) {
                // Si pas d'heure mais date valide, remettre le champ heure en neutre
                FieldStyler.markAsNeutral(fields.time);
            }
        } else {
            FieldStyler.markAsNeutral(this);
            if (fields.time) {
                FieldStyler.markAsNeutral(fields.time);
            }
        }
    });
    
    fields.date.addEventListener('change', function() {
        const result = SecureValidator.validateDate(this.value);
        if (!result.valid) {
            FieldStyler.markAsInvalid(this, result.errors[0]);
        } else {
            FieldStyler.markAsValid(this);
        }
        // Revalider l'heure si elle existe
        if (fields.time && fields.time.value) {
            const timeResult = SecureValidator.validateTime(fields.time.value, this.value);
            if (!timeResult.valid) {
                FieldStyler.markAsInvalid(fields.time, timeResult.errors[0]);
            } else {
                FieldStyler.markAsValid(fields.time);
            }
        } else if (fields.time && !fields.time.value && result.valid) {
            FieldStyler.markAsNeutral(fields.time);
        }
    });
    
    fields.date.addEventListener('blur', function() {
        if (this.value) {
            const result = SecureValidator.validateDate(this.value);
            if (!result.valid) {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        }
    });
    
    // Heure
    fields.time.addEventListener('input', function() {
        if (this.value) {
            const dateValue = fields.date ? fields.date.value : null;
            const result = SecureValidator.validateTime(this.value, dateValue);
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
            const dateValue = fields.date ? fields.date.value : null;
            const result = SecureValidator.validateTime(this.value, dateValue);
            if (!result.valid) {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            } else {
                FieldStyler.markAsValid(this);
            }
        }
    });
    
    fields.time.addEventListener('blur', function() {
        if (this.value) {
            const dateValue = fields.date ? fields.date.value : null;
            const result = SecureValidator.validateTime(this.value, dateValue);
            if (!result.valid) {
                FieldStyler.markAsInvalid(this, result.errors[0]);
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
    
    fields.price.addEventListener('blur', function() {
        if (this.value) {
            const result = SecureValidator.validatePrice(this.value);
            if (!result.valid) {
                FieldStyler.markAsInvalid(this, result.errors[0]);
            }
        }
    });
}

// ==================== STEP NAVIGATION ====================
// Gestion de la navigation par étapes

class StepNavigator {
    constructor(form) {
        this.form = form;
        this.currentStep = 1;
        this.totalSteps = 3;
        
        this.sections = form.querySelectorAll('.form-section[data-section]');
        this.progressSteps = document.querySelectorAll('.progress-steps .step');
        this.tips = document.querySelectorAll('.contextual-tip[data-step]');
        
        this.btnNext = form.querySelector('.btn-next');
        this.btnPrev = form.querySelector('.btn-prev');
        this.btnSubmit = form.querySelector('.btn-submit');
        
        this.init();
    }
    
    init() {
        // Initialiser l'affichage - forcer l'affichage de l'étape 1
        this.currentStep = 1;
        this.showStep(this.currentStep);
        
        // Événements des boutons
        if (this.btnNext) {
            this.btnNext.addEventListener('click', (e) => {
                e.preventDefault();
                this.validateAndNext();
            });
        }
        
        if (this.btnPrev) {
            this.btnPrev.addEventListener('click', (e) => {
                e.preventDefault();
                this.goToPrevStep();
            });
        }
        
        // Surveiller les modifications pour (dés)activer les boutons en direct
        this.form.addEventListener('input', () => this.updateNavigationState(), true);
        this.form.addEventListener('change', () => this.updateNavigationState(), true);

        // Cliquer sur les indicateurs d'étape
        this.progressSteps.forEach((step) => {
            step.addEventListener('click', () => {
                const targetStep = parseInt(step.dataset.step);
                if (targetStep < this.currentStep) {
                    this.goToStep(targetStep);
                }
            });
        });
        
        // Validation initiale après restauration de données persistantes
        setTimeout(() => this.updateNavigationState(), 100);
    }
    
    showStep(stepNum) {
        // Masquer toutes les sections
        this.sections.forEach(section => {
            const sectionStep = parseInt(section.dataset.section);
            if (sectionStep === stepNum) {
                section.style.cssText = 'display: block !important;';
                section.classList.add('active');
            } else {
                section.style.cssText = 'display: none !important;';
                section.classList.remove('active');
            }
        });
        
        // Masquer les dividers sauf ceux de l'étape courante
        const dividers = this.form.querySelectorAll('.section-divider');
        dividers.forEach(divider => {
            const dividerStep = parseInt(divider.dataset?.section || '0');
            divider.style.display = (dividerStep === stepNum) ? 'flex' : 'none';
        });
        
        // Mettre à jour les indicateurs de progression
        this.progressSteps.forEach(step => {
            const stepIndex = parseInt(step.dataset.step);
            step.classList.remove('active', 'completed');
            
            if (stepIndex === stepNum) {
                step.classList.add('active');
            } else if (stepIndex < stepNum) {
                step.classList.add('completed');
            }
        });
        
        // Conseils contextuels
        this.tips.forEach(tip => {
            const tipStep = parseInt(tip.dataset.step);
            tip.style.display = (tipStep === stepNum) ? 'flex' : 'none';
        });
        
        // Gestion des boutons
        if (this.btnPrev) {
            this.btnPrev.style.display = (stepNum > 1) ? 'flex' : 'none';
        }
        
        if (this.btnNext) {
            this.btnNext.style.display = (stepNum < this.totalSteps) ? 'flex' : 'none';
        }
        
        if (this.btnSubmit) {
            this.btnSubmit.style.display = (stepNum === this.totalSteps) ? 'flex' : 'none';
        }
        
        // Scroll vers le haut du formulaire
        this.form.scrollIntoView({ behavior: 'smooth', block: 'start' });

        // Mettre à jour l'état des boutons après toute transition
        this.updateNavigationState();
    }

    validateCurrentStep(showErrors = true) {
        const fieldsToValidate = this.getFieldsForStep(this.currentStep);
        let isValid = true;
        let firstInvalidField = null;
        
        fieldsToValidate.forEach(fieldInfo => {
            const field = document.getElementById(fieldInfo.id);
            if (!field) return;
            
            let result;
            switch (fieldInfo.type) {
                case 'city':
                    result = SecureValidator.validateCity(field.value, fieldInfo.label, field);
                    break;
                case 'street':
                    result = SecureValidator.validateStreet(field.value, fieldInfo.label);
                    break;
                case 'streetNumber':
                    result = SecureValidator.validateStreetNumber(field.value, fieldInfo.label);
                    break;
                case 'date':
                    result = SecureValidator.validateDate(field.value);
                    break;
                case 'time':
                    const dateField = document.getElementById('date');
                    const dateValue = dateField ? dateField.value : null;
                    result = SecureValidator.validateTime(field.value, dateValue);
                    break;
                case 'places':
                    result = SecureValidator.validatePlaces(field.value);
                    break;
                case 'price':
                    result = SecureValidator.validatePrice(field.value);
                    break;
                default:
                    result = { valid: true, errors: [] };
            }
            
            if (!result.valid) {
                isValid = false;
                if (showErrors) {
                    FieldStyler.markAsInvalid(field, result.errors[0]);
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                }
            } else if (showErrors) {
                const value = typeof field.value === 'string' ? field.value.trim() : '';
                if (value || fieldInfo.required) {
                    FieldStyler.markAsValid(field);
                }
            }
        });
        
        // Vérification supplémentaire: villes différentes (étape 1)
        if (this.currentStep === 1 && isValid) {
            const depCity = document.getElementById('dep-city');
            const arrCity = document.getElementById('arr-city');
            
            if (depCity && arrCity && depCity.value.trim() && arrCity.value.trim()) {
                if (depCity.value.trim().toLowerCase() === arrCity.value.trim().toLowerCase()) {
                    isValid = false;
                    if (showErrors) {
                        FieldStyler.markAsInvalid(depCity, 'Les villes de départ et d\'arrivée doivent être différentes');
                        FieldStyler.markAsInvalid(arrCity);
                        firstInvalidField = depCity;
                    }
                }
            }
        }
        
        if (!isValid && firstInvalidField && showErrors) {
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidField.focus();
        }
        
        return isValid;
    }

    updateNavigationState() {
        const stepIsValid = this.validateCurrentStep(false);

        if (this.btnNext) {
            const shouldDisableNext = this.currentStep >= this.totalSteps || !stepIsValid;
            this.btnNext.disabled = shouldDisableNext;
        }
        
        if (this.btnSubmit) {
            const shouldDisableSubmit = this.currentStep !== this.totalSteps || !stepIsValid;
            this.btnSubmit.disabled = shouldDisableSubmit;
        }
    }
    
    getFieldsForStep(stepNum) {
        switch (stepNum) {
            case 1:
                return [
                    { id: 'dep-city', type: 'city', label: 'ville de départ', required: true },
                    { id: 'arr-city', type: 'city', label: 'ville d\'arrivée', required: true },
                    { id: 'dep-street', type: 'street', label: 'rue de départ', required: false },
                    { id: 'arr-street', type: 'street', label: 'rue d\'arrivée', required: false },
                    { id: 'dep-num', type: 'streetNumber', label: 'numéro de départ', required: false },
                    { id: 'arr-num', type: 'streetNumber', label: 'numéro d\'arrivée', required: false }
                ];
            case 2:
                return [
                    { id: 'date', type: 'date', label: 'date', required: true },
                    { id: 'time', type: 'time', label: 'heure', required: false },
                    { id: 'price', type: 'price', label: 'prix', required: false }
                ];
            case 3:
                return [
                    { id: 'places', type: 'places', label: 'places', required: true }
                ];
            default:
                return [];
        }
    }
    
    validateAndNext() {
        if (this.validateCurrentStep()) {
            this.goToNextStep();
        }
    }
    
    goToNextStep() {
        if (this.currentStep < this.totalSteps) {
            this.currentStep++;
            this.showStep(this.currentStep);
        }
    }
    
    goToPrevStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.showStep(this.currentStep);
        }
    }
    
    goToStep(stepNum) {
        if (stepNum >= 1 && stepNum <= this.totalSteps) {
            this.currentStep = stepNum;
            this.showStep(this.currentStep);
        }
    }
}

// Initialiser la navigation par étapes après le chargement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.trip-form-modern');
    if (form && form.querySelector('.btn-next')) {
        // Initialiser le navigateur d'étapes
        window.stepNavigator = new StepNavigator(form);
    }
});
