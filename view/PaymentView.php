<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paiement - CarShare</title>
  <link href="/assets/styles/header.css" rel="stylesheet">
  <link href="/assets/styles/footer.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background-color: #fff;
      color: #0b0b0b;
    }

    main {
      text-align: center;
      margin-top: 40px;
    }

    h1 {
      font-size: 24px;
      font-weight: 600;
    }

    .card {
      border: 1px solid #b6b6b6;
      border-radius: 20px;
      padding: 30px 50px;
      width: 550px;
      margin: 40px auto;
      text-align: left;
    }

    .section-title {
      font-weight: 600;
      font-size: 18px;
      margin-bottom: 10px;
    }

    .payment-info table {
      width: 100%;
      font-size: 15px;
      margin-bottom: 20px;
    }

    .payment-info td {
      padding: 6px 0;
    }

    .total-price {
      font-size: 20px;
      font-weight: 600;
      margin-top: 15px;
      border-top: 1px solid #000;
      padding-top: 15px;
      text-align: right;
    }

    .payment-form div {
      margin-bottom: 12px;
    }

    .payment-form label {
      font-weight: 500;
    }

    .payment-form input {
      width: 100%;
      padding: 8px;
      border-radius: 8px;
      border: 1px solid #b6b6b6;
    }

    .btn-payer {
      background-color: #b6c8ff;
      border: none;
      border-radius: 15px;
      padding: 10px 25px;
      cursor: pointer;
      font-weight: 500;
      width: 100%;
      margin-top: 15px;
    }

    .details-resume {
      margin-top: 10px;
      font-size: 14px;
    }

    .error-message {
      color: red;
      background-color: #ffe6e6;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
    }
  </style>
</head>

<body>

<?php require __DIR__ . "/components/header.php"; ?>

<main>

  <h1>Paiement du trajet</h1>

  <div class="card">

    <?php if (isset($error)): ?>
      <div class="error-message">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <div class="payment-info">
      <div class="section-title">Récapitulatif du trajet</div>

      <table>
        <tr><td><strong>Trajet :</strong></td><td><?= htmlspecialchars($carpooling['start_location']) ?> → <?= htmlspecialchars($carpooling['end_location']) ?></td></tr>
        <tr><td><strong>Date :</strong></td><td><?= date('d/m/Y', strtotime($carpooling['start_date'])) ?></td></tr>
        <tr><td><strong>Heure :</strong></td><td><?= date('H:i', strtotime($carpooling['start_date'])) ?></td></tr>
        <tr><td><strong>Conducteur :</strong></td><td><?= htmlspecialchars($carpooling['first_name']) ?> (⭐ <?= $carpooling['global_rating'] ? round($carpooling['global_rating'], 1) : 'N/A' ?>)</td></tr>
      </table>

      <div class="total-price">
        Total : <?= number_format($carpooling['price'], 2) ?> € TTC
      </div>
    </div>

    <form class="payment-form" method="POST" action="/index.php?action=payment&carpooling_id=<?= $carpooling['id'] ?>">
      <div class="section-title">Informations de paiement</div>

      <div>
        <label>Nom sur la carte</label>
        <input type="text" name="card_name" placeholder="Ex : Marie Dupont" required>
      </div>

      <div>
        <label>Numéro de carte</label>
        <input type="text" name="card_number" placeholder="1234 5678 9012 3456" required>
      </div>

      <div style="display:flex; gap:20px;">
        <div style="flex:1;">
          <label>Expiration</label>
          <input type="text" name="card_expiry" placeholder="MM/AA" required>
        </div>
        <div style="flex:1;">
          <label>CVV</label>
          <input type="text" name="card_cvv" placeholder="123" required>
        </div>
      </div>

      <button type="submit" class="btn-payer">Payer maintenant</button>

      <div class="details-resume">
        En cliquant sur "Payer maintenant", vous confirmez votre réservation.
      </div>
    </form>

  </div>

</main>

<?php require __DIR__ . "/components/footer.php"; ?>

</body>
</html>
