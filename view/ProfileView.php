<?php
class ProfileView{

    static function displayHistory($history){
        ?>
        <div class="history-container">
            <h1 class="title">Mes trajets</h1>
        <section class="section-prochaines">
            <div class="col">
                <h2>En tant que conducteur</h2>
                <?php if (empty($history["incoming_provider_history"])): ?>
                    <p>Aucun trajet proposé</p>
                <?php else: ?>
                    <?php foreach ($history["incoming_provider_history"] as $trip => $incoming_provider_infos): ?>
                        <div class="trajet-card">
                            <div class="middle">
                                <span><?= htmlspecialchars($incoming_provider_infos['start_location']) ?></span>
                                <span class="fleche">→</span>
                                <span><?= htmlspecialchars($incoming_provider_infos['end_location']) ?></span>
                            </div>
                            <div class="right">
                                <small>Le <?= date('d/m/Y', strtotime($incoming_provider_infos['start_date'])) ?> à <?= date('H:i', strtotime($incoming_provider_infos['start_date'])) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <a href="index.php?controller=trip&action=publish" class="small-btn">Publier un trajet +</a>
            </div>

            <div class="col">
                <h2>En tant que voyageur</h2>
                <?php if (empty($history["incoming_booker_history"])): ?>
                    <p>Aucune réservation</p>
                <?php else: ?>
                    <?php foreach ($history["incoming_booker_history"] as $trip => $incoming_booker_infos): ?>
                        <div class="trajet-card">
                            <div class="left">
                                <div class="avatar"><img src="<?php echo $incoming_booker_infos["profile_picture_path"]?>"/></div>
                                <span class="nom"><?= htmlspecialchars($incoming_booker_infos['first_name']) ?></span>
                            </div>
                            <div class="middle">
                                <span><?= htmlspecialchars($incoming_booker_infos['start_location']) ?></span>
                                <span class="fleche">→</span>
                                <span><?= htmlspecialchars($incoming_booker_infos['end_location']) ?></span>
                            </div>
                            <div class="right">
                                <small>Le <?= date('d/m/Y', strtotime($incoming_booker_infos['start_date'])) ?> à <?= date('H:i', strtotime($incoming_booker_infos['start_date'])) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <a href="index.php?controller=trip&action=search" class="small-btn">Réserver un trajet</a>
            </div>
        </section>

        <hr class="bar-horizontal">

        <section class="section-historique">
            <div class="col">
                <h2>Historique - Conducteur</h2>
                <?php foreach ($history["provider_history"] as $trip => $tripInfos): ?>
                    <div class="trajet-card">
                        <div class="middle">
                            <span><?= htmlspecialchars($tripInfos['start_location']) ?></span>
                            <span class="fleche">→</span>
                            <span><?= htmlspecialchars($tripInfos['end_location']) ?></span>
                        </div>
                        <div class="right">
                            <small>Le <?= date('d/m/Y', strtotime($tripInfos['start_date'])) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col">
                <h2>Historique - Voyageur</h2>
                <?php foreach ($history["booker_history"] as $trip => $bookingInfos): ?>
                    <div class="trajet-card">
                        <div class="left">
                            <div class="avatar"><img src="<?php echo $bookingInfos['profile_picture_path']?>"/> </div>
                            <span class="nom"><?= htmlspecialchars($bookingInfos['first_name']) ?></span>
                        </div>
                        <div class="middle">
                            <span><?= htmlspecialchars($bookingInfos['start_location']) ?></span>
                            <span class="fleche">→</span>
                            <span><?= htmlspecialchars($bookingInfos['end_location']) ?></span>
                        </div>
                        <div class="right">
                            <small>Le <?= date('d/m/Y', strtotime($bookingInfos['start_date'])) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        </div>
    <?php
    }

