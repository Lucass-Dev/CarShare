<?php
// /app/views/rating/rating.php
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>CarShare — Notation du trajet</title>

  <link href="http://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="/assets/styles/rating.css">
  <link rel="stylesheet" href="/assets/styles/header.css">
  <link rel="stylesheet" href="/assets/styles/footer.css">
</head>

<body>
  <header>
    <div class="logo">
      <img src="/assets/img/photo_hextech.jpeg" alt="CarShare Logo">
      CarShare
    </div>

    <div class="header-icons">
      <a href="/search" title="Rechercher">🔍</a>
      <a href="/connexion" title="Mon compte">👤</a>
    </div>
  </header>

  <main class="main container">
    <section class="rating">
      <h1 class="rating__title">Merci d'avoir choisi CarShare</h1>

      <?php if (isset($_GET['success'])): ?>
        <p style="margin:12px 0; padding:10px; border-radius:8px; background:#e8ffe8;">
          ✅ Merci ! Votre avis a bien été envoyé.
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
            </div>
          </div>

          <div class="rating__form">
            <form method="POST" action="/rating/submit">
              <input type="hidden" name="driver" value="<?= htmlspecialchars($driver['name']) ?>">

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
                <div class="stars stars--big" aria-hidden="true">
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
          <div class="stars stars--footer">
            <span class="star star--on">★</span>
            <span class="star star--on">★</span>
            <span class="star star--on">★</span>
            <span class="star star--on">★</span>
            <span class="star">☆</span>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <div class="footer-container">
      <div>HexTech ®</div>
      <div>CGU</div>
      <div>Informations légales</div>
      <div>Tous droits réservés</div>
      <div><a href="/FAQ">FAQ</a></div>
    </div>
  </footer>
</body>
</html>
