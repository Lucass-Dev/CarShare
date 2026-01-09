<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ - CarShare</title>
  <link href="/assets/styles/header.css" rel="stylesheet">
  <link href="/assets/styles/footer.css" rel="stylesheet">
  <style>
    body {
      font-family: "Inter", Arial, sans-serif;
      background-color: #fff;
      margin: 0;
      padding: 0;
      color: #051220;
    }

    main {
      max-width: 900px;
      margin: 50px auto;
      padding: 0 20px;
    }

    h1 {
      text-align: center;
      color: #051220;
      font-size: 50px;
      margin-bottom: 50px;
    }

    h2 {
      color: #3065ad;
      font-size: 1.4em;
      margin: 40px 0 20px;
      border-bottom: 2px solid #3065ad;
      padding-bottom: 5px;
    }

    .faq-item {
      background-color: #ecf1fd;
      border: 1px solid #ccc;
      border-radius: 20px;
      margin-bottom: 15px;
      padding: 0;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      transition: 0.3s;
      overflow: hidden;
    }

    .faq-item:hover {
      border-color: #3065ad;
    }

    .faq-toggle {
      display: none;
    }

    .faq-question {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-weight: 600;
      cursor: pointer;
      font-size: 1.1em;
      padding: 18px 22px;
      user-select: none;
    }

    .faq-icon {
      font-size: 1.4em;
      font-weight: bold;
      transition: transform 0.3s, color 0.3s;
    }

    .faq-answer {
      max-height: 0;
      opacity: 0;
      padding: 0 22px;
      font-size: 0.97em;
      line-height: 1.6;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .faq-toggle:checked ~ .faq-answer {
      max-height: 300px;
      opacity: 1;
      padding: 12px 22px 18px;
    }

    .faq-toggle:checked + label .faq-icon {
      transform: rotate(45deg);
      color: #3065ad;
    }
  </style>
</head>
<body>

<?php require __DIR__ . "/components/header.php"; ?>

<main>
  <h1>Foire Aux Questions</h1>

  <h2>Fonctionnement général</h2>

  <div class="faq-item">
    <input type="checkbox" id="faq1" class="faq-toggle">
    <label for="faq1" class="faq-question">Comment fonctionne CarShare ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">CarShare met en relation conducteurs et passagers pour partager un trajet simplement et à moindre coût.</div>
  </div>

  <div class="faq-item">
    <input type="checkbox" id="faq2" class="faq-toggle">
    <label for="faq2" class="faq-question">Faut-il créer un compte pour utiliser CarShare ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">Oui, un compte est nécessaire pour proposer ou réserver un trajet. Cependant, vous pouvez visiter le site sans avoir de compte.</div>
  </div>

  <div class="faq-item">
    <input type="checkbox" id="faq3" class="faq-toggle">
    <label for="faq3" class="faq-question">Le service est-il payant ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">L'inscription est gratuite. Seuls les trajets comportent une participation aux frais du conducteur.</div>
  </div>

  <div class="faq-item">
    <input type="checkbox" id="faq4" class="faq-toggle">
    <label for="faq4" class="faq-question">Comment contacter un conducteur ou un passager ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">Une messagerie interne est disponible après la réservation, facilitant les échanges pour préparer le trajet.</div>
  </div>

  <div class="faq-item">
    <input type="checkbox" id="faq5" class="faq-toggle">
    <label for="faq5" class="faq-question">Puis-je voyager avec des bagages ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">Oui, mais il est conseillé de prévenir le conducteur pour vérifier la place disponible. Le nombre de bagages autorisés sera renseigné par le conducteur lors de la publication de son trajet.</div>
  </div>

  <h2>Réservations et trajets</h2>

  <div class="faq-item">
    <input type="checkbox" id="faq6" class="faq-toggle">
    <label for="faq6" class="faq-question">Comment réserver une place ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">Cherchez un trajet, sélectionnez le trajet qui vous intéresse et cliquez sur "Réserver", puisvalidez le paiement sécurisé. Vous recevrez ensuite une confirmation de réservation.</div>
  </div>

  <div class="faq-item">
    <input type="checkbox" id="faq7" class="faq-toggle">
    <label for="faq7" class="faq-question">Comment proposer un trajet ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">Le bouton "Publier un trajet" se situe en haut de votre écran, cliquez dessus puis renseignez votre itinéraire, date, prix, nombre de places disponibles et détails (optionnel).</div>
  </div>


  <div class="faq-item">
    <input type="checkbox" id="faq9" class="faq-toggle">
    <label for="faq9" class="faq-question">Que se passe-t-il si le conducteur ne vient pas ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">Contactez le support CarShare pour signaler le problème et obtenir un remboursement.</div>
  </div>

  <h2>Sécurité et confiance</h2>

  <div class="faq-item">
    <input type="checkbox" id="faq10" class="faq-toggle">
    <label for="faq10" class="faq-question">CarShare est-il sécurisé ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">Oui, les profils conducteurs sont vérifiés et les avis utilisateurs garantissent la fiabilité.</div>
  </div>

  <div class="faq-item">
    <input type="checkbox" id="faq11" class="faq-toggle">
    <label for="faq11" class="faq-question">Comment signaler un comportement inapproprié ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">Utilisez le bouton "Signaler" sur le profil de l'utilisateur ou le trajet que vous souhaitez signaler. Cette option n'est disponible qu'une fois le trajet effectué.</div>
  </div>

  <div class="faq-item">
    <input type="checkbox" id="faq12" class="faq-toggle">
    <label for="faq12" class="faq-question">Les paiements sont-ils sécurisés ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">Oui, toutes les transactions passent par un système de paiement certifié et simple d'utilisation</div>
  </div>

  <h2>Compte et assistance</h2>

  <div class="faq-item">
    <input type="checkbox" id="faq13" class="faq-toggle">
    <label for="faq13" class="faq-question">J'ai oublié mon mot de passe, que faire ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">Cliquez sur "Mot de passe oublié" à la connexion pour le réinitialiser.</div>
  </div>

  <div class="faq-item">
    <input type="checkbox" id="faq14" class="faq-toggle">
    <label for="faq14" class="faq-question">Comment modifier mes informations personnelles ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">Depuis votre profil, vous pouvez modifier vos données à tout moment.</div>
  </div>

  <div class="faq-item">
    <input type="checkbox" id="faq15" class="faq-toggle">
    <label for="faq15" class="faq-question">Comment contacter le support CarShare ? <span class="faq-icon">+</span></label>
    <div class="faq-answer">Depuis la page "Qui sommes-nous?" disponible en fin de chaque page du site CarShare. Vous y trouverez notre contact.</div>
  </div>

</main>

<?php require __DIR__ . "/components/footer.php"; ?>

</body>
</html>