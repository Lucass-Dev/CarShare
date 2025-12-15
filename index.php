<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$action = $_GET['action'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CarShare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="/CarShare/assets/styles/home.css">
    <!-- CSS global -->
    <link rel="stylesheet" href="/CarShare/assets/styles/header.css">
    <link rel="stylesheet" href="/CarShare/assets/styles/footer.css">
    <link rel="stylesheet" href="/CarShare/assets/styles/home.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<?php require __DIR__ . "/view/components/header.php"; ?>

<main>
<?php
switch ($action) {
    case 'home':
        require __DIR__ . "/controller/HomeController.php";
        (new HomeController())->index();
        break;

    default:
        echo "<h2>Page non trouvée</h2>";
}
?>
</main>

<?php require __DIR__ . "/view/components/footer.php"; ?>

</body>
</html>
