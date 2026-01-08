<?php

require_once 'model/MessagingModel.php';

class MessagingController {
    private $model;

    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['logged']) || !$_SESSION['logged']) {
            header('Location: index.php?action=login');
            exit;
        }

        $this->model = new MessagingModel();
    }

    /**
     * Display list of conversations
     */
    public function index() {
        $userId = $_SESSION['user_id'];
        $conversations = $this->model->getUserConversations($userId);
        $unreadCount = $this->model->getUnreadCount($userId);

        require_once 'view/MessagingView.php';
    }

    /**
     * Display a specific conversation
     */
    public function conversation() {
        $userId = $_SESSION['user_id'];
        $conversationId = $_GET['conversation_id'] ?? null;
        $otherUserId = $_GET['user_id'] ?? null;

        // If user_id provided, get or create conversation
        if ($otherUserId && !$conversationId) {
            $conversationId = $this->model->getOrCreateConversation($userId, $otherUserId);
        }

        if (!$conversationId) {
            header('Location: index.php?action=messaging');
            exit;
        }

        // Get conversation info
        $conversationInfo = $this->model->getConversationInfo($conversationId, $userId);
        if (!$conversationInfo) {
            header('Location: index.php?action=messaging');
            exit;
        }

        // Get messages
        $messages = $this->model->getConversationMessages($conversationId, $userId);

        // Mark messages as read
        $this->model->markMessagesAsRead($conversationId, $userId);

        // Get all conversations for sidebar
        $conversations = $this->model->getUserConversations($userId);
        $unreadCount = $this->model->getUnreadCount($userId);

        require_once 'view/MessagingConversationView.php';
    }

    /**
     * Send a message (AJAX endpoint)
     */
    public function sendMessage() {
        header('Content-Type: application/json');

        $userId = $_SESSION['user_id'];
        $conversationId = $_POST['conversation_id'] ?? null;
        $receiverId = $_POST['receiver_id'] ?? null;
        $content = trim($_POST['content'] ?? '');

        if (!$conversationId || !$receiverId || empty($content)) {
            echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
            exit;
        }

        $messageId = $this->model->sendMessage($conversationId, $userId, $receiverId, $content);

        if ($messageId) {
            echo json_encode([
                'success' => true,
                'message_id' => $messageId,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'envoi']);
        }
        exit;
    }

    /**
     * Get new messages (AJAX endpoint for polling)
     */
    public function getNewMessages() {
        header('Content-Type: application/json');

        $userId = $_SESSION['user_id'];
        $conversationId = $_GET['conversation_id'] ?? null;
        $lastMessageId = $_GET['last_message_id'] ?? 0;

        if (!$conversationId) {
            echo json_encode(['success' => false, 'error' => 'Conversation non spécifiée']);
            exit;
        }

        $db = Database::getDb();
        $stmt = $db->prepare("
            SELECT 
                m.id,
                m.sender_id,
                m.receiver_id,
                m.content,
                m.is_read,
                m.created_at,
                CONCAT(u.first_name, ' ', u.last_name) as sender_name
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.conversation_id = ? AND m.id > ?
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$conversationId, $lastMessageId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mark new messages as read
        if (count($messages) > 0) {
            $this->model->markMessagesAsRead($conversationId, $userId);
        }

        echo json_encode([
            'success' => true,
            'messages' => $messages
        ]);
        exit;
    }

    /**
     * Delete a conversation
     */
    public function deleteConversation() {
        $userId = $_SESSION['user_id'];
        $conversationId = $_POST['conversation_id'] ?? null;

        if ($conversationId) {
            $this->model->deleteConversation($conversationId, $userId);
        }

        header('Location: index.php?action=messaging');
        exit;
    }
}
