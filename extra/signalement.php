<?php
/**
 * Standalone Signalement (Report) Page
 * This file provides a direct access point to the reporting functionality
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
    <title>CarShare - Signalement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/CarShare/assets/styles/header.css">
    <link rel="stylesheet" href="/CarShare/assets/styles/footer.css">
    <link rel="stylesheet" href="/CarShare/assets/styles/report-user.css">
</head>

<body>

<?php
// Show header
require __DIR__ . "/view/components/header.php";
?>

<main>
<?php
// Include the controller
require_once __DIR__ . '/controller/SignalementController.php';

$controller = new SignalementController();

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
