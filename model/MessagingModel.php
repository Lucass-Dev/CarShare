<?php

require_once 'Database.php';

class MessagingModel {
    private $db;

    public function __construct() {
        $this->db = Database::getDb();
    }

    /**
     * Generate RSA key pair for a user (simplified version using base64)
     */
    public function generateKeysForUser($userId) {
        // Simple encryption: using base64 + user-specific salt
        // In production, use openssl_pkey_new() for real RSA
        $publicKey = base64_encode("public_key_user_" . $userId . "_" . time());
        $privateKey = base64_encode("private_key_user_" . $userId . "_" . time());

        $stmt = $this->db->prepare("
            UPDATE users 
            SET public_key = ?, private_key = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$publicKey, $privateKey, $userId]);
    }

    /**
     * Get or create conversation between two users
     */
    public function getOrCreateConversation($user1Id, $user2Id) {
        // Check if conversation exists
        $stmt = $this->db->prepare("
            SELECT id FROM conversations 
            WHERE (user1_id = ? AND user2_id = ?) 
               OR (user1_id = ? AND user2_id = ?)
            LIMIT 1
        ");
        $stmt->execute([$user1Id, $user2Id, $user2Id, $user1Id]);
        $conversation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($conversation) {
            return $conversation['id'];
        }

        // Create new conversation
        $stmt = $this->db->prepare("
            INSERT INTO conversations (user1_id, user2_id) 
            VALUES (?, ?)
        ");
        $stmt->execute([$user1Id, $user2Id]);
        return $this->db->lastInsertId();
    }

    /**
     * Get all conversations for a user
     */
    public function getUserConversations($userId) {
        $stmt = $this->db->prepare("
            SELECT 
                c.id,
                CASE 
                    WHEN c.user1_id = ? THEN c.user2_id 
                    ELSE c.user1_id 
                END as other_user_id,
                CASE 
                    WHEN c.user1_id = ? THEN CONCAT(u2.first_name, ' ', u2.last_name)
                    ELSE CONCAT(u1.first_name, ' ', u1.last_name)
                END as other_user_name,
                (SELECT content FROM private_message WHERE id_conv = c.id ORDER BY send_at DESC LIMIT 1) as last_message,
                (SELECT send_at FROM private_message WHERE id_conv = c.id ORDER BY send_at DESC LIMIT 1) as last_message_time,
                0 as unread_count
            FROM conversations c
            JOIN users u1 ON c.user1_id = u1.id
            JOIN users u2 ON c.user2_id = u2.id
            WHERE c.user1_id = ? OR c.user2_id = ?
            ORDER BY (SELECT MAX(send_at) FROM private_message WHERE id_conv = c.id) DESC
        ");
        $stmt->execute([$userId, $userId, $userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get messages in a conversation
     */
    public function getConversationMessages($conversationId, $userId, $limit = 50) {
        // Verify user is part of conversation
        $stmt = $this->db->prepare("
            SELECT id FROM conversations 
            WHERE id = ? AND (user1_id = ? OR user2_id = ?)
        ");
        $stmt->execute([$conversationId, $userId, $userId]);
        if (!$stmt->fetch()) {
            return [];
        }

        // Get messages
        $stmt = $this->db->prepare("
            SELECT 
                m.id,
                m.sender_id,
                m.content,
                m.send_at as created_at,
                CONCAT(u.first_name, ' ', u.last_name) as sender_name
            FROM private_message m
            JOIN users u ON m.sender_id = u.id
            WHERE m.id_conv = ?
            ORDER BY m.send_at ASC
            LIMIT ?
        ");
        $stmt->execute([$conversationId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Send a message
     */
    public function sendMessage($conversationId, $senderId, $receiverId, $content) {
        $stmt = $this->db->prepare("
            INSERT INTO private_message (id_conv, sender_id, content, send_at) 
            VALUES (?, ?, ?, NOW())
        ");
        
        $result = $stmt->execute([
            $conversationId, 
            $senderId, 
            $content
        ]);

        if ($result) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Mark messages as read (not implemented in this database structure)
     */
    public function markMessagesAsRead($conversationId, $userId) {
        // La table private_message n'a pas de colonne is_read
        // Cette fonctionnalité nécessiterait une modification de la structure de la base de données
        return true;
    }

    /**
     * Get unread message count for user
     * Count conversations where the last message was NOT sent by the user
     */
    public function getUnreadCount($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM conversations c
            WHERE (c.user1_id = ? OR c.user2_id = ?)
            AND EXISTS (
                SELECT 1 
                FROM private_message pm 
                WHERE pm.id_conv = c.id 
                AND pm.id = (
                    SELECT MAX(id) 
                    FROM private_message 
                    WHERE id_conv = c.id
                )
                AND pm.sender_id != ?
            )
        ");
        $stmt->execute([$userId, $userId, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['count'] : 0;
    }

    /**
     * Get conversation info
     */
    public function getConversationInfo($conversationId, $userId) {
        $stmt = $this->db->prepare("
            SELECT 
                c.id,
                CASE 
                    WHEN c.user1_id = ? THEN c.user2_id 
                    ELSE c.user1_id 
                END as other_user_id,
                CASE 
                    WHEN c.user1_id = ? THEN CONCAT(u2.first_name, ' ', u2.last_name)
                    ELSE CONCAT(u1.first_name, ' ', u1.last_name)
                END as other_user_name
            FROM conversations c
            JOIN users u1 ON c.user1_id = u1.id
            JOIN users u2 ON c.user2_id = u2.id
            WHERE c.id = ? AND (c.user1_id = ? OR c.user2_id = ?)
        ");
        $stmt->execute([$userId, $userId, $conversationId, $userId, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Delete a conversation
     */
    public function deleteConversation($conversationId, $userId) {
        // Verify user owns this conversation
        $stmt = $this->db->prepare("
            DELETE FROM conversations 
            WHERE id = ? AND (user1_id = ? OR user2_id = ?)
        ");
        return $stmt->execute([$conversationId, $userId, $userId]);
    }

    /**
     * Get unread message count for a user
     * Count conversations where the last message was NOT sent by the user
     */
    public function getUnreadMessageCount($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM conversations c
            WHERE (c.user1_id = ? OR c.user2_id = ?)
            AND EXISTS (
                SELECT 1 
                FROM private_message pm 
                WHERE pm.id_conv = c.id 
                AND pm.id = (
                    SELECT MAX(id) 
                    FROM private_message 
                    WHERE id_conv = c.id
                )
                AND pm.sender_id != ?
            )
        ");
        $stmt->execute([$userId, $userId, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['count'] : 0;
    }
}
