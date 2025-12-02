<?php
class LoginView {
    public function display_form(){
        ?>
        <div class="login-box">
            <h2>Se connecter</h2>
            <form action="?action=login" method="POST">
                <input name="email" type="email" placeholder="Email" required />
                <input name="password" type="password" placeholder="Mot de passe" required />
                <div class="buttons">
                <a class="secondary" href="?action=register">Pas de compte ?</a>
                <button type="submit" class="primary">Se connecter</button>
                </div>
            </form>
        </div>
        <?php
    }

    public function display_error_message($message){
        ?>
        <div class="error-message">
            <p><?php echo $message;?></p>
        </div>
        <?php
    }
}