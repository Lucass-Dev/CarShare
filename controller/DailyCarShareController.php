<?php
require_once __DIR__ . '/../view/DailyCarShareView.php';

class DailyCarShareController {
    
    public function render(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        DailyCarShareView::render();
    }
}
