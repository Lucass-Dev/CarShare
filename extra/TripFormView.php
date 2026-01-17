<?php $success = $success ?? false; ?>
<link rel="stylesheet" href="/CarShare/assets/styles/trip-form-modern.css">

<div class="trip-publish-container">
  <!-- Hero Section with Visual -->
  <div class="trip-hero">
    <div class="trip-hero-content">
      <div class="trip-hero-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </svg>
        <span>Publication de trajet</span>
      </div>
      <h1 class="trip-hero-title">Partagez votre trajet</h1>
      <p class="trip-hero-subtitle">Réduisez vos coûts de transport et votre empreinte carbone en partageant votre trajet avec d'autres voyageurs</p>
    </div>
    
    <div class="trip-hero-visual">
      <div class="visual-card visual-card-1">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 6v6l4 2"/>
        </svg>
        <span>Rapide</span>
      </div>
      <div class="visual-card visual-card-2">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5"/>
        </svg>
        <span>Économique</span>
      </div>
      <div class="visual-card visual-card-3">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M3 3h18v18H3zM3 9h18M9 21V9"/>
        </svg>
        <span>Écologique</span>
      </div>
    </div>
  </div>

  <?php if ($success): ?>
    <div class="alert alert-success">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
        <polyline points="22 4 12 14.01 9 11.01"/>
      </svg>
      <span>Trajet créé avec succès ! Il est maintenant visible par tous les utilisateurs.</span>
    </div>
  <?php endif; ?>

  <?php 
  $errors = $_SESSION['trip_form_errors'] ?? [];
  $formData = $_SESSION['trip_form_data'] ?? [];
  if (!empty($errors)): 
  ?>
    <div class="alert alert-error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <div>
        <strong>Erreurs détectées :</strong>
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  <?php 
    unset($_SESSION['trip_form_errors']);
  endif; 
  ?>

  <div class="trip-form-wrapper">
    <!-- Tips Banner -->
    <div class="tips-banner">
      <div class="tips-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 16v-4M12 8h.01"/>
        </svg>
      </div>
      <div class="tips-content">
        <strong>Conseils pour une publication réussie</strong>
        <p>Soyez précis sur vos horaires et votre itinéraire. Plus votre annonce est détaillée, plus vous avez de chances de trouver des passagers.</p>
      </div>
    </div>

    <!-- Progress Steps -->
    <div class="progress-steps">
      <div class="step active" data-step="1">
        <div class="step-number">1</div>
        <div class="step-label">Itinéraire</div>
      </div>
      <div class="step-connector"></div>
      <div class="step" data-step="2">
        <div class="step-number">2</div>
        <div class="step-label">Horaires & Prix</div>
      </div>
      <div class="step-connector"></div>
      <div class="step" data-step="3">
        <div class="step-number">3</div>
        <div class="step-label">Options</div>
      </div>
    </div>

    <form class="trip-form-modern" method="POST" action="/CarShare/index.php?action=create_trip_submit" novalidate>
      
      <!-- Section 1: Départ -->
      <div class="form-section" data-section="1">
        <div class="section-header">
          <div class="section-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="10" r="3"/>
              <path d="M12 2a8 8 0 00-8 8.2C4 15.5 12 22 12 22s8-6.5 8-11.8A8 8 0 0012 2z"/>
            </svg>
          </div>
          <div>
            <h2>Point de départ</h2>
            <p>Où commencera votre trajet ?</p>
          </div>
        </div>

        <div class="form-grid">
          <div class="form-field form-field-small">
            <label for="dep-num">N° voie</label>
            <input 
              id="dep-num" 
              name="dep-num"
              class="form-input" 
              placeholder="Ex: 10"
              value="<?= htmlspecialchars($formData['dep-num'] ?? '') ?>"
            />
          </div>

          <div class="form-field">
            <label for="dep-street">Rue</label>
            <input 
              id="dep-street" 
              name="dep-street"
              class="form-input" 
              placeholder="Ex: Rue de la République"
              value="<?= htmlspecialchars($formData['dep-street'] ?? '') ?>"
            />
          </div>

          <div class="form-field form-field-full">
            <label for="dep-city">Ville de départ <span class="required">*</span></label>
            <input 
              id="dep-city" 
              name="dep-city"
              class="form-input city-autocomplete" 
              placeholder="Recherchez une ville en France"
              value="<?= htmlspecialchars($formData['dep-city'] ?? '') ?>"
              autocomplete="off"
              required
            />
            <div class="autocomplete-dropdown" id="dep-city-dropdown"></div>
          </div>
        </div>
      </div>

      <div class="section-divider">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="12" y1="5" x2="12" y2="19"/>
          <polyline points="19 12 12 19 5 12"/>
        </svg>
      </div>

      <!-- Section 2: Arrivée -->
      <div class="form-section" data-section="1">
        <div class="section-header">
          <div class="section-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
              <circle cx="12" cy="10" r="3"/>
            </svg>
          </div>
          <div>
            <h2>Point d'arrivée</h2>
            <p>Où se terminera votre trajet ?</p>
          </div>
        </div>

        <div class="form-grid">
          <div class="form-field form-field-small">
            <label for="arr-num">N° voie</label>
            <input 
              id="arr-num" 
              name="arr-num"
              class="form-input" 
              placeholder="Ex: 25"
              value="<?= htmlspecialchars($formData['arr-num'] ?? '') ?>"
            />
          </div>

          <div class="form-field">
            <label for="arr-street">Rue</label>
            <input 
              id="arr-street" 
              name="arr-street"
              class="form-input" 
              placeholder="Ex: Avenue des Champs"
              value="<?= htmlspecialchars($formData['arr-street'] ?? '') ?>"
            />
          </div>

          <div class="form-field form-field-full">
            <label for="arr-city">Ville d'arrivée <span class="required">*</span></label>
            <input 
              id="arr-city" 
              name="arr-city"
              class="form-input city-autocomplete" 
              placeholder="Recherchez une ville en France"
              value="<?= htmlspecialchars($formData['arr-city'] ?? '') ?>"
              autocomplete="off"
              required
            />
            <div class="autocomplete-dropdown" id="arr-city-dropdown"></div>
          </div>
        </div>
      </div>

      <!-- Section 3: Date & Prix -->
      <div class="form-section" data-section="2">
        <div class="section-header">
          <div class="section-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
          </div>
          <div>
            <h2>Date et tarif</h2>
            <p>Quand partez-vous et à quel prix ?</p>
          </div>
        </div>

        <div class="form-grid">
          <div class="form-field">
            <label for="date">Date du trajet <span class="required">*</span></label>
            <input 
              id="date" 
              name="date"
              class="form-input" 
              type="date"
              value="<?= htmlspecialchars($formData['date'] ?? '') ?>"
              required
            />
          </div>

          <div class="form-field">
            <label for="time">Heure de départ</label>
            <input 
              id="time" 
              name="time"
              class="form-input" 
              type="time"
              value="<?= htmlspecialchars($formData['time'] ?? '') ?>"
            />
          </div>

          <div class="form-field">
            <label for="price">Prix par passager</label>
            <div class="input-with-icon">
              <input 
                id="price" 
                name="price"
                class="form-input" 
                type="number"
                step="0.01"
                min="0"
                max="250"
                placeholder="0.00"
                value="<?= htmlspecialchars($formData['price'] ?? '') ?>"
              />
              <span class="input-icon">€</span>
            </div>
            <small class="field-hint">Maximum 250€ (participation aux frais)</small>
          </div>
        </div>
      </div>

      <!-- Section 4: Options -->
      <div class="form-section" data-section="3">
        <div class="section-header">
          <div class="section-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="16" x2="12" y2="12"/>
              <line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>
          </div>
          <div>
            <h2>Préférences du trajet</h2>
            <p>Personnalisez les conditions de votre covoiturage</p>
          </div>
        </div>

        <div class="form-grid">
          <div class="form-field">
            <label for="places">Nombre de places disponibles <span class="required">*</span></label>
            <select id="places" name="places" class="form-input" required>
              <option value="">Choisir...</option>
              <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="<?= $i ?>" <?= (isset($formData['places']) && $formData['places'] == $i) ? 'selected' : '' ?>>
                  <?= $i ?> place<?= $i > 1 ? 's' : '' ?>
                </option>
              <?php endfor; ?>
            </select>
          </div>

          <div class="form-field form-field-full">
            <label>Options de confort</label>
            <div class="checkbox-group">
              <label class="checkbox-label">
                <input type="checkbox" name="animals" <?= isset($formData['animals']) ? 'checked' : '' ?>>
                <span class="checkbox-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 19l7-7 3 3-7 7-3-3z"/>
                    <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/>
                    <path d="M2 2l7.586 7.586"/>
                    <circle cx="11" cy="11" r="2"/>
                  </svg>
                </span>
                <span class="checkbox-text">Animaux acceptés</span>
              </label>

              <label class="checkbox-label">
                <input type="checkbox" name="smoking" <?= isset($formData['smoking']) ? 'checked' : '' ?>>
                <span class="checkbox-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8c0-3.3-2.7-6-6-6"/>
                    <path d="M12 2C9.6 2 7.6 3.8 7.1 6.2"/>
                    <line x1="2" y1="16" x2="22" y2="16"/>
                    <line x1="2" y1="20" x2="22" y2="20"/>
                  </svg>
                </span>
                <span class="checkbox-text">Fumeur accepté</span>
              </label>

              <label class="checkbox-label">
                <input type="checkbox" name="luggage" <?= isset($formData['luggage']) ? 'checked' : '' ?>>
                <span class="checkbox-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <polyline points="17 11 19 13 23 9"/>
                  </svg>
                </span>
                <span class="checkbox-text">Bagages volumineux possibles</span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-secondary" onclick="history.back()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
          </svg>
          Annuler
        </button>
        <button type="submit" class="btn btn-primary">
          <span>Publier mon trajet</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="5" y1="12" x2="19" y2="12"/>
            <polyline points="12 5 19 12 12 19"/>
          </svg>
        </button>
      </div>
    </form>

    <!-- Info Panel -->
    <div class="info-panel">
      <h3 class="info-panel-title">Pourquoi publier sur CarShare ?</h3>
      
      <div class="info-cards-grid">
        <div class="info-card-small">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
          </svg>
          <div>
            <strong>Sécurité garantie</strong>
            <p>Tous les profils sont vérifiés</p>
          </div>
        </div>

        <div class="info-card-small">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
          </svg>
          <div>
            <strong>Publication immédiate</strong>
            <p>Votre trajet est visible instantanément</p>
          </div>
        </div>

        <div class="info-card-small">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
          </svg>
          <div>
            <strong>Communication facilitée</strong>
            <p>Messagerie intégrée avec vos passagers</p>
          </div>
        </div>

        <div class="info-card-small">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
          </svg>
          <div>
            <strong>Réduisez vos frais</strong>
            <p>Partagez les coûts de carburant</p>
          </div>
        </div>

        <div class="info-card-small">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
          </svg>
          <div>
            <strong>Flexibilité totale</strong>
            <p>Gérez vos trajets en toute liberté</p>
          </div>
        </div>

        <div class="info-card-small">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
            <line x1="12" y1="22.08" x2="12" y2="12"/>
          </svg>
          <div>
            <strong>Impact écologique</strong>
            <p>Contribuez à réduire les émissions CO₂</p>
          </div>
        </div>
      </div>

      <!-- Best Practices Section -->
      <div class="best-practices">
        <h4>Bonnes pratiques pour votre trajet</h4>
        <div class="practices-list">
          <div class="practice-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            <span>Indiquez des points de rencontre faciles d'accès</span>
          </div>
          <div class="practice-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            <span>Soyez ponctuel et prévenez en cas de retard</span>
          </div>
          <div class="practice-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            <span>Acceptez les réservations rapidement</span>
          </div>
          <div class="practice-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            <span>Respectez le nombre de places disponibles</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/CarShare/assets/js/create-trip-enhanced.js"></script>
