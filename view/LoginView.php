<?php
class LoginView {
    public function display_form(){
        ?>
        <div class="login-box">
            <h2>Se connecter</h2>
            <form action="?action=login">
                <input type="email" placeholder="Email" required />
                <input type="password" placeholder="Mot de passe" required />
                <div class="buttons">
                <a class="secondary" href="?action=register">Pas de compte ?</a>
                <button type="submit" class="primary">Se connecter</button>
                </div>
            </form>
        </div>
        <?php
    }
}