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
<<<<<<< Updated upstream
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
=======
        // Routage avec support de ?controller=... et ?action=...
        $controllerParam = $_GET["controller"] ?? null;
        $actionParam = $_GET["action"] ?? null;
        
        // Si controller est défini, on route selon controller + action
        if ($controllerParam) {
            switch ($controllerParam) {
                case "booking":
                    $controller = new BookingController();
                    if ($actionParam === "history") {
                        $controller->history();
                    } else {
                        http_response_code(404);
                        echo "Page non trouvée";
                    }
                    break;
                case "trip":
                    $controller = new TripController();
                    $controller->render();
                    break;
                case "user":
                    $controller = new UserController();
                    $controller->index();
                    break;
                case "login":
                    $controller = new LoginController();
                    $controller->render();
                    break;
                case "register":
                    $controller = new RegisterController();
                    $controller->render();
                    break;
                case "mp":
                    $controller = new MPController();
                    $controller->render();
                    break;
                case "profile":
                    $controller = new ProfileController();
                    $controller->render();
                    break;
                default:
                    http_response_code(404);
                    echo "Page non trouvée";
                    break;
            }
        } else {
            // Fallback sur l'ancien système ?action=...
            $controller = $actionParam ?? "home";

        switch ($controller) {
            case "home":
                $controller = new HomeController();
                $controller->render();
                break;
            case "user":
                $controller = new UserController();
                $controller->index();
                break;
            case "login":
                $controller = new LoginController();
                $controller->render();
                break;
            case "register":
                $controller = new RegisterController();
                $controller->render();
                break;
            case "profile":
                $controller = new ProfileController();
                $controller->render();
                break;
            case "trip":
                $controller = new TripController();
                $controller->render();
                break;
            case "admin":
                $controller = new AdminController();
                $controller->index();
                break;
            case "faq":
                $controller = new FAQController();
                $controller->index();
                break;
            case "cgu":
                $controller = new CGUController();
                $controller->index();
                break;
            case "legal":
                $controller = new LegalController();
                $controller->index();
                break;
            case "disconnect":
                session_unset();
                session_destroy();
                header("Location: index.php");
                break;
            case "mp":
                $controller = new MPController();
                $controller->render();
                break;
            case "create_trip":
                $controller = new TripFormController();
                $controller->render();
                break;
            case "create_trip_submit":
                $controller = new TripFormController();
                $controller->submit();
                break;
            // --- Nouvelles routes sans impacter l'existant ---
            case "history":
                $controller = new BookingController();
                $controller->history();
                break;
            case "rating":
                $controller = new RatingController();
                // Méthode d'affichage du formulaire
                if (method_exists($controller, 'render')) {
                    $controller->render();
                } else if (method_exists($controller, 'index')) {
                    $controller->index();
                }
                break;
            case "rating_submit":
                $controller = new RatingController();
                $controller->submit();
                break;
            case "rating_get_carpoolings":
                $controller = new RatingController();
                // API JSON pour récupérer les trajets d'un utilisateur
                if (method_exists($controller, 'getCarpoolings')) {
                    $controller->getCarpoolings();
                } else if (method_exists($controller, 'getUserCarpoolings')) {
                    $controller->getUserCarpoolings();
                }
                break;
            case "signalement":
                $controller = new SignalementController();
                if (method_exists($controller, 'render')) {
                    $controller->render();
                } else if (method_exists($controller, 'index')) {
                    $controller->index();
                }
                break;
            case "signalement_submit":
                $controller = new SignalementController();
                $controller->submit();
                break;
            case "signalement_get_carpoolings":
                $controller = new SignalementController();
                if (method_exists($controller, 'getCarpoolings')) {
                    $controller->getCarpoolings();
                } else if (method_exists($controller, 'getUserCarpoolings')) {
                    $controller->getUserCarpoolings();
                }
                break;
            default:
                http_response_code(404);
                echo "Page non trouvée";
                break;
            }
        }


>>>>>>> Stashed changes
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
