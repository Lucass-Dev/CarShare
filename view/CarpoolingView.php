<section class="hero">
    <div class="hero__overlay">
      <h1 class="hero__title">Publier un nouveau trajet</h1>

      <?php if (isset($error)): ?>
        <div style="color: red; text-align: center; margin: 20px;">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form class="trip-form" method="POST" action="<?= url('index.php?action=create_trip') ?>">
        <div class="trip-form__row">
          <div class="form__group">
            <label class="form__label" for="dep-city">Adresse de départ <span class="form__required">*</span></label>
            <input id="dep-city" name="start_city" class="form__input" placeholder="Ville (France)" required />
          </div>
        </div>

        <div class="trip-form__row">
          <div class="form__group">
            <label class="form__label" for="arr-city">Adresse d'arrivée <span class="form__required">*</span></label>
            <input id="arr-city" name="end_city" class="form__input" placeholder="Ville (France)" required />
          </div>
        </div>

        <div class="trip-form__row trip-form__row--compact">
          <div class="form__group form__group--small">
            <label class="form__label" for="date">Date <span class="form__required">*</span></label>
            <input id="date" name="date" class="form__input" type="date" required />
          </div>

          <div class="form__group form__group--small">
            <label class="form__label" for="time">Heure</label>
            <input id="time" name="time" class="form__input" type="time" value="08:00" />
          </div>

          <div class="form__group form__group--small">
            <label class="form__label" for="places">Nombre de place(s) <span class="form__required">*</span></label>
            <select id="places" name="places" class="form__input" required>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
            </select>
          </div>

          <div class="form__group form__group--small">
            <label class="form__label" for="price">Prix (€)</label>
            <input id="price" name="price" class="form__input" type="number" step="0.01" value="15" />
          </div>
        </div>

        <div class="trip-form__row trip-form__row--options">
          <div class="trip-form__actions">
            <button type="submit" class="btn btn--primary btn--pill">Publier</button>
          </div>
        </div>
      </form>
    </div>
  </section>