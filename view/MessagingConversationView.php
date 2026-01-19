<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation avec <?= htmlspecialchars($conversationInfo['other_user_name']) ?></title>
    <link rel="stylesheet" href="./assets/styles/footer.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f6f8fa;
            color: #24292f;
            line-height: 1.6;
            height: 100vh;
            overflow: hidden;
        }

        .chat-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            height: 100vh;
        }

        .conversations-sidebar {
            background: white;
            border-right: 1px solid #d0d7de;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 16px;
            border-bottom: 1px solid #d0d7de;
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
        }

        .sidebar-title {
            font-size: 16px;
            font-weight: 600;
            color: #24292f;
            margin-bottom: 8px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #0969da;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .conversation-item {
            padding: 12px 16px;
            border-bottom: 1px solid #d0d7de;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .conversation-item:hover {
            background: #f6f8fa;
        }

        .conversation-item.active {
            background: #ddf4ff;
            border-left: 3px solid #0969da;
        }

        .conversation-item-name {
            font-size: 14px;
            font-weight: 600;
            color: #24292f;
            margin-bottom: 4px;
        }

        .conversation-item-preview {
            font-size: 12px;
            color: #57606a;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background: white;
        }

        .chat-header {
            padding: 16px 24px;
            border-bottom: 1px solid #d0d7de;
            background: white;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .chat-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 600;
            color: white;
        }

        .chat-user-name {
            font-size: 16px;
            font-weight: 600;
            color: #24292f;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            background: #f6f8fa;
            min-height: 0;
        }

        .message {
            margin-bottom: 16px;
            display: flex;
            gap: 12px;
        }

        .message.sent {
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            color: white;
            flex-shrink: 0;
        }

        .message-content {
            max-width: 60%;
        }

        .message-bubble {
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 14px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: pre-wrap;
        }

        .message.received .message-bubble {
            background: white;
            border: 1px solid #d0d7de;
            color: #24292f;
        }

        .message.sent .message-bubble {
            background: #0969da;
            color: white;
        }

        .message-time {
            font-size: 11px;
            color: #57606a;
            margin-top: 4px;
            padding: 0 4px;
        }

        .message.sent .message-time {
            text-align: right;
        }

        .chat-input-container {
            padding: 16px 24px;
            background: white;
            flex-shrink: 0;
            border-top: 1px solid #d0d7de;
        }

        .chat-input-form {
            display: flex;
            gap: 12px;
        }

        .chat-input {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            resize: none;
            max-height: 120px;
        }

        .chat-input:focus {
            outline: none;
            border-color: #0969da;
            box-shadow: 0 0 0 3px rgba(9, 105, 218, 0.1);
        }

        .send-button {
            padding: 10px 20px;
            background: #0969da;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .send-button:hover {
            background: #0550ae;
        }

        .send-button:disabled {
            background: #d0d7de;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .chat-layout {
                grid-template-columns: 1fr;
            }

            .conversations-sidebar {
                display: none;
            }

            .message-content {
                max-width: 80%;
            }
        }
    </style>
</head>
<body>
    <div class="chat-layout">
        <!-- Sidebar -->
        <div class="conversations-sidebar">
            <div class="sidebar-header">
                <a href="index.php?action=messaging" class="back-link">
                    <span>←</span> Toutes les conversations
                </a>
            </div>
            <?php foreach ($conversations as $conv): ?>
                <a href="index.php?action=messaging_conversation&conversation_id=<?= $conv['id'] ?>" 
                   class="conversation-item <?= $conv['id'] == $conversationId ? 'active' : '' ?>">
                    <div class="conversation-item-name">
                        <?= htmlspecialchars($conv['other_user_name']) ?>
                        <?php if (isset($conv['unread_count']) && $conv['unread_count'] > 0 && $conv['id'] != $conversationId): ?>
                            <span style="background: #0969da; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px; margin-left: 6px;">
                                <?= $conv['unread_count'] ?>
                            </span>
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

        // Auto-resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Send message on Enter (Shift+Enter for new line)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                messageForm.dispatchEvent(new Event('submit'));
            }
        });

        // Send message
        messageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const content = messageInput.value.trim();
            if (!content) return;

            sendButton.disabled = true;
            
            try {
                const formData = new FormData();
                formData.append('conversation_id', conversationId);
                formData.append('receiver_id', receiverId);
                formData.append('content', content);

                const response = await fetch('index.php?action=send_message', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                    addMessageToChat(currentUserId, content, result.timestamp, true);
                } else {
                    showNotification('Erreur lors de l\'envoi du message', 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showNotification('Erreur lors de l\'envoi du message', 'error');
            }

            sendButton.disabled = false;
            messageInput.focus();
        });

        // Add message to chat
        function addMessageToChat(senderId, content, timestamp, isSent) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
            
            const timeStr = 'À l\'instant';
            const initials = isSent ? '<?= strtoupper(substr($_SESSION['first_name'] ?? 'U', 0, 1) . substr($_SESSION['last_name'] ?? 'U', 0, 1)) ?>' : 
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

        // Scroll to bottom
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Poll for new messages every 3 seconds
        let lastMessageId = <?= !empty($messages) ? end($messages)['id'] : 0 ?>;
        
        setInterval(async () => {
            try {
                const response = await fetch(`index.php?action=get_new_messages&conversation_id=${conversationId}&last_message_id=${lastMessageId}`);
                const result = await response.json();

                if (result.success && result.messages.length > 0) {
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

        // Initial scroll to bottom
        scrollToBottom();
    </script>
</body>
</html>
