/**
 * Security Validator - Universal input validation and sanitization
 * Protection against: XSS, SQL injection, hex encoding, special characters, etc.
 * Version: 2.0 - Enhanced with real-time input filtering
 */

class SecurityValidator {
    // Patterns de caractères dangereux
    static dangerousPatterns = {
        // Scripts et code malveillant
        xss: /<script|<iframe|<object|<embed|javascript:|onerror|onload|onclick|onmouseover|eval\(|expression\(/gi,
        
        // SQL Injection
        sql: /(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE|UNION|SCRIPT)\b|--|;|\/\*|\*\/|xp_|sp_)/gi,
        
        // Encodage hexadécimal/binaire
        hexEncoding: /(\\x[0-9a-fA-F]{2}|%[0-9a-fA-F]{2}){3,}/g,
        binaryEncoding: /[01]{32,}/g,
        
        // Caractères de contrôle et Unicode suspects
        controlChars: /[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/g,
        unicodeExploits: /\\u[0-9a-fA-F]{4}/gi,
        
        // Backslash et caractères spéciaux dangereux
        dangerousChars: /[<>{}[\]\\|`]/g,
        
        // HTML entities suspectes
        htmlEntities: /&(#\d+|#x[0-9a-fA-F]+|[a-zA-Z]+);/g
    };

    /**
     * Bloque la saisie de caractères dangereux en temps réel
     */
    static setupInputFiltering(input, options = {}) {
        const config = {
            allowedPattern: null,  // Regex des caractères autorisés
            maxLength: null,
            blockDangerous: true,
            sanitize: true,
            ...options
        };

        // Filtrage en temps réel sur input
        input.addEventListener('input', function(e) {
            let value = this.value;
            let cursorPosition = this.selectionStart;
            let originalLength = value.length;

            // Bloquer caractères dangereux
            if (config.blockDangerous) {
                // Supprimer backslashes
                value = value.replace(/\\/g, '');
                
                // Supprimer caractères de contrôle
                value = value.replace(SecurityValidator.dangerousPatterns.controlChars, '');
                
                // Supprimer < > { } [ ] |
                value = value.replace(/[<>{}[\]|`]/g, '');
            }

            // Appliquer pattern autorisé si défini
            if (config.allowedPattern) {
                const chars = value.split('');
                value = chars.filter(char => config.allowedPattern.test(char)).join('');
            }

            // Limiter longueur
            if (config.maxLength && value.length > config.maxLength) {
                value = value.substring(0, config.maxLength);
            }

            // Mettre à jour si changement
            if (value !== this.value) {
                this.value = value;
                
                // Ajuster position curseur
                const lengthDiff = originalLength - value.length;
                const newPosition = Math.max(0, cursorPosition - lengthDiff);
                this.setSelectionRange(newPosition, newPosition);
            }
        });

        // Validation sur blur
        input.addEventListener('blur', function() {
            if (config.sanitize) {
                this.value = SecurityValidator.sanitizeInput(this.value);
            }
        });

        // Prévenir paste de contenu dangereux
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const sanitized = SecurityValidator.sanitizeInput(pastedText);
            
            // Appliquer les mêmes filtres que input
            let cleaned = sanitized;
            
            if (config.blockDangerous) {
                cleaned = cleaned.replace(/\\/g, '');
                cleaned = cleaned.replace(/[<>{}[\]|`]/g, '');
            }
            
            if (config.allowedPattern) {
                const chars = cleaned.split('');
                cleaned = chars.filter(char => config.allowedPattern.test(char)).join('');
            }
            
            if (config.maxLength) {
                const remainingLength = config.maxLength - this.value.length;
                cleaned = cleaned.substring(0, remainingLength);
            }
            
            document.execCommand('insertText', false, cleaned);
        });
    }

    /**
     * Nettoie une chaîne de caractères dangereux
     */
    static sanitizeInput(value) {
        if (typeof value !== 'string') return '';
        
        // Supprimer caractères de contrôle
        value = value.replace(this.dangerousPatterns.controlChars, '');
        
        // Supprimer backslashes
        value = value.replace(/\\/g, '');
        
        // Trim
        value = value.trim();
        
        return value;
    }

    /**
     * Détecte les menaces de sécurité dans une valeur
     */
    static detectThreats(value) {
        const threats = [];
        
        if (!value || typeof value !== 'string') return threats;

        // XSS
        if (this.dangerousPatterns.xss.test(value)) {
            threats.push('XSS');
        }

        // SQL Injection
        if (this.dangerousPatterns.sql.test(value)) {
            threats.push('SQL Injection');
        }

        // Hex encoding
        if (this.dangerousPatterns.hexEncoding.test(value)) {
            threats.push('Hex encoding');
        }

        // Binary encoding
        if (this.dangerousPatterns.binaryEncoding.test(value)) {
            threats.push('Binary encoding');
        }

        // Unicode exploits
        if (this.dangerousPatterns.unicodeExploits.test(value)) {
            threats.push('Unicode exploit');
        }

        // Caractères dangereux
        if (this.dangerousPatterns.dangerousChars.test(value)) {
            threats.push('Caractères dangereux');
        }

        return threats;
    }

    /**
     * Validation pour numéro de rue - STRICT
     * Accepte uniquement: chiffres, bis/ter/quater, lettres A-Z, tiret
     * BLOQUE: points, virgules, slash, backslash
     */
    static validateStreetNumber(value) {
        const sanitized = this.sanitizeInput(value);
        
        if (!sanitized) {
            return { valid: true, value: sanitized, error: null };
        }

        // Bloquer décimaux (points/virgules)
        if (/[.,\/\\]/.test(sanitized)) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Le numéro ne peut pas contenir de point, virgule, slash ou backslash' 
            };
        }

        // Format strict: nombre + suffixe optionnel + tiret optionnel
        // Exemples valides: 10, 10bis, 10B, 10-12
        if (!/^\d+\s*(bis|ter|quater|[A-Za-z])?\s*(-\d+)?$/i.test(sanitized)) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Format invalide. Exemples: 10, 10bis, 10B, 10-12' 
            };
        }

        // Vérifier longueur
        if (sanitized.length > 10) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Le numéro est trop long (maximum 10 caractères)' 
            };
        }

        // Vérifier menaces
        const threats = this.detectThreats(sanitized);
        if (threats.length > 0) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Caractères interdits détectés' 
            };
        }

        return { valid: true, value: sanitized, error: null };
    }

    /**
     * Validation pour nom de rue - STRICT
     * Pas de backslash, chevrons, accolades
     */
    static validateStreetName(value) {
        const sanitized = this.sanitizeInput(value);
        
        if (!sanitized) {
            return { valid: true, value: sanitized, error: null };
        }

        // Bloquer caractères dangereux
        if (/[<>{}[\]\\|`]/.test(sanitized)) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'La rue ne peut pas contenir de caractères spéciaux dangereux' 
            };
        }

        // Format: lettres, chiffres, espaces, tirets, apostrophes, points, virgules, slash
        if (!/^[a-zA-Z0-9À-ÿ\s\-'.,/]+$/.test(sanitized)) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'La rue contient des caractères non autorisés' 
            };
        }

        if (sanitized.length > 150) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Le nom de rue est trop long (maximum 150 caractères)' 
            };
        }

        // Vérifier menaces
        const threats = this.detectThreats(sanitized);
        if (threats.length > 0) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Caractères interdits détectés' 
            };
        }

        return { valid: true, value: sanitized, error: null };
    }

    /**
     * Validation pour nom de ville - STRICT
     */
    static validateCityName(value) {
        const sanitized = this.sanitizeInput(value);
        
        if (!sanitized) {
            return { valid: false, value: sanitized, error: 'La ville est obligatoire' };
        }

        // Bloquer caractères dangereux
        if (/[<>{}[\]\\|`0-9]/.test(sanitized)) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'La ville ne peut pas contenir de chiffres ou caractères spéciaux' 
            };
        }

        // Format: lettres, espaces, tirets, apostrophes uniquement
        if (!/^[a-zA-ZÀ-ÿ\s\-']+$/.test(sanitized)) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'La ville contient des caractères non autorisés' 
            };
        }

        if (sanitized.length < 2 || sanitized.length > 100) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Le nom de ville doit faire entre 2 et 100 caractères' 
            };
        }

        return { valid: true, value: sanitized, error: null };
    }

    /**
     * Validation pour noms/prénoms
     */
    static validateName(value) {
        const sanitized = this.sanitizeInput(value);
        
        if (!sanitized) {
            return { valid: false, value: sanitized, error: 'Ce champ est obligatoire' };
        }

        // Bloquer chiffres et caractères spéciaux
        if (!/^[a-zA-ZÀ-ÿ\s\-']+$/.test(sanitized)) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Seules les lettres, espaces, tirets et apostrophes sont autorisés' 
            };
        }

        if (sanitized.length < 2 || sanitized.length > 50) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Doit faire entre 2 et 50 caractères' 
            };
        }

        return { valid: true, value: sanitized, error: null };
    }

    /**
     * Validation pour email
     */
    static validateEmail(value) {
        const sanitized = this.sanitizeInput(value);
        
        if (!sanitized) {
            return { valid: false, value: sanitized, error: 'L\'email est obligatoire' };
        }

        // Pattern email strict
        const emailPattern = /^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(sanitized)) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Format d\'email invalide' 
            };
        }

        if (sanitized.length > 254) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'L\'email est trop long' 
            };
        }

        return { valid: true, value: sanitized, error: null };
    }

    /**
     * Validation pour prix/montants
     */
    static validatePrice(value) {
        const sanitized = this.sanitizeInput(value);
        
        if (!sanitized) {
            return { valid: true, value: sanitized, error: null };
        }

        // Format: nombres et point décimal uniquement
        if (!/^\d+(\.\d{1,2})?$/.test(sanitized)) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Format invalide. Exemple: 15.50' 
            };
        }

        const price = parseFloat(sanitized);
        if (price < 0 || price > 250) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Le prix doit être entre 0 et 250€' 
            };
        }

        return { valid: true, value: sanitized, error: null };
    }

    /**
     * Validation pour textarea (message, description)
     */
    static validateTextarea(value, minLength = 10, maxLength = 1000) {
        const sanitized = this.sanitizeInput(value);
        
        if (!sanitized) {
            return { valid: false, value: sanitized, error: 'Ce champ est obligatoire' };
        }

        // Bloquer caractères dangereux
        if (/[<>{}[\]\\|`]/.test(sanitized)) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Le texte contient des caractères non autorisés' 
            };
        }

        if (sanitized.length < minLength) {
            return { 
                valid: false, 
                value: sanitized, 
                error: `Le texte doit faire au moins ${minLength} caractères` 
            };
        }

        if (sanitized.length > maxLength) {
            return { 
                valid: false, 
                value: sanitized, 
                error: `Le texte ne peut pas dépasser ${maxLength} caractères` 
            };
        }

        // Vérifier menaces
        const threats = this.detectThreats(sanitized);
        if (threats.length > 0) {
            return { 
                valid: false, 
                value: sanitized, 
                error: 'Contenu suspect détecté' 
            };
        }

        return { valid: true, value: sanitized, error: null };
    }
}

// Export pour utilisation dans d'autres fichiers
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SecurityValidator;
}
