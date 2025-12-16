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
        ?>
        <div class="register-box">
        <?php
        $this->registerView->display_form();
        if (sizeof($_POST) > 0) {
            if ($this->registerModel->check_form_values($_POST)) {
                $result = $this->registerModel->send_form($_POST);
                $this->registerView->display_result_message($result["message"], $result["success"]);
            }
        }

        ?>
        </div>
        <?php
        $_POST =[]; // Flush le $_POST pour éviter de garder en mémoire
    }
}