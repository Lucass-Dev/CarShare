<link rel="stylesheet" href="<?= asset('styles/page_profil.css') ?>">

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
            
            <a href="<?= url('index.php?action=logout') ?>" class="btn btn-logout-sidebar">Se déconnecter</a>
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

                <form method="POST" action="<?= url('index.php?action=profile') ?>" id="profileForm">
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

                <form method="POST" action="<?= url('index.php?action=profile') ?>" id="vehicleForm">
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

                <form method="POST" action="<?= url('index.php?action=profile') ?>" id="passwordForm" novalidate>
                    <input type="hidden" name="update_password" value="1">
                    
                    <div class="card-body">
                        <div class="password-info" id="passwordInfo">
                            <p class="text-muted">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="vertical-align: middle; margin-right: 8px;">
                                    <path d="M8 14A6 6 0 108 2a6 6 0 000 12z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M8 6v4M8 10v.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                Pour modifier votre mot de passe, nous vous enverrons un lien de confirmation par email.
                            </p>
                        </div>

                        <div class="password-fields" style="display: none;">
                            <div class="info-field full-width">
                                <label>Mot de passe actuel <span class="required">*</span></label>
                                <input type="password" name="current_password" class="form-input" placeholder="Votre mot de passe actuel" required>
                                <small class="field-help">Pour des raisons de sécurité, veuillez confirmer votre mot de passe actuel. Nous vous enverrons ensuite un email avec un lien sécurisé pour définir votre nouveau mot de passe.</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" style="display: none;">
                        <button type="submit" class="btn btn-primary">Envoyer le lien de modification</button>
                        <button type="button" class="btn btn-secondary" id="cancelPasswordBtn">Annuler</button>
                    </div>
                </form>
            </div>

            <!-- Delete Account Card - DANGER ZONE -->
            <div class="profile-card danger-card">
                <div class="card-header">
                    <h2>Zone de danger</h2>
                </div>

                <form method="POST" class="profile-form" id="deleteAccountForm">
                    <input type="hidden" name="delete_account" value="1">
                    
                    <div class="card-body">
                        <div class="danger-warning">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-right: 12px;">
                                <path d="M12 2L1 21h22L12 2zm0 6l1 8h-2l1-8zm0 10.5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5z" fill="#e74c3c"/>
                            </svg>
                            <div>
                                <h3>Supprimer mon compte</h3>
                                <p>Cette action est <strong>définitive et irréversible</strong>. Toutes vos données personnelles, vos trajets et vos réservations seront supprimés de manière permanente.</p>
                            </div>
                        </div>

                        <div class="delete-fields" style="display: none;">
                            <div class="info-field">
                                <label>Votre mot de passe <span class="required">*</span></label>
                                <input type="password" name="confirm_password" class="form-input" placeholder="Confirmez votre mot de passe" required>
                            </div>

                            <div class="info-field">
                                <label>Tapez "SUPPRIMER" pour confirmer <span class="required">*</span></label>
                                <input type="text" name="confirm_text" class="form-input" placeholder="SUPPRIMER" required>
                                <small class="field-help">Tapez le mot SUPPRIMER en majuscules pour confirmer la suppression.</small>
                            </div>

                            <div class="danger-warning" style="margin-top: 15px; background: #fff3cd; border-color: #ffc107;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" style="margin-right: 10px;">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#856404"/>
                                </svg>
                                <div>
                                    <p style="margin: 0; color: #856404;"><strong>Rappel :</strong> Un email de confirmation vous sera envoyé après la suppression de votre compte.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer-danger" style="display: none;">
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Je comprends, supprimer mon compte</button>
                        <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Custom Confirmation Modal -->
<div id="customModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L1 21h22L12 2zm0 6l1 8h-2l1-8zm0 10.5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5z" fill="#e74c3c"/>
            </svg>
        </div>
        <h3 class="modal-title">Dernière confirmation</h3>
        <p class="modal-message">Êtes-vous absolument sûr de vouloir supprimer définitivement votre compte ?</p>
        <div class="modal-warning">
            ⚠️ Cette action est <strong>irréversible</strong>. Toutes vos données seront perdues.
        </div>
        <div class="modal-actions">
            <button id="modalCancel" class="modal-btn modal-btn-cancel">Non, annuler</button>
            <button id="modalConfirm" class="modal-btn modal-btn-danger">Oui, supprimer définitivement</button>
        </div>
    </div>
