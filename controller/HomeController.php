<?php
require_once __DIR__ . "/../model/HomeModel.php";
require_once __DIR__ . "/../view/HomeView.php";

class HomeController {

    public function index(): void {
        $model = new HomeModel();
        $topUsers = $model->getTopRatedUsers();

        HomeView::render($topUsers);
    }
}

?>