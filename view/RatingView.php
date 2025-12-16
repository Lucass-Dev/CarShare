<?php
if (!isset($driver) || !is_array($driver) || empty($driver['id'])) {
    header('Location: /CarShare/index.php?action=rating');
    exit;
}
?>
<section class="main container">
    <section class="rating">
      <h1 class="rating__title">Merci d'avoir choisi CarShare</h1>

      <?php if (isset($_GET['success'])): ?>
        <p style="margin:12px 0; padding:10px; border-radius:8px; background:#d4edda; color:#155724; text-align:center;">
          ✅ Merci pour votre avis !
        </p>
      <?php elseif (isset($_GET['error'])): ?>
        <?php 
        $errorMsg = 'Une erreur est survenue';
        if ($_GET['error'] === 'user_not_found') $errorMsg = 'Utilisateur non trouvé';
        elseif ($_GET['error'] === 'carpooling_not_found') $errorMsg = 'Trajet non trouvé';
        elseif ($_GET['error'] === 'save_failed') $errorMsg = 'Erreur lors de l\'enregistrement';
        elseif ($_GET['error'] === 'self_rating') $errorMsg = 'Vous ne pouvez pas vous évaluer vous-même';
        ?>
        <p style="margin:12px 0; padding:10px; border-radius:8px; background:#f8d7da; color:#721c24; text-align:center;">
          ❌ <?= htmlspecialchars($errorMsg) ?>
        </p>
      <?php endif; ?>

      <div class="rating__card">
        <div class="rating__card-inner">
          <div class="rating__profile">
            <div class="avatar avatar--large" aria-hidden="true">
              <svg width="72" height="72" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="36" cy="36" r="36" fill="#9fc0ff"/>
                <circle cx="36" cy="28" r="13" fill="#fff"/>
                <path d="M14 58c8-10 30-10 44 0" fill="#fff"/>
              </svg>
            </div>
            <div class="rating__profile-meta">
              <div class="rating__name"><?= htmlspecialchars($driver['name']) ?></div>
              <div class="rating__avg">Moy. <span class="rating__avg-value"><?= htmlspecialchars((string)$driver['avg']) ?></span></div>
              <div class="rating__avg" style="display:flex; gap:8px; color:#4a5568; font-size:14px;">
                <span><?= htmlspecialchars((string)$driver['trips']) ?> trajets</span>
                <span>•</span>
                <span><?= htmlspecialchars((string)$driver['reviews']) ?> avis</span>
              </div>
            </div>
          </div>

          <div class="rating__form">
            <form method="POST" action="/CarShare/index.php?action=rating_submit">
              <input type="hidden" name="user_id" value="<?= htmlspecialchars($driver['id']) ?>">
              <?php if (!empty($driver['carpooling_id'])): ?>
              <input type="hidden" name="carpooling_id" value="<?= htmlspecialchars($driver['carpooling_id']) ?>">
              <?php endif; ?>

              <label class="form__label" for="comment">Laissez un commentaire</label>
              <textarea
                id="comment"
                name="comment"
                class="form__textarea"
                placeholder="Comment s'est passé votre voyage avec <?= htmlspecialchars($driver['name']) ?> ?"
              ></textarea>

              <div style="margin-top:10px;">
                <label class="form__label" for="stars">Note</label>
                <select id="stars" name="stars" class="form__textarea" style="height:44px;">
                  <option value="5">★★★★★ (5)</option>
                  <option value="4" selected>★★★★☆ (4)</option>
                  <option value="3">★★★☆☆ (3)</option>
                  <option value="2">★★☆☆☆ (2)</option>
                  <option value="1">★☆☆☆☆ (1)</option>
                </select>
              </div>

              <div class="rating__stars-row" style="margin-top:12px;">
                <div class="stars stars--big" aria-hidden="true" id="star-display">
                  <span class="star star--on">★</span>
                  <span class="star star--on">★</span>
                  <span class="star star--on">★</span>
                  <span class="star star--on">★</span>
                  <span class="star">☆</span>
                </div>

                <button type="submit" class="btn btn--primary rating__submit">Donner un avis</button>
              </div>
            </form>
          </div>
        </div>

        <div class="rating__footer-stars" aria-hidden="true">
          <div class="stars stars--footer" id="footer-stars">
            <?php 
            $avgRating = is_numeric($driver['avg']) ? (int)round($driver['avg']) : 0;
            for ($i = 1; $i <= 5; $i++): 
            ?>
              <span class="star <?= $i <= $avgRating ? 'star--on' : '' ?>">
                <?= $i <= $avgRating ? '★' : '☆' ?>
              </span>
            <?php endfor; ?>
          </div>
        </div>
      </div>
    </section>
  </section>

<script src="/CarShare/assets/js/rating-form.js"></script>
