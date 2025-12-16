<?php
require_once __DIR__ . "/../model/HomeModel.php";

class HomeController {

    public function render(): void {
        $model = new HomeModel();

        HomeView::render();
    }
}

?>