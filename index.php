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

    <?php if ($action === 'rating'): ?>
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <?php endif; ?>
    
    <?php if ($action === 'profile'): ?>
    <link rel="stylesheet" href="/CarShare/style.css">
    <link rel="stylesheet" href="/CarShare/page profil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <?php endif; ?>
    
    <?php if ($action === 'history'): ?>
    <link rel="stylesheet" href="/CarShare/historique style.css">
    <?php endif; ?>
    
    <link rel="stylesheet" href="/CarShare/assets/styles/header.css">
    <link rel="stylesheet" href="/CarShare/assets/styles/footer.css">
    <link rel="stylesheet" href="/CarShare/assets/styles/home.css">
    <link rel="stylesheet" href="/CarShare/assets/styles/searchPage.css">
    
    <?php
    // Load page-specific CSS based on action
    $pageCss = [
        'create_trip' => 'create-trip.css',
        'rating' => 'rating.css',
        'signalement' => 'report-user.css',
        'login' => 'conn.css',
        'register' => 'inscr.css',
        'trip_details' => 'create-trip.css'
    ];
    
    if (isset($pageCss[$action])) {
        echo '<link rel="stylesheet" href="/CarShare/assets/styles/' . $pageCss[$action] . '">';
    }
    ?>
</head>

<body<?= ($action === 'create_trip' || $action === 'trip_details') ? ' class="page page--create"' : '' ?>>

<?php
// Routes that don't need header/footer wrapper
if ($action === 'admin') {
    require_once __DIR__ . "/controller/AdminController.php";
    (new AdminController())->render();
    exit;
}

// Show header for other pages
require __DIR__ . "/view/components/header.php";
?>

<main>
<?php
switch ($action) {

    case "home":
        require_once __DIR__ . "/controller/HomeController.php";
        (new HomeController())->index();
        break;

    case "search":
    case "display_search":
        require_once __DIR__ . "/controller/SearchPageController.php";
        (new SearchPageController())->render();
        break;

    case "login":
        require_once __DIR__ . "/controller/LoginController.php";
        (new LoginController())->render();
        break;

    case "register":
        require_once __DIR__ . "/controller/RegisterController.php";
        (new RegisterController())->render();
        break;

    case "profile":
        require_once __DIR__ . "/controller/ProfileController.php";
        (new ProfileController())->render();
        break;

    case "logout":
        require_once __DIR__ . "/controller/ProfileController.php";
        (new ProfileController())->logout();
        break;

    case "create_trip":
        require_once __DIR__ . "/controller/TripFormController.php";
        (new TripFormController())->render();
        break;

    case "create_trip_submit":
        require_once __DIR__ . "/controller/TripFormController.php";
        (new TripFormController())->submit();
        break;

    case "trip_details":
        require_once __DIR__ . "/controller/CarpoolingController.php";
        (new CarpoolingController())->details();
        break;

    case "payment":
        require_once __DIR__ . "/controller/PaymentController.php";
        (new PaymentController())->render();
        break;

    case "booking_confirmation":
        require_once __DIR__ . "/controller/BookingController.php";
        (new BookingController())->confirmation();
        break;

    case "history":
        require_once __DIR__ . "/controller/BookingController.php";
        (new BookingController())->history();
        break;

    case "faq":
        require_once __DIR__ . "/controller/FAQController.php";
        (new FAQController())->render();
        break;

    case "rating":
        require_once __DIR__ . "/controller/RatingController.php";
        (new RatingController())->render();
        break;

    case "rating_submit":
        require_once __DIR__ . "/controller/RatingController.php";
        (new RatingController())->submit();
        break;

    case "signalement":
        require_once __DIR__ . "/controller/SignalementController.php";
        (new SignalementController())->render();
        break;

    case "signalement_submit":
        require_once __DIR__ . "/controller/SignalementController.php";
        (new SignalementController())->submit();
        break;

    case "signalement_get_carpoolings":
        require_once __DIR__ . "/controller/SignalementController.php";
        (new SignalementController())->getCarpoolings();
        break;

    case "rating_get_carpoolings":
        require_once __DIR__ . "/controller/RatingController.php";
        (new RatingController())->getCarpoolings();
        break;

    default:
        http_response_code(404);
        echo "<p>Page non trouvée</p>";
        break;
}
?>
</main>

<?php require __DIR__ . "/view/components/footer.php"; ?>

</body>
</html>