    static function displayProfile($user){
        ?>
        <div class="profile-view-container" >
            <form class="profile-card" action="index.php?controller=profile&action=update_user" method="POST" id="update_user_form">
            <input type="number" value="<?php echo $user->id?>" name="user_id" hidden>
            <h2>Résumé du profil</h2>

            <div class="profile-top">
                <div class="profile-top-left">
                    <label class="profile-label">Prénom</label>
                    <input class="profile-input" type="text" value="<?php echo $user->first_name?>" name="first_name">

                    <label class="profile-label">Nom</label>
                    <input class="profile-input" type="text" value="<?php echo $user->last_name?>" name="last_name">
                </div>
                <div class="profile-top-right">
                    <label class="profile-label">Mail</label>
                    <input class="profile-input" type="email" value="<?php echo $user->email?>" name="email">

                    <label class="profile-label">Date de naissance</label>
                    <input class="profile-input" type="date" value="<?php echo $user->birth_date?>" name="birth_date">

                </div>
            </div>
            
            <label for="pass" class="profile-label">Changer de mot de passe</label>
            <input id="pass" class="profile-input" type="password" placeholder="Nouveau mot de passe" name="pass">
            <input id="confirm_pass" class="profile-input" type="password" placeholder="Confirmez le mot de passe" name="confirm_pass">

            <span id="change_profile_msg" style="display:none;"></span>
            <button class="btn" type="button" id="profile-button" onclick="check_update_profile_values()">
                Modifier
            </button>
            <?php
                    if (isset($_GET["profile_update"]) && $_GET["profile_update"]=="1") {
                        ?>
                        <div class="car-success">
                            <span>Profile mis à jour !</span>
                        </div>
                        <?php
                    }
                ?>
        </form>
        <form class="profile-card" id="update-car-form" method="POST" action="index.php?controller=profile&action=update_car">
            <h2>Mon véhicule</h2>

            <div class="profile-top" action="index.php?controller=profile&action=update_vehicle">
                <div class="profile-top-left">
                    <label class="profile-label">Marque</label>
                    <input class="profile-input" type="text" value="<?php echo $user->car_brand?>" name="car_brand" id="car_brand">

                    <label class="profile-label">Modèle</label>
                    <input class="profile-input" type="text" value="<?php echo $user->car_model?>" name="car_model" id="car_model">
                </div>

                <div class="profile-top-right">
                    <label class="profile-label">Année</label>
                    <input class="profile-input" type="number" min="1900" max="2026" step="1" value="<?php echo $user->car_year?>" name="car_year" id="car_year">

                    <label class="profile-label">Crit'Air</label>
                    <input class="profile-input" type="number" type="number" min="1" max="5" step="1" value="<?php echo $user->car_crit_air?>" name="car_crit_air" id="car_crit_air">
                </div>
            </div>
                <label class="profile-label">Plaque d'immatriculation</label>
                <input class="profile-input" type="text" value="<?php echo $user->car_plate?>" name="car_plate" id="car_plate" >

                <button class="btn" type="button" id="car-button" onclick="check_update_car_values()">
                    Modifier
                </button>
                <?php
                    if (isset($_GET["car_update"]) && $_GET["car_update"]=="1") {
                        ?>
                        <div class="car-success">
                            <span>Véhicule mis à jour !</span>
                        </div>
                        <?php
                    }
                ?>
            </div>
    </form>
</div>
<?php
    }

    static function displayLogin(){
         ?>
        <div class="login-box">
            <h2>Se connecter</h2>
            <form controller="?controller=profile" method="POST">
                <input name="email" type="email" placeholder="Email" required />
                <input name="password" type="password" placeholder="Mot de passe" required />
                <div class="buttons">
                <a class="secondary" href="?controller=profile&action=register">Pas de compte ?</a>
                <button type="submit" class="primary">Se connecter</button>
                </div>
            </form>
        </div>
        <?php
    }

    static function displayRegister($message, $success){
         ?>
            
            <form controller="./?controller=profile&action=register" method="POST" class="register-box">
                <h2>S'inscrire</h2>    
                <input type="text" placeholder="Nom" name="first_name" required value="<?php echo (!$success ? $_POST["first_name"] : "") ?>"/>
                <input type="text" placeholder="Prénom" name="last_name" required value="<?php echo (!$success ? $_POST["last_name"] : "") ?>"/>
                <input type="email" placeholder="Email" name="mail" required value="<?php echo (!$success ? $_POST["mail"] : "") ?>"/>
                <input type="date" placeholder="Date de naissance" name="birthdate" required value="<?php echo (!$success ? $_POST["birthdate"] : "") ?>"/>
                <input type="password" placeholder="Mot de passe" name="pass" required />
                <input type="password" placeholder="Confirmation" name="confirm_pass" required />
                <div class="buttons">
                    <a class="secondary" href="?controller=profile&action=login">Déjà un compte ?</a>
                    <button type="submit" class="primary" id="register-button">S'inscrire</button>
                </div>
                <?php ProfileView::display_result_message($message, $success); ?>
            </form>
        <?php
    }

    static public function display_result_message($message, $success){
        ?>
        <div class="error-message <?php echo $success == "true" ? "success" : "error" ?>">
            <p><?php echo $message;?></p>
        </div>
        <?php
    }
}