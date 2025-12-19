<div class="login-box">
  <h2>S'inscrire</h2>
  
  <?php if (isset($error)): ?>
    <div class="error-message" style="color: red; margin-bottom: 15px;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>
  
  <form method="POST" action="/CarShare/index.php?action=register">
    <input type="text" name="last_name" placeholder="Nom" required />
    <input type="text" name="first_name" placeholder="Prénom" required />
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Mot de passe" required />
    <input type="password" name="confirm_password" placeholder="Confirmation" required />

<<<<<<< Updated upstream
    <div class="buttons">
      <a class="secondary" href="/CarShare/index.php?action=login">Déjà un compte ?</a>
      <button type="submit" class="primary">S'inscrire</button>
    </div>
  </form>
</div>
=======
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

                <div class="gender-section">
                <label>Sexe</label>
                <div class="gender-options">
                    <div><input type="radio" name="sexe" value="M" id="M"/><label for="M">M ♂</label></div>
                    <div><input type="radio" name="sexe" value="F" id="F"/><label for="F">F ♀</label></div>
                    <div><input type="radio" name="sexe" value="X" id="X"/><label for="X">X ⚪</label></div>
                </div>
                </div>

                <div class="buttons">
                    <a class="secondary" href="?controller=login">Déjà un compte ?</a>
                    <button type="submit" class="primary" id="register-button">S’inscrire</button>
                </div>
            </form>
        <?php
    }
}
>>>>>>> Stashed changes
