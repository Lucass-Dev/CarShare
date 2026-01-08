-- Script SQL pour le système de messagerie sécurisée

-- Table pour les conversations entre utilisateurs
CREATE TABLE IF NOT EXISTS conversations (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user1_id BIGINT(20) UNSIGNED NOT NULL,
    user2_id BIGINT(20) UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_user1 (user1_id),
    KEY idx_user2 (user2_id),
    KEY idx_users (user1_id, user2_id),
    FOREIGN KEY (user1_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table pour les messages
CREATE TABLE IF NOT EXISTS messages (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    conversation_id BIGINT(20) UNSIGNED NOT NULL,
    sender_id BIGINT(20) UNSIGNED NOT NULL,
    receiver_id BIGINT(20) UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    encrypted_content TEXT,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_conversation (conversation_id),
    KEY idx_sender (sender_id),
    KEY idx_receiver (receiver_id),
    KEY idx_created (created_at),
    FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ajouter colonnes pour clés de chiffrement dans users (si elles n'existent pas)
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS public_key TEXT,
ADD COLUMN IF NOT EXISTS private_key TEXT;
