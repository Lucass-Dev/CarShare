<?php
require_once __DIR__ . "/../model/HomeModel.php";

class HomeController {

    public function index(): void {
        $model = new HomeModel();
<<<<<<< Updated upstream
        $hello = $model->getStrHelloWorld();

        require __DIR__ . "/../view/HomeView.php";
=======
        $topUsers = $model->getTopRatedUsers();

        HomeView::render($topUsers);
>>>>>>> Stashed changes
    }
}

?>