</div>

<style>
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    animation: fadeIn 0.2s ease;
}

.modal-content {
    position: relative;
    background: white;
    border-radius: 16px;
    padding: 32px;
    max-width: 480px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
    text-align: center;
}

.modal-icon {
    margin: 0 auto 20px;
    animation: shake 0.5s ease;
}

.modal-title {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0 0 12px 0;
}

.modal-message {
    font-size: 16px;
    color: #555;
    line-height: 1.6;
    margin: 0 0 20px 0;
}

.modal-warning {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);
    border: 2px solid #e74c3c;
    border-radius: 10px;
    padding: 16px;
    margin: 0 0 24px 0;
    font-size: 14px;
    color: #721c24;
    line-height: 1.5;
}

.modal-warning strong {
    color: #e74c3c;
    font-weight: 700;
}

.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.modal-btn {
    flex: 1;
    padding: 14px 24px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-btn-cancel {
    background: #ecf0f1;
    color: #2c3e50;
}

.modal-btn-cancel:hover {
    background: #d5dbdd;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.modal-btn-danger {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    color: white;
}

.modal-btn-danger:hover {
    background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(231, 76, 60, 0.4);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes shake {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-5deg); }
    75% { transform: rotate(5deg); }
}

/* Simple notification toast */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #e74c3c;
    color: white;
    padding: 16px 24px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    z-index: 10001;
    animation: slideInRight 0.3s ease;
    font-size: 14px;
    max-width: 320px;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>

<script src="<?= asset('js/profile.js') ?>"></script>
<script src="<?= asset('js/universal-form-protection.js') ?>"></script>
<script>
// Custom modal functions
function showCustomModal() {
    return new Promise((resolve) => {
        const modal = document.getElementById('customModal');
        const confirmBtn = document.getElementById('modalConfirm');
        const cancelBtn = document.getElementById('modalCancel');
        
        modal.style.display = 'flex';
        
        const handleConfirm = () => {
            cleanup();
            resolve(true);
        };
        
        const handleCancel = () => {
            cleanup();
            resolve(false);
        };
        
        const cleanup = () => {
            modal.style.display = 'none';
            confirmBtn.removeEventListener('click', handleConfirm);
            cancelBtn.removeEventListener('click', handleCancel);
        };
        
        confirmBtn.addEventListener('click', handleConfirm);
        cancelBtn.addEventListener('click', handleCancel);
    });
}

function showToast(message, type = 'error') {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.background = type === 'error' ? '#e74c3c' : '#3498db';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideInRight 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Delete account confirmation
document.addEventListener('DOMContentLoaded', function() {
    const deleteAccountForm = document.getElementById('deleteAccountForm');
    const deleteFields = deleteAccountForm.querySelector('.delete-fields');
    const cardFooterDanger = deleteAccountForm.querySelector('.card-footer-danger');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    
    // Show delete confirmation fields
    deleteAccountForm.querySelector('.danger-warning h3').addEventListener('click', function() {
        deleteFields.style.display = 'block';
        cardFooterDanger.style.display = 'flex';
    });
    
    // Cancel deletion
    cancelDeleteBtn.addEventListener('click', function() {
        deleteFields.style.display = 'none';
        cardFooterDanger.style.display = 'none';
        deleteAccountForm.reset();
    });
    
    // Confirm deletion - show custom modal
    confirmDeleteBtn.addEventListener('click', async function() {
        const password = deleteAccountForm.querySelector('input[name="confirm_password"]').value;
        const confirmText = deleteAccountForm.querySelector('input[name="confirm_text"]').value;
        
        if (!password) {
            showToast('Veuillez entrer votre mot de passe.');
            return;
        }
        
        if (confirmText.toUpperCase() !== 'SUPPRIMER') {
            showToast('Veuillez taper "SUPPRIMER" pour confirmer.');
            return;
        }
        
        const confirmed = await showCustomModal();
        if (confirmed) {
            deleteAccountForm.submit();
        }
    });
});
</script>