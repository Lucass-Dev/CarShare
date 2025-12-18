<?php
class RegisterView {

    public function display_result_message($message, $success){
        ?>
        <link rel="stylesheet" href="./assets/styles/register.css">
        <div class="error-message <?php echo $success == "true" ? "success" : "error" ?>">
            <p><?php echo $message;?></p>
        </div>
        <?php
    }

    public function display_form(){
        ?>        <link rel="stylesheet" href="./assets/styles/register.css">        
            <h2>S’inscrire</h2>
            <form controller="./?controller=register" method="POST">
                <input type="text" placeholder="Nom" name="first_name" required />
                <input type="text" placeholder="Prénom" name="last_name" required />
                <input type="email" placeholder="Email" name="mail" required />
                <input type="date" placeholder="Date de naissance" name="birthdate" required />
                <input type="password" placeholder="Mot de passe" name="pass" required />
                <input type="password" placeholder="Confirmation" name="confirm_pass" required />
                <div class="buttons">
                    <a class="secondary" href="?controller=login">Déjà un compte ?</a>
                    <button type="submit" class="primary" id="register-button">S’inscrire</button>
                </div>
            </form>
        <?php
    }
}
