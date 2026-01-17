-- Table pour tracer les vérifications de carte Stripe
-- Aucune donnée sensible stockée (conformité PCI-DSS)
-- Pour projet académique CarShare

CREATE TABLE IF NOT EXISTS card_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Référence utilisateur
    user_id INT NOT NULL,
    
    -- Référence réservation
    booking_id INT NULL,
    carpooling_id INT NOT NULL,
    
    -- Informations Stripe (IDs publics, pas de données sensibles)
    stripe_setup_intent_id VARCHAR(255) NULL,
    stripe_payment_method_id VARCHAR(255) NULL,
    
    -- Résultat de la vérification
    verification_status ENUM('pending', 'succeeded', 'failed', 'canceled') DEFAULT 'pending',
    
    -- Derniers 4 chiffres de la carte (seule info conservée)
    card_last4 VARCHAR(4) NULL,
    card_brand VARCHAR(20) NULL COMMENT 'visa, mastercard, amex, etc.',
    
    -- Montant symbolique vérifié (toujours 0 pour projet académique)
    amount_verified DECIMAL(10,2) DEFAULT 0.00,
    
    -- Message d'erreur si échec
    error_message TEXT NULL,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verified_at TIMESTAMP NULL,
    
    -- Clés étrangères
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (carpooling_id) REFERENCES carpoolings(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
    
    -- Index pour recherches rapides
    INDEX idx_user_id (user_id),
    INDEX idx_booking_id (booking_id),
    INDEX idx_setup_intent (stripe_setup_intent_id),
    INDEX idx_status (verification_status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Vérifications de carte bancaire via Stripe - Mode TEST uniquement';

-- Exemple de requête pour voir les vérifications récentes
-- SELECT cv.*, u.email, u.first_name, u.last_name, c.start_location, c.end_location
-- FROM card_verifications cv
-- JOIN users u ON cv.user_id = u.id
-- JOIN carpoolings c ON cv.carpooling_id = c.id
-- ORDER BY cv.created_at DESC
-- LIMIT 20;
