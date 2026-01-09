<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détails du trajet - CarShare</title>
  <link href="/assets/styles/header.css" rel="stylesheet">
  <link href="/assets/styles/footer.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background-color: #fff;
      color: #051220;
    }
    main {
      text-align: center;
      margin-top: 40px;
    }

    h1 {
      font-size: 50px;
      font-weight: 600;
    }

    .trajet-info {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 60px;
      margin-top: 40px;
      margin-bottom: 50px;
    }

    .trajet-info h2 {
      font-size: 20px;
      font-weight: 600;
    }

    .trajet-info span {
      font-size: 16px;
    }

    .card {
      border: 1px solid #051220;
      border-radius: 20px;
      padding: 30px 50px;
      width: 550px;
      margin: auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .left {
      text-align: center;
      border-right: 2px solid #051220;
      padding-right: 30px;
    }

    .left img {
      width: 60px;
      border-radius: 50%;
    }

    .right {
      flex: 1;
      padding-left: 40px;
      text-align: left;
    }

    .right table {
      width: 100%;
      font-size: 15px;
      margin-bottom: 10px;
    }

    .right td {
      padding: 3px 0;
    }

    .details-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 25px;
      border-top: 1px solid #051220;
      padding-top: 10px;
    }

    .price {
      font-weight: 600;
      font-size: 18px;
    }

    .btn-reserver {
      background-color: #C8D6F8;
      border: none;
      border-radius: 15px;
      padding: 8px 18px;
      cursor: pointer;
      font-weight: 500;
      text-decoration: none;
      color: #000;
      display: inline-block;
    }
  </style>
</head>

<body>

<?php require __DIR__ . "/components/header.php"; ?>

<main>
  <h1>Détails du trajet</h1>

  <div class="trajet-info">
    <div>
      <h2><?= htmlspecialchars($carpooling['start_location']) ?> → <?= htmlspecialchars($carpooling['end_location']) ?></h2>
      <span><?= date('d/m/Y', strtotime($carpooling['start_date'])) ?></span>
    </div>
  </div>

  <div class="card">
    <div class="left">
      <img src="/assets/img/avatar.jpg" alt="Photo du conducteur">
      <p><strong><?= htmlspecialchars($carpooling['first_name']) ?></strong><br>
      <?= $carpooling['global_rating'] ? round($carpooling['global_rating'], 1) : 'N/A' ?>⭐</p>
    </div>

    <div class="right">
      <table>
        <tr>
          <td><strong>Départ</strong></td>
          <td>Lieu : <?= htmlspecialchars($carpooling['start_location']) ?></td>
          <td>Date : <?= date('d/m/Y', strtotime($carpooling['start_date'])) ?></td>
          <td>Heure : <?= date('H:i', strtotime($carpooling['start_date'])) ?></td>
        </tr>
        <tr>
          <td><strong>Arrivée</strong></td>
          <td>Lieu : <?= htmlspecialchars($carpooling['end_location']) ?></td>
          <td colspan="2"></td>
        </tr>
      </table>

      <div class="details-bottom">
        <div>
          <p><?= $carpooling['available_places'] ?> place(s) disponible(s)</p>
        </div>
        <div>
          <span class="price"><?= number_format($carpooling['price'], 2) ?> € TTC</span><br>
          <br>
          <a class="btn-reserver" href="/index.php?action=payment&carpooling_id=<?= $carpooling['id'] ?>">Réserver</a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require __DIR__ . "/components/footer.php"; ?>

</body>
</html>
