<?php
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
            echo "je suis ici";
            $checkForm = $this->registerModel->check_form_values($_POST);
            if ($checkForm["success"]) {
                $result = $this->registerModel->send_form($_POST);
                $this->registerView->display_result_message($result["message"], $result["success"]);
                header("Location: index.php?controller=login");
            }else{
                $this->registerView->display_result_message($checkForm["message"], $checkForm["success"]);
            }
        }

        ?>
        </div>
        <?php
        $_POST =[]; // Flush le $_POST pour éviter de garder en mémoire
    }
}