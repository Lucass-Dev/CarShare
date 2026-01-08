<?php $success = $success ?? false; ?>
<section class="hero">
    <div class="hero__overlay">
      <h1 class="hero__title">Publier un nouveau trajet</h1>

      <?php if ($success): ?>
        <div style="margin:20px 0; padding:15px; border-radius:8px; background:#d4edda; color:#155724; text-align:center;">
          ✅ Trajet créé avec succès !
        </div>
      <?php endif; ?>

      <?php 
      $errors = $_SESSION['trip_form_errors'] ?? [];
      $formData = $_SESSION['trip_form_data'] ?? [];
      if (!empty($errors)): 
      ?>
        <div style="margin:20px 0; padding:15px; border-radius:8px; background:#f8d7da; color:#721c24;">
          <strong>Erreurs :</strong>
          <ul style="margin:10px 0 0 0; padding-left:20px;">
            <?php foreach ($errors as $err): ?>
              <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php 
        // Clear errors after displaying
        unset($_SESSION['trip_form_errors']);
      endif; 
      ?>

      <form class="trip-form" method="POST" action="/CarShare/index.php?action=create_trip_submit" novalidate>
        <div class="trip-form__row">
          <div class="form__group form__group--small">
            <label class="form__label" for="dep-num">N° voie</label>
            <input 
              id="dep-num" 
              name="dep-num"
              class="form__input" 
              placeholder="N° voie"
              value="<?= htmlspecialchars($formData['dep-num'] ?? '') ?>"
            />
          </div>

          <div class="form__group">
            <label class="form__label" for="dep-street">Rue</label>
            <input 
              id="dep-street" 
              name="dep-street"
              class="form__input" 
              placeholder="Rue"
              value="<?= htmlspecialchars($formData['dep-street'] ?? '') ?>"
            />
          </div>

          <div class="form__group">
            <label class="form__label" for="dep-city">Ville de départ <span class="form__required">*</span></label>
            <input 
              id="dep-city" 
              name="dep-city"
              class="form__input city-autocomplete" 
              placeholder="Ville (France)"
              value="<?= htmlspecialchars($formData['dep-city'] ?? '') ?>"
              autocomplete="off"
              required
            />
            <div class="autocomplete-dropdown" id="dep-city-dropdown"></div>
          </div>
        </div>

        <div class="trip-form__row">
          <div class="form__group form__group--small">
            <label class="form__label" for="arr-num">N° voie</label>
            <input 
              id="arr-num" 
              name="arr-num"
              class="form__input" 
              value="<?= htmlspecialchars($formData['arr-num'] ?? '') ?>"
            />
          </div>

          <div class="form__group">
            <label class="form__label" for="arr-street">Rue</label>
            <input 
              id="arr-street" 
              name="arr-street"
              class="form__input" 
              placeholder="Rue"
              value="<?= htmlspecialchars($formData['arr-street'] ?? '') ?>"
            />
          </div>

          <div class="form__group">
            <label class="form__label" for="arr-city">Ville d'arrivée <span class="form__required">*</span></label>
            <input 
              id="arr-city" 
              name="arr-city"
              class="form__input city-autocomplete" 
              placeholder="Ville (France)"
              value="<?= htmlspecialchars($formData['arr-city'] ?? '') ?>"
              autocomplete="off"
              required
            />
            <div class="autocomplete-dropdown" id="arr-city-dropdown"></div>
          </div>
        </div>

        <div class="trip-form__row trip-form__row--compact">
          <div class="form__group form__group--small">
            <label class="form__label" for="date">Date <span class="form__required">*</span></label>
            <input 
              id="date" 
              name="date"
              class="form__input" 
              type="date"
              value="<?= htmlspecialchars($formData['date'] ?? '') ?>"
              required
            />
          </div>

          <div class="form__group form__group--small">
            <label class="form__label" for="time">Heure</label>
            <input 
              id="time" 
              name="time"
              class="form__input" 
              type="time"
              value="<?= htmlspecialchars($formData['time'] ?? '') ?>"
            />
          </div>

          <div class="form__group form__group--small">
            <label class="form__label" for="price">Prix (€)</label>
            <input 
              id="price" 
              name="price"
              class="form__input" 
              type="number"
              step="0.01"
              min="0"
              placeholder="0.00"
              value="<?= htmlspecialchars($formData['price'] ?? '') ?>"
            />
          </div>
        </div>

        <div class="trip-form__row trip-form__row--options">
          <fieldset class="options">
            <legend class="options__title">Options</legend>
            
            <div class="form__group form__group--small">
              <label class="form__label" for="places">Nombre de place(s) <span class="form__required">*</span></label>
              <select id="places" name="places" class="form__input" required>
                <option value="0">0</option>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                  <option value="<?= $i ?>" <?= (isset($formData['places']) && $formData['places'] == $i) ? 'selected' : '' ?>>
                    <?= $i ?>
                  </option>
                <?php endfor; ?>
              </select>
            </div>

            <label class="options__item"><input type="checkbox" name="animals" <?= isset($formData['animals']) ? 'checked' : '' ?>> Animaux</label>
            <label class="options__item"><input type="checkbox" name="smoking" <?= isset($formData['smoking']) ? 'checked' : '' ?>> Fumeur</label>
          </fieldset>

          <div class="trip-form__actions">
            <button type="submit" class="btn btn--primary btn--pill">Publier</button>
          </div>
        </div>
      </form>

      <!-- Datalist for location autocomplete -->
      <datalist id="locations-list">
        <?php foreach ($locations as $location): ?>
          <option value="<?= htmlspecialchars($location['name']) ?>">
            <?= htmlspecialchars($location['postal_code']) ?>
          </option>
        <?php endforeach; ?>
      </datalist>
    </div>
  </section>

<script src="/CarShare/assets/js/create-trip.js"></script>
