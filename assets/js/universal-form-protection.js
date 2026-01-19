/**
 * Universal Form Protection
 * Protection universelle pour tous les formulaires du site
 * Empêche les boutons de rester bloqués en "chargement"
 */

(function() {
    'use strict';
    
    // Map pour stocker l'état original des boutons
    const buttonStates = new WeakMap();
    
    document.addEventListener('DOMContentLoaded', function() {
        protectAllForms();
    });
    
    // Restaurer tous les boutons au chargement de la page (navigation arrière, erreur serveur)
    window.addEventListener('pageshow', function() {
        restoreAllButtons();
    });
    
    function protectAllForms() {
        // Sélectionner tous les formulaires qui n'ont pas déjà une protection custom
        const forms = document.querySelectorAll('form:not(.custom-validation):not(.no-protection)');
        
        forms.forEach(form => {
            const submitBtn = form.querySelector('button[type="submit"]');
            
            if (!submitBtn) return;
            
            // Sauvegarder l'état original du bouton
            buttonStates.set(submitBtn, {
                text: submitBtn.innerHTML,
                disabled: submitBtn.disabled
            });
            
            // Restaurer le bouton au chargement
            restoreButton(submitBtn);
            
            // Ajouter la gestion de soumission
            form.addEventListener('submit', function(e) {
                // Vérifier la validation HTML5
                if (!form.checkValidity()) {
                    return; // Laisser la validation HTML5 faire son travail
                }
                
                // Désactiver le bouton et changer le texte
                const originalState = buttonStates.get(submitBtn);
                if (originalState) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');
                    
                    // Créer un texte de chargement approprié
                    const loadingText = getLoadingText(submitBtn);
                    submitBtn.innerHTML = `<span class="spinner"></span> ${loadingText}`;
                    
                    // Sécurité: restaurer après 10 secondes en cas de problème
                    setTimeout(() => {
                        restoreButton(submitBtn);
                    }, 10000);
                }
            });
        });
    }
    
    function restoreAllButtons() {
        document.querySelectorAll('button[type="submit"]').forEach(btn => {
            restoreButton(btn);
        });
    }
    
    function restoreButton(button) {
        const originalState = buttonStates.get(button);
        
        if (originalState) {
            button.disabled = originalState.disabled;
            button.innerHTML = originalState.text;
            button.classList.remove('loading');
        } else {
            // Si pas d'état sauvegardé, juste réactiver
            button.disabled = false;
            button.classList.remove('loading');
        }
    }
    
    function getLoadingText(button) {
        const text = button.textContent.toLowerCase().trim();
        
        if (text.includes('envoyer')) return 'Envoi en cours...';
        if (text.includes('connexion') || text.includes('connecter')) return 'Connexion...';
        if (text.includes('inscription') || text.includes('inscrire')) return 'Inscription...';
        if (text.includes('enregistrer') || text.includes('sauvegarder')) return 'Enregistrement...';
        if (text.includes('confirmer')) return 'Confirmation...';
        if (text.includes('modifier')) return 'Modification...';
        if (text.includes('supprimer')) return 'Suppression...';
        if (text.includes('publier')) return 'Publication...';
        if (text.includes('réserver')) return 'Réservation...';
        
        return 'Traitement...';
    }
    
    // Ajouter le style du spinner si nécessaire
    if (!document.getElementById('universal-form-protection-styles')) {
        const style = document.createElement('style');
        style.id = 'universal-form-protection-styles';
        style.textContent = `
            button.loading {
                opacity: 0.7;
                cursor: wait !important;
            }
            
            button .spinner {
                display: inline-block;
                width: 12px;
                height: 12px;
                border: 2px solid rgba(255,255,255,0.3);
                border-top-color: white;
                border-radius: 50%;
                animation: spinner-rotate 0.6s linear infinite;
            }
            
            @keyframes spinner-rotate {
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
})();
