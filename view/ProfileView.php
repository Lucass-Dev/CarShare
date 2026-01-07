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
        </form>
        <form class="profile-card" method="POST">
            <h2>Mon véhicule</h2>

            <div class="profile-top" action="index.php?controller=profile&action=update_vehicle">
                <div class="profile-top-left">
                    <label class="profile-label">Marque</label>
                    <input class="profile-input" type="text" value="<?php echo $user->car_brand?>" name="car_brand">

                    <label class="profile-label">Modèle</label>
                    <input class="profile-input" type="text" value="<?php echo $user->car_model?>" name="car_model">
                </div>

                <div class="profile-top-right">
                    <label class="profile-label">Année</label>
                    <input class="profile-input" type="text" value="<?php echo $user->car_year?>" name="car_year">

                    <label class="profile-label">Crit'Air</label>
                    <input class="profile-input" type="text" value="<?php echo $user->car_crit_air?>" name="car_crit_air">
                </div>
            </div>
                <label class="profile-label">Plaque d'immatriculation</label>
                <input class="profile-input" type="text" value="<?php echo $user->car_plate?>" name="car_plate">

                <button type="submit" class="btn">
                    Modifier
                </button>
            </div>
    </form>
        <?php
    }
}

?>