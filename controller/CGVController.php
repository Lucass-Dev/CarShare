<?php
class CGVController {
    public function index() {
        require_once __DIR__ . '/../view/CGVView.php';
        CGVView::render();
    }
}
?>
