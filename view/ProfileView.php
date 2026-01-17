<link rel="stylesheet" href="/CarShare/assets/styles/page_profil.css">

<main class="profile-container">
    <?php if (isset($error)): ?>
        <div class="message-box message-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="message-box message-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div class="profile-layout">
        <!-- Sidebar -->
        <aside class="profile-sidebar">
            <div class="profile-avatar-container">
                <?php if (!empty($user['profile_photo'])): ?>
                    <img src="<?= htmlspecialchars($user['profile_photo']) ?>" alt="Photo de profil" class="profile-avatar">
                <?php else: ?>
                    <div class="profile-avatar profile-avatar-default">
                        <span><?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? 'U', 0, 1)) ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="profile-identity">
                <h1 class="profile-name"><?= htmlspecialchars($user['first_name'] ?? '') ?> <?= htmlspecialchars($user['last_name'] ?? '') ?></h1>
                <p class="profile-email"><?= htmlspecialchars($user['email'] ?? '') ?></p>
            </div>
            
            <a href="/CarShare/index.php?action=logout" class="btn btn-logout-sidebar">Se déconnecter</a>
        </aside>

        <!-- Main Content -->
        <div class="profile-main">
            <!-- Personal Information Card -->
            <div class="profile-card">
                <div class="card-header">
                    <h2>Informations personnelles</h2>
                    <button type="button" class="btn btn-edit" id="editProfileBtn">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M10.586 1.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM8.586 3.586L1 11.172V14h2.828l7.586-7.586L8.586 3.586z" fill="currentColor"/>
                        </svg>
                        Modifier
                    </button>
                </div>

                <form method="POST" action="/CarShare/index.php?action=profile" id="profileForm">
                    <input type="hidden" name="update_profile" value="1">
                    
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-field">
                                <label>Prénom</label>
                                <div class="info-display" data-field="first_name"><?= htmlspecialchars($user['first_name'] ?? '') ?></div>
                                <input type="text" name="first_name" class="form-input" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required style="display: none;">
                            </div>

                            <div class="info-field">
                                <label>Nom</label>
                                <div class="info-display" data-field="last_name"><?= htmlspecialchars($user['last_name'] ?? '') ?></div>
                                <input type="text" name="last_name" class="form-input" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required style="display: none;">
                            </div>

                            <div class="info-field full-width">
                                <label>Adresse email</label>
                                <div class="info-display" data-field="email"><?= htmlspecialchars($user['email'] ?? '') ?></div>
                                <input type="email" name="email" class="form-input" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required style="display: none;">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" style="display: none;">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        <button type="button" class="btn btn-secondary" id="cancelBtn">Annuler</button>
                    </div>
                </form>
            </div>

            <!-- Vehicle Information Card -->
            <div class="profile-card">
                <div class="card-header">
                    <h2>Véhicule</h2>
                    <button type="button" class="btn btn-edit" id="editVehicleBtn">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M10.586 1.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM8.586 3.586L1 11.172V14h2.828l7.586-7.586L8.586 3.586z" fill="currentColor"/>
                        </svg>
                        Modifier
                    </button>
                </div>

                <form method="POST" action="/CarShare/index.php?action=profile" id="vehicleForm">
                    <input type="hidden" name="update_vehicle" value="1">
                    
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-field">
                                <label>Marque</label>
                                <div class="info-display" data-field="car_brand"><?= !empty($user['car_brand']) ? htmlspecialchars($user['car_brand']) : '<span class="text-muted">Non renseigné</span>' ?></div>
                                <input type="text" name="car_brand" class="form-input" value="<?= htmlspecialchars($user['car_brand'] ?? '') ?>" style="display: none;">
                            </div>

                            <div class="info-field">
                                <label>Modèle</label>
                                <div class="info-display" data-field="car_model"><?= !empty($user['car_model']) ? htmlspecialchars($user['car_model']) : '<span class="text-muted">Non renseigné</span>' ?></div>
                                <input type="text" name="car_model" class="form-input" value="<?= htmlspecialchars($user['car_model'] ?? '') ?>" style="display: none;">
                            </div>

                            <div class="info-field full-width">
                                <label>Plaque d'immatriculation</label>
                                <div class="info-display license-plate" data-field="car_plate"><?= !empty($user['car_plate']) ? htmlspecialchars($user['car_plate']) : '<span class="text-muted">Non renseigné</span>' ?></div>
                                <input type="text" name="car_plate" class="form-input" value="<?= htmlspecialchars($user['car_plate'] ?? '') ?>" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" style="display: none;">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        <button type="button" class="btn btn-secondary" id="cancelVehicleBtn">Annuler</button>
                    </div>
                </form>
            </div>

            <!-- Security / Password Card -->
            <div class="profile-card">
                <div class="card-header">
                    <h2>Sécurité</h2>
                    <button type="button" class="btn btn-edit" id="editPasswordBtn">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M10.586 1.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM8.586 3.586L1 11.172V14h2.828l7.586-7.586L8.586 3.586z" fill="currentColor"/>
                        </svg>
                        Modifier le mot de passe
                    </button>
                </div>

                <form method="POST" action="/CarShare/index.php?action=profile" id="passwordForm" novalidate>
                    <input type="hidden" name="update_password" value="1">
                    
                    <div class="card-body">
                        <div class="password-info" id="passwordInfo">
                            <p class="text-muted">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="vertical-align: middle; margin-right: 8px;">
                                    <path d="M8 14A6 6 0 108 2a6 6 0 000 12z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M8 6v4M8 10v.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                Dernière modification : Jamais modifié
                            </p>
                        </div>

                        <div class="password-fields" style="display: none;">
                            <div class="info-field full-width">
                                <label>Mot de passe actuel <span class="required">*</span></label>
                                <input type="password" name="current_password" class="form-input" placeholder="Votre mot de passe actuel" required>
                                <small class="field-help">Pour des raisons de sécurité, veuillez confirmer votre mot de passe actuel</small>
                            </div>

                            <div class="info-field full-width">
                                <label>Nouveau mot de passe <span class="required">*</span></label>
                                <input type="password" name="new_password" class="form-input" placeholder="Minimum 12 caractères" minlength="12" required>
                                <div class="password-strength-meter">
                                    <div class="strength-bar">
                                        <div class="strength-bar-fill"></div>
                                    </div>
                                    <div class="strength-text"></div>
                                    <ul class="strength-requirements">
                                        <li data-req="length">Au moins 12 caractères</li>
                                        <li data-req="uppercase">Une majuscule</li>
                                        <li data-req="lowercase">Une minuscule</li>
                                        <li data-req="number">Un chiffre</li>
                                        <li data-req="special">Un caractère spécial</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="info-field full-width">
                                <label>Confirmer le nouveau mot de passe <span class="required">*</span></label>
                                <input type="password" name="confirm_new_password" class="form-input" placeholder="Retapez le nouveau mot de passe" required>
                                <div class="password-match-indicator"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" style="display: none;">
                        <button type="submit" class="btn btn-primary">Modifier le mot de passe</button>
                        <button type="button" class="btn btn-secondary" id="cancelPasswordBtn">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="/CarShare/assets/js/profile.js"></script>