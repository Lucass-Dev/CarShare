<?php
class RegisterView {
    public function display_form(){
        ?>
        <div class="login-box">
            <h2>S’inscrire</h2>
            <form action="?action=register">
                <input type="text" placeholder="Nom" required />
                <input type="text" placeholder="Prénom" required />
                <input type="email" placeholder="Email" required />
                <input type="date" placeholder="Date de naissance" required />
                <input type="password" placeholder="Mot de passe" required />
                <input type="password" placeholder="Confirmation" required />

                <div class="gender-section">
                <label>Sexe</label>
                <div class="gender-options">
                    <label><input type="radio" name="sexe" value="M" /> M ♂</label>
                    <label><input type="radio" name="sexe" value="F" /> F ♀</label>
                    <label><input type="radio" name="sexe" value="X" /> X ⚪</label>
                </div>
                </div>

                <div class="buttons">
                    <a class="secondary" href="?action=connexion">Déjà un compte ?</a>
                    <button type="submit" class="primary">S’inscrire</button>
                </div>
            </form>
        </div>
        <?php
    }
}