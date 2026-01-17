<?php
/**
 * Configuration Stripe pour CarShare
 * 
 * MODE TEST - Aucun débit réel, uniquement vérification de carte
 * Pour projet académique
 * 
 * INSTRUCTIONS :
 * 1. Créer un compte Stripe gratuit sur https://dashboard.stripe.com/register
 * 2. Récupérer vos clés TEST (pas les clés LIVE !)
 * 3. Remplacer les valeurs ci-dessous par vos vraies clés TEST
 * 4. Les clés TEST commencent par pk_test_ et sk_test_
 * 
 * CARTES DE TEST GRATUITES :
 * - Carte valide : 4242 4242 4242 4242
 * - Carte déclinée : 4000 0000 0000 0002
 * - CVV : n'importe quel 3 chiffres
 * - Date : n'importe quelle date future
 */

class StripeConfig {
    
    /**
     * Clé publique Stripe (mode TEST)
     * À afficher côté client, pas de risque de sécurité
     */
    const STRIPE_PUBLIC_KEY = 'pk_test_51SqcqKKkNIU0XghS3UZgDz8Wmzub0b6hoO6HjFPaASHwmIZvGmlmooB6VVLcreTalQ0vyrTu1K8UeNUZKGiS1w7r002HQeyKk5';
    
    /**
     * Clé secrète Stripe (mode TEST)
     * NE JAMAIS EXPOSER CÔTÉ CLIENT !
     */
    const STRIPE_SECRET_KEY = 'sk_test_51SqcqKKkNIU0XghSwpzG2KWexRRHKOBqCLdiURuuSfpycqZ1amxRzOr9N9qc1wulxAfG8QIZeLuTvRsy30b7n9bo00xQUCjtuL';
    
    /**
     * Mode de fonctionnement
     * true = Mode test (cartes de test uniquement, aucun débit réel)
     * false = Mode production (vrais paiements - À NE PAS UTILISER pour projet académique)
     */
    const TEST_MODE = true;
    
    /**
     * Devise par défaut
     */
    const CURRENCY = 'eur';
    
    /**
     * Nom de l'entreprise affiché sur les reçus
     */
    const COMPANY_NAME = 'CarShare';
    
    /**
     * Description pour les vérifications de carte
     */
    const VERIFICATION_DESCRIPTION = 'Vérification de carte bancaire - Aucun débit';
    
    /**
     * Vérifier que Stripe est bien configuré
     * 
     * @return bool True si configuré, False sinon
     */
    public static function isConfigured() {
        return self::STRIPE_PUBLIC_KEY !== 'pk_test_VOTRE_CLE_PUBLIQUE_ICI' 
            && self::STRIPE_SECRET_KEY !== 'sk_test_VOTRE_CLE_SECRETE_ICI'
            && strpos(self::STRIPE_PUBLIC_KEY, 'pk_test_') === 0
            && strpos(self::STRIPE_SECRET_KEY, 'sk_test_') === 0;
    }
    
    /**
     * Obtenir la clé publique pour le client
     * 
     * @return string
     */
    public static function getPublicKey() {
        return self::STRIPE_PUBLIC_KEY;
    }
    
    /**
     * Obtenir la clé secrète pour le serveur
     * 
     * @return string
     */
    public static function getSecretKey() {
        return self::STRIPE_SECRET_KEY;
    }
    
    /**
     * Message d'aide si non configuré
     * 
     * @return string
     */
    public static function getConfigurationHelp() {
        return "
        <div style='padding: 20px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; margin: 20px;'>
            <h2 style='color: #856404;'>⚙️ Configuration Stripe requise</h2>
            <p>Pour activer le système de paiement, suivez ces étapes :</p>
            <ol style='line-height: 1.8;'>
                <li><strong>Créer un compte Stripe gratuit</strong><br>
                    → <a href='https://dashboard.stripe.com/register' target='_blank'>https://dashboard.stripe.com/register</a>
                </li>
                <li><strong>Activer le mode TEST</strong><br>
                    → Dans le dashboard Stripe, assurez-vous que le bouton \"Mode test\" est activé
                </li>
                <li><strong>Récupérer vos clés TEST</strong><br>
                    → Menu \"Développeurs\" → \"Clés API\"<br>
                    → Copiez la \"Clé publique\" (commence par pk_test_)<br>
                    → Copiez la \"Clé secrète\" (commence par sk_test_)
                </li>
                <li><strong>Configurer le fichier</strong><br>
                    → Ouvrez <code>model/StripeConfig.php</code><br>
                    → Remplacez les valeurs STRIPE_PUBLIC_KEY et STRIPE_SECRET_KEY
                </li>
            </ol>
            <p style='margin-top: 20px; padding: 10px; background: #d1ecf1; border-left: 4px solid #0c5460;'>
                <strong>ℹ️ Mode TEST :</strong> Aucune vraie carte bancaire ne sera débitée.
                Utilisez les cartes de test Stripe (ex: 4242 4242 4242 4242)
            </p>
        </div>
        ";
    }
}
