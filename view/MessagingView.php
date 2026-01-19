<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
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
        }

        .messaging-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #24292f;
            margin-bottom: 8px;
        }

        .conversations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 16px;
        }

        .conversation-card {
            background: white;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .conversation-card:hover {
            border-color: #0969da;
            box-shadow: 0 3px 8px rgba(9, 105, 218, 0.1);
        }

        .conversation-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .conversation-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 600;
            color: white;
            flex-shrink: 0;
        }

        .conversation-info {
            flex: 1;
            min-width: 0;
        }

        .conversation-name {
            font-size: 15px;
            font-weight: 600;
            color: #24292f;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .conversation-time {
            font-size: 12px;
            color: #57606a;
        }

        .conversation-preview {
            font-size: 13px;
            color: #57606a;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .unread-badge {
            background: #0969da;
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 12px;
            display: inline-block;
            margin-left: auto;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border: 1px solid #d0d7de;
            border-radius: 6px;
        }

        .empty-state-icon {
            font-size: 48px;
            color: #d0d7de;
            margin-bottom: 16px;
        }

        .empty-state-title {
            font-size: 18px;
            font-weight: 600;
            color: #24292f;
            margin-bottom: 8px;
        }

        .empty-state-text {
            font-size: 14px;
            color: #57606a;
        }
    </style>
</head>
<body>
    <div class="messaging-container">
        <div class="page-header">
            <h1 class="page-title">Messages</h1>
        </div>

        <?php if (empty($conversations)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">💬</div>
                <h2 class="empty-state-title">Aucune conversation</h2>
                <p class="empty-state-text">Vous n'avez pas encore de messages. Commencez une conversation depuis un profil utilisateur.</p>
            </div>
        <?php else: ?>
            <div class="conversations-grid">
                <?php foreach ($conversations as $conv): ?>
                    <a href="index.php?action=messaging_conversation&conversation_id=<?= $conv['id'] ?>" class="conversation-card">
                        <div class="conversation-header">
                            <div class="conversation-avatar">
                                <?= strtoupper(substr($conv['other_user_name'], 0, 2)) ?>
                            </div>
                            <div class="conversation-info">
                                <div class="conversation-name">
                                    <?= htmlspecialchars($conv['other_user_name']) ?>
                                    <?php if ($conv['unread_count'] > 0): ?>
                                        <span class="unread-badge"><?= $conv['unread_count'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="conversation-time">
                                    <?php
                                    if ($conv['last_message_time']) {
                                        $time = strtotime($conv['last_message_time']);
                                        $diff = time() - $time;
                                        if ($diff < 3600) {
                                            echo "Il y a " . floor($diff / 60) . " min";
                                        } elseif ($diff < 86400) {
                                            echo "Il y a " . floor($diff / 3600) . " h";
                                        } else {
                                            echo date('d/m/Y', $time);
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($conv['last_message']): ?>
                            <div class="conversation-preview">
                                <?= htmlspecialchars(mb_substr($conv['last_message'], 0, 80)) ?>
                                <?= mb_strlen($conv['last_message']) > 80 ? '...' : '' ?>
                            </div>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
