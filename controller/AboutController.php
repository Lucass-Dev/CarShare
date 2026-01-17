<?php

class AboutController {
    
    public function index() {
        require __DIR__ . "/../view/AboutView.php";
        AboutView::render();
    }
    
    public function render() {
        $this->index();
    }
}
