<?php
require_once __DIR__ . "/../model/FAQModel.php";

class FAQController {
    
    public function render() {
        require __DIR__ . "/../view/FAQView.php";
    }
}