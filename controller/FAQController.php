<?php
require_once __DIR__ . "/../model/FAQModel.php";

class FAQController {
    
    public function index() {
        require __DIR__ . "/../view/FAQView.php";
        FAQView::render();
    }
    
    public function render() {
        $this->index();
    }
}