<?php
    include(__DIR__."/../model/RegisterModel.php");
    include(__DIR__."/../view/RegisterView.php");
class RegisterController {
    private $registerModel;
    private $registerView;

    public function __construct() {
        $this->registerModel = new registerModel();
        $this->registerView = new registerView();
    }
    public function render(){
        $this->registerView->display_form();

        if ($_POST[""]) {
            # code...
        }
    }
}