<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation avec <?= htmlspecialchars($conversationInfo['other_user_name']) ?></title>
    <link rel="stylesheet" href="./assets/styles/messaging-conversation.css">
    <link rel="stylesheet" href="./assets/styles/footer.css">
</head>
<body class="chat-page">
    <div class="chat-layout">
        <!-- Sidebar -->
        <div class="conversations-sidebar">
            <div class="sidebar-header">
                <a href="index.php?action=messaging" class="back-link">
                    <span>←</span> Conversations
                </a>
            </div>
            <div class="conversations-list">
                <?php foreach ($conversations as $conv): ?>
                    <a href="index.php?action=messaging_conversation&conversation_id=<?= $conv['id'] ?>" 
                       class="conversation-item <?= $conv['id'] == $conversationId ? 'active' : '' ?>">
                        <div class="conversation-item-name">
                            <span><?= htmlspecialchars($conv['other_user_name']) ?></span>
                            <?php if (isset($conv['unread_count']) && $conv['unread_count'] > 0 && $conv['id'] != $conversationId): ?>
                                <span class="unread-badge"><?= $conv['unread_count'] ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($conv['last_message']): ?>
                            <div class="conversation-item-preview">
                                <?= htmlspecialchars(mb_substr($conv['last_message'], 0, 40)) ?>
                                <?= mb_strlen($conv['last_message']) > 40 ? '...' : '' ?>
                            </div>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Chat -->
        <div class="chat-container">
            <div class="chat-header">
                <div class="chat-avatar">
                    <?= strtoupper(substr($conversationInfo['other_user_name'], 0, 2)) ?>
                </div>
                <div class="chat-user-name">
                    <?= htmlspecialchars($conversationInfo['other_user_name']) ?>
                </div>
            </div>

            <div class="chat-messages" id="chatMessages">
                <?php foreach ($messages as $message): ?>
                    <div class="message <?= $message['sender_id'] == $_SESSION['user_id'] ? 'sent' : 'received' ?>" 
                         data-message-id="<?= $message['id'] ?>">
                        <div class="message-avatar">
                            <?= strtoupper(substr($message['sender_name'], 0, 2)) ?>
                        </div>
                        <div class="message-content">
                            <div class="message-bubble">
                                <?= nl2br(htmlspecialchars($message['content'])) ?>
                            </div>
                            <div class="message-time">
                                <?php
                                $time = strtotime($message['created_at']);
                                $diff = time() - $time;
                                if ($diff < 60) {
                                    echo "À l'instant";
                                } elseif ($diff < 3600) {
                                    echo floor($diff / 60) . " min";
                                } elseif ($diff < 86400) {
                                    echo date('H:i', $time);
                                } else {
                                    echo date('d/m/Y H:i', $time);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="chat-input-container">
                <form class="chat-input-form" id="messageForm">
                    <textarea 
                        class="chat-input" 
                        id="messageInput" 
                        placeholder="Écrivez votre message..."
                        rows="1"
                        required
                    ></textarea>
                    <button type="submit" class="send-button" id="sendButton">Envoyer</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const conversationId = <?= $conversationId ?>;
        const receiverId = <?= $conversationInfo['other_user_id'] ?>;
        const currentUserId = <?= $_SESSION['user_id'] ?>;
        
        const chatMessages = document.getElementById('chatMessages');
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');

        // Auto-resize textarea on input
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Send message on Enter (Shift+Enter for new line)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (messageInput.value.trim().length > 0) {
                    messageForm.dispatchEvent(new Event('submit'));
                }
            }
        });

        // Send message via form submission
        messageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const content = messageInput.value.trim();
            if (!content) return;

            // Disable button and show loading state
            sendButton.disabled = true;
            const originalText = sendButton.textContent;
            sendButton.textContent = 'Envoi...';
            
            try {
                const formData = new FormData();
                formData.append('conversation_id', conversationId);
                formData.append('receiver_id', receiverId);
                formData.append('content', content);

                const response = await fetch('index.php?action=send_message', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    // Clear input and reset height
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                    
                    // Add message to chat
                    addMessageToChat(currentUserId, content, result.timestamp, true);
                    
                    // Focus back on input
                    messageInput.focus();
                } else {
                    console.error('Erreur serveur:', result.error);
                    showError('Erreur lors de l\'envoi du message');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Erreur lors de l\'envoi du message. Vérifiez votre connexion.');
            } finally {
                // Re-enable button
                sendButton.disabled = false;
                sendButton.textContent = originalText;
            }
        });

        // Add message to chat UI
        function addMessageToChat(senderId, content, timestamp, isSent) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
            
            const timeStr = 'À l\'instant';
            const initials = isSent ? 
                '<?= strtoupper(substr($_SESSION['first_name'] ?? 'U', 0, 1) . substr($_SESSION['last_name'] ?? 'U', 0, 1)) ?>' : 
                '<?= strtoupper(substr($conversationInfo['other_user_name'], 0, 2)) ?>';
            
            messageDiv.innerHTML = `
                <div class="message-avatar">${initials}</div>
                <div class="message-content">
                    <div class="message-bubble">${escapeHtml(content).replace(/\n/g, '<br>')}</div>
                    <div class="message-time">${timeStr}</div>
                </div>
            `;
            
            chatMessages.appendChild(messageDiv);
            scrollToBottom();
        }

        // Scroll to bottom of messages
        function scrollToBottom() {
            setTimeout(() => {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 0);
        }

        // Escape HTML for security
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Show error message
        function showError(message) {
            // Utiliser le système de notification existant s'il est disponible
            if (typeof showNotification === 'function') {
                showNotification(message, 'error');
            } else {
                alert(message);
            }
        }

        // Poll for new messages every 3 seconds
        let lastMessageId = <?= !empty($messages) ? end($messages)['id'] : 0 ?>;
        let pollingInterval;
        
        function startPolling() {
            pollingInterval = setInterval(async () => {
                try {
                    const response = await fetch(`index.php?action=get_new_messages&conversation_id=${conversationId}&last_message_id=${lastMessageId}`);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();

                    if (result.success && result.messages && result.messages.length > 0) {
                        result.messages.forEach(msg => {
                            const isSent = msg.sender_id == currentUserId;
                            addMessageToChat(msg.sender_id, msg.content, msg.created_at, isSent);
                            lastMessageId = Math.max(lastMessageId, msg.id);
                        });
                    }
                } catch (error) {
                    console.error('Erreur polling:', error);
                }
            }, 3000);
        }

        // Start polling on page load
        window.addEventListener('load', function() {
            scrollToBottom();
            startPolling();
        });

        // Clean up on page unload
        window.addEventListener('beforeunload', function() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
        });
    </script>
</body>
</html>
