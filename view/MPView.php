<?php
/**
 * messaging.php
 * Design: Eliarisoa (100% du HTML/CSS/JS préservé)
 * Architecture: Lucas (fichier PHP direct)
 */

// Variables attendues: $conversations, $unreadCount

$success = $_SESSION['success'] ?? '';
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['success'], $_SESSION['error']);
?>

<?php require_once __DIR__ . '/components/header.php'; ?>

<div class="messaging-container">
            <div class="messaging-header">
                <h1>💬 Messages</h1>
                <?php if ($unreadCount > 0): ?>
                    <span class="unread-badge"><?= $unreadCount ?> non lu<?= $unreadCount > 1 ? 's' : '' ?></span>
                <?php endif; ?>
            </div>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= e($success) ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>
            
            <?php if (empty($conversations)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h2>Aucun message</h2>
                    <p>Vos conversations apparaîtront ici</p>
                </div>
            <?php else: ?>
                <div class="conversations-list">
                    <?php foreach ($conversations as $conv): ?>
                        <!-- DEEP MERGE: Utilise conv_id (architecture Lucas) au lieu de user_id -->
                        <a href="<?= url('index.php?controller=messaging&action=conversation&conv_id=' . $conv['conv_id']) ?>" 
                           class="conversation-item <?= $conv['unread_count'] > 0 ? 'unread' : '' ?>">
                            
                            <div class="conversation-avatar">
                                <?php if (!empty($conv['profile_picture'])): ?>
                                    <img src="<?= asset('img/' . e($conv['profile_picture'])) ?>" 
                                         alt="<?= e($conv['first_name']) ?>">
                                <?php else: ?>
                                    <div class="avatar-placeholder">
                                        <?= strtoupper(substr($conv['first_name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($conv['unread_count'] > 0): ?>
                                    <span class="avatar-badge"><?= $conv['unread_count'] ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="conversation-content">
                                <div class="conversation-header">
                                    <div class="conversation-name">
                                        <?= e($conv['first_name'] . ' ' . substr($conv['last_name'], 0, 1)) ?>.
                                    </div>
                                    <div class="conversation-time">
                                        <?php
                                        $time = strtotime($conv['last_message_time']);
                                        $now = time();
                                        $diff = $now - $time;
                                        
                                        if ($diff < 3600) {
                                            echo floor($diff / 60) . ' min';
                                        } elseif ($diff < 86400) {
                                            echo floor($diff / 3600) . 'h';
                                        } elseif ($diff < 604800) {
                                            echo floor($diff / 86400) . 'j';
                                        } else {
                                            echo strftime('%d/%m', $time);
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="conversation-preview">
                                    <?php if ($conv['sender_id'] == $_SESSION['user_id']): ?>
                                        <span class="you-label">Vous:</span>
                                    <?php endif; ?>
                                    <!-- DEEP MERGE: 'message' vient de pm.content AS message (compat) -->
                                    <?= e(mb_substr($conv['message'], 0, 80)) ?><?= mb_strlen($conv['message']) > 80 ? '...' : '' ?>
                                </div>
                            </div>
                            
                            <?php if ($conv['unread_count'] > 0): ?>
                                <div class="unread-indicator"></div>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <link rel="stylesheet" href="<?= asset('styles/messaging.css') ?>">
<?php require_once __DIR__ . '/components/footer.php'; ?>
