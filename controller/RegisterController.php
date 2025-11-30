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

        if (sizeof($_POST) > 0) {
            print_r($_POST);
            if ($this->registerModel->check_form_values($_POST)) {
            }else{
                $this->registerView->display_error_message();
            }
        }

        //$_POST = array(); // Flush le $_POST pour éviter de garder en mémoire, à décommenter à la fin
    }
}