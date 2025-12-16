<?php
if (!isset($driver) || !is_array($driver) || empty($driver['id'])) {
    header('Location: /CarShare/index.php?action=rating');
    exit;
}

