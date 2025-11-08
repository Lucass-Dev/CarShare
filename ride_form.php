<?php
// === Configuration de base ===
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- Connexion à la base de données ---
require_once './model/Database.php';
if (Database::$db === null) {
    Database::instanciateDb("carshare", "localhost", "root", "admin");
}

// --- Traitement du formulaire ---
$message = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération et nettoyage des champs
    $date   = $_POST["date"] ?? null;
    $heure  = $_POST["heure"] ?? null;
    $places = $_POST["places"] ?? null;

    // Départ
    $depart_numero   = $_POST['depart_numero'] ?? null;
    $depart_rue      = trim($_POST['depart_rue'] ?? '');
    $depart_cp       = $_POST['depart_code_postal'] ?? null;
    $depart_ville    = trim($_POST['depart_ville'] ?? '');

    // Arrivée
    $arrivee_numero  = $_POST['arrivee_numero'] ?? null;
    $arrivee_rue     = trim($_POST['arrivee_rue'] ?? '');
    $arrivee_cp      = $_POST['arrivee_code_postal'] ?? null;
    $arrivee_ville   = trim($_POST['arrivee_ville'] ?? '');

    // Validation
    if (!$date) $errors[] = "Veuillez choisir une date.";
    if (!$heure) $errors[] = "Veuillez indiquer une heure.";
    if (!$places || $places < 1) $errors[] = "Le nombre de places doit être supérieur à 0.";

    foreach ([
        'depart_numero','depart_rue','depart_code_postal','depart_ville',
        'arrivee_numero','arrivee_rue','arrivee_code_postal','arrivee_ville'
    ] as $champ) {
        if (empty($_POST[$champ])) {
            $errors[] = "Le champ " . htmlspecialchars($champ) . " est obligatoire.";
        }
    }

    // Si tout est bon → insertion BDD
    if (empty($errors)) {
        try {
            $stmt = Database::$db->prepare("
                INSERT INTO rides (
                    date_trajet, heure_trajet, places,
                    depart_numero, depart_rue, depart_code_postal, depart_ville,
                    arrivee_numero, arrivee_rue, arrivee_code_postal, arrivee_ville
                )
                VALUES (:date, :heure, :places,
                        :d_num, :d_rue, :d_cp, :d_ville,
                        :a_num, :a_rue, :a_cp, :a_ville)
            ");
            $stmt->execute([
                ':date' => $date,
                ':heure' => $heure,
                ':places' => $places,
                ':d_num' => $depart_numero,
                ':d_rue' => $depart_rue,
                ':d_cp' => $depart_cp,
                ':d_ville' => $depart_ville,
                ':a_num' => $arrivee_numero,
                ':a_rue' => $arrivee_rue,
                ':a_cp' => $arrivee_cp,
                ':a_ville' => $arrivee_ville
            ]);
            $message = "✅ Trajet enregistré avec succès !";
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier un trajet</title>

    <!-- Importation des fichiers CSS existants -->
    <link rel="stylesheet" href="./assets/styles/header.css">
    <link rel="stylesheet" href="./assets/styles/footer.css">
    <link rel="stylesheet" href="./assets/styles/index.css"> <!-- facultatif -->
</head>

<body>
    <?php include './view/components/header.php'; ?>

    <main class="form-container">
        <h1>Publier un nouveau trajet</h1>

        <!-- Affichage des erreurs ou du message de succès -->
        <?php if (!empty($errors)): ?>
            <div class="error">
                <h3>⚠️ Erreurs :</h3>
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($message): ?>
            <p class="success"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <!-- Formulaire -->
        <form method="POST" action="ride_form.php" class="ride-form">
            <label for="date">Date :</label>
            <input type="date" id="date" name="date" required>

            <label for="heure">Heure :</label>
            <input type="time" id="heure" name="heure" required>

            <label for="places">Nombre de places :</label>
            <input type="number" id="places" name="places" min="1" max="10" required>

            <!-- Adresse de départ -->
            <fieldset>
                <legend>Adresse de départ</legend>
                <label for="depart_numero">Numéro :</label>
                <input type="number" id="depart_numero" name="depart_numero" min="1" required>

                <label for="depart_rue">Rue :</label>
                <input type="text" id="depart_rue" name="depart_rue" required>

                <label for="depart_code_postal">Code postal :</label>
                <input type="text" id="depart_code_postal" name="depart_code_postal" pattern="\d{5}" required>

                <label for="depart_ville">Ville :</label>
                <input type="text" id="depart_ville" name="depart_ville" required>
            </fieldset>

            <!-- Adresse d'arrivée -->
            <fieldset>
                <legend>Adresse d'arrivée</legend>
                <label for="arrivee_numero">Numéro :</label>
                <input type="number" id="arrivee_numero" name="arrivee_numero" min="1" required>

                <label for="arrivee_rue">Rue :</label>
                <input type="text" id="arrivee_rue" name="arrivee_rue" required>

                <label for="arrivee_code_postal">Code postal :</label>
                <input type="text" id="arrivee_code_postal" name="arrivee_code_postal" pattern="\d{5}" required>

                <label for="arrivee_ville">Ville :</label>
                <input type="text" id="arrivee_ville" name="arrivee_ville" required>
            </fieldset>

            <button type="submit">Envoyer</button>
        </form>
    </main>

    <?php include './view/components/footer.php'; ?>
</body>
</html>
