<?php
if (!isset($userData) || !is_array($userData) || empty($userData['id'])) {
    header('Location: /CarShare/index.php?action=signalement');
    exit;
}
?>

