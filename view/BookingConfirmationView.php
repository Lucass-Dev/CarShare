<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation confirmée - CarShare</title>
    <link href="/CarShare/assets/styles/header.css" rel="stylesheet">
    <link href="/CarShare/assets/styles/footer.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #fff;
        }

        main {
            background-color: white;
            min-height: 80vh;
            padding: 60px 20px;
            display: flex;
            flex-direction: column;            http://localhost/CarShare/index.php?controller=booking&action=history
            align-items: center;
        }

        .title {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 40px;
        }

        .check {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid #051220;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .title h1 {
            font-size: 36px;
            margin: 0;
            color: #051220;
        }

        .card {
            width: 80%;
            max-width: 900px;
            border: 2px solid #051220;
            border-radius: 30px;
            padding: 40px;
        }

        .trip-title {
            text-align: center;
            font-size: 20px;
            margin-bottom: 30px;
        }

        .separator {
            border-top: 2px solid #051220;
            margin: 25px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 25px 0;
            font-size: 17px;
        }

        .btn {
            background-color: #8caff2;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 22px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<?php require __DIR__ . "/components/header.php"; ?>

<main>

    <div class="title">
        <div class="check">✓</div>
        <h1>Réservation confirmée</h1>
    </div>

    <div class="card">
        <div class="trip-title">
            Votre trajet a été réservé avec succès
        </div>

        <div class="separator"></div>

        <div class="row">
            <span>Vous pouvez retrouver votre trajet dans votre historique</span>
            <a href="/CarShare/index.php?action=history" class="btn">Voir mon historique</a>
        </div>

        <div class="row">
            <span>Retour à la page d'accueil</span>
            <a href="/CarShare/index.php?action=home" class="btn">Accueil</a>
        </div>
    </div>

</main>

<?php require __DIR__ . "/components/footer.php"; ?>

</body>
</html>
