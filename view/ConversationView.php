<?php
/**
 * conversation.php
 * Design: Eliarisoa (100% du HTML/CSS/JS préservé)
 * Architecture: Lucas (fichier PHP direct)
 */

// Variables attendues: $messages, $otherUser

$success = $_SESSION['success'] ?? '';
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['success'], $_SESSION['error']);
        
        $currentUserId = $_SESSION['user_id'];
?>

<?php require_once __DIR__ . '/components/header.php'; ?>

<div class="conversation-container">
            <!-- Header -->
            <div class="conversation-header">
                <a href="<?= url('index.php?controller=messaging') ?>" class="back-btn">
                    ← Retour
                </a>
                
                <div class="header-user">
                    <div class="header-avatar">
                        <?php if (!empty($otherUser['profile_picture'])): ?>
                            <img src="<?= asset('img/' . e($otherUser['profile_picture'])) ?>" 
                                 alt="<?= e($otherUser['first_name']) ?>">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <?= strtoupper(substr($otherUser['first_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="header-name">
                        <?= e($otherUser['first_name'] . ' ' . substr($otherUser['last_name'], 0, 1)) ?>.
                    </div>
                </div>
                
                <!-- DEEP MERGE: conv_id pour suppression (architecture Lucas) -->
                <form method="POST" action="<?= url('index.php?controller=messaging&action=delete') ?>" 
                      onsubmit="return confirm('Supprimer cette conversation ?')">
                    <input type="hidden" name="conv_id" value="<?= $conv_id ?>">
                    <button type="submit" class="delete-btn" title="Supprimer la conversation">
                        🗑️
                    </button>
                </form>
            </div>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= e($success) ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>
            
            <!-- Messages -->
            <div class="messages-container" id="messagesContainer">
                <?php if (empty($messages)): ?>
                    <div class="no-messages">
                        <p>Aucun message. Envoyez le premier message !</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($messages as $message): 
                        $isOwn = $message['sender_id'] == $currentUserId;
                    ?>
                        <div class="message-wrapper <?= $isOwn ? 'own' : 'other' ?>">
                            <?php if (!$isOwn): ?>
                                <div class="message-avatar">
                                    <?php if (!empty($message['profile_picture'])): ?>
                                        <img src="<?= asset('img/' . e($message['profile_picture'])) ?>" 
                                             alt="<?= e($message['first_name']) ?>">
                                    <?php else: ?>
                                        <div class="avatar-placeholder-small">
                                            <?= strtoupper(substr($message['first_name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="message-bubble">
                                <!-- DEEP MERGE: 'content' au lieu de 'message' (architecture Lucas) -->
                                <div class="message-text"><?= nl2br(e($message['content'])) ?></div>
                                <div class="message-time">
                                    <?= strftime('%d/%m/%Y à %H:%M', strtotime($message['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Send Form -->
            <div class="send-form">
                <!-- DEEP MERGE: conv_id au lieu de recipient_id (architecture Lucas) -->
                <form method="POST" action="<?= url('index.php?controller=messaging&action=send') ?>" id="messageForm">
                    <input type="hidden" name="conv_id" value="<?= $conv_id ?>">
                    
                    <textarea name="message" 
                              placeholder="Écrivez votre message..." 
                              rows="2"
                              maxlength="1000"
                              required
                              id="messageInput"></textarea>
                    
                    <button type="submit" class="send-btn">
                        📤 Envoyer
                    </button>
                </form>
            </div>
        </div>
    <link rel="stylesheet" href="<?= asset('styles/messaging.css') ?>">
<script>
            // Auto-scroll to bottom
            const messagesContainer = document.getElementById('messagesContainer');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            
            // Auto-resize textarea
            const messageInput = document.getElementById('messageInput');
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            
            // Submit on Enter (Shift+Enter for newline)
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    document.getElementById('messageForm').submit();
                }
            });
        </script>

<?php require_once __DIR__ . '/components/footer.php'; ?>
