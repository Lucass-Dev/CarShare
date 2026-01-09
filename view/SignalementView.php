<?php
if (!isset($userData) || !is_array($userData) || empty($userData['id'])) {
    header('Location: /index.php?controller=signalement');
    exit;
}
?>

