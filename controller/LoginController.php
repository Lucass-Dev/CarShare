<?php
include_once(__DIR__."/../model/LoginModel.php");
include_once(__DIR__."/../view/LoginView.php");

class LoginController {

    private $loginModel;
    private $loginView;

    public function __construct() {
        $this->loginModel = new LoginModel();
        $this->loginView = new LoginView();
    }
    public function render(){
        $this->loginView->display_form();

        if (sizeof($_POST) > 0) {
            $this->loginModel->send_form($_POST);
        }
    }
}