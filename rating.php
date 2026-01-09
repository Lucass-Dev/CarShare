<?php
/**
 * Standalone Rating Page
 * This file provides a direct access point to the rating functionality
 * while maintaining the MVC structure.
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

$action = $_GET['action'] ?? 'view';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CarShare - Notation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/styles/header.css">
    <link rel="stylesheet" href="/assets/styles/footer.css">
    <link rel="stylesheet" href="/assets/styles/rating.css">
</head>

<body>

<?php
// Show header
require __DIR__ . "/view/components/header.php";
?>

<main>
<?php
// Include the controller
require_once __DIR__ . '/controller/RatingController.php';

$controller = new RatingController();

// Handle different actions
switch ($action) {
    case 'submit':
        $controller->submit();
        break;
    
    case 'get_carpoolings':
        $controller->getCarpoolings();
        break;
    
    case 'view':
    default:
        $controller->render();
        break;
}
?>
</main>

<?php require __DIR__ . "/view/components/footer.php"; ?>

</body>
</html>
