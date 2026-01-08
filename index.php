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
    <link rel="stylesheet" href="/CarShare/assets/styles/index.css">
    <link rel="stylesheet" href="/CarShare/assets/styles/home.css">
    <link rel="stylesheet" href="/CarShare/assets/styles/searchPage.css">
    <link rel="stylesheet" href="/CarShare/assets/styles/form-enhancements.css">
    <link rel="stylesheet" href="/CarShare/assets/styles/global-enhancements.css">
    <link rel="stylesheet" href="/CarShare/assets/styles/autocomplete.css">
    
    <?php
    // Load page-specific CSS based on action
    $pageCss = [
        'create_trip' => ['create-trip.css', 'city-autocomplete.css'],
        'rating' => 'rating.css',
        'signalement' => 'report-user.css',
        'login' => 'conn.css',
        'forgot_password' => 'conn.css',
        'register' => ['inscr.css', 'password-validator.css'],
        'trip_details' => 'trip_infos.css',
        'payment' => 'trip_payment.css',
        'booking_confirmation' => 'confirmation_reservation.css',
        'history' => ['history.css', 'modal-system.css'],
        'profile' => 'page_profil.css',
        'user_profile' => ['user-profile.css', 'modal-system.css'],
        'legal' => 'legal.css',
        'cgu' => 'legal.css',
        'cgv' => 'legal.css',
        'faq' => 'FAQ.css',
        'search' => ['search-enhancements.css', 'city-autocomplete.css'],
        'display_search' => ['search-enhancements.css', 'city-autocomplete.css'],
        'home' => ['search-enhancements.css', 'city-autocomplete.css'],
        'offers' => 'offers.css'
    ];
    
    if (isset($pageCss[$action])) {
        $css = is_array($pageCss[$action]) ? $pageCss[$action] : [$pageCss[$action]];
        foreach ($css as $file) {
            echo '<link rel="stylesheet" href="/CarShare/assets/styles/' . $file . '">';
        }
    }
    ?>
    
    <!-- Core JavaScript -->
    <script src="/CarShare/assets/js/form-enhancements.js" defer></script>
    <script src="/CarShare/assets/js/global-enhancements.js" defer></script>
    
    <?php
    // Load page-specific JavaScript based on action
    $pageJs = [
        'register' => ['password-validator.js', 'register.js'],
        'login' => ['login.js'],
        'create_trip' => ['city-autocomplete-enhanced.js', 'create-trip.js'],
        'rating' => ['rating.js', 'rating-form.js'],
        'signalement' => ['signalement.js', 'signalement-form.js'],
        'search' => ['city-autocomplete-enhanced.js', 'search-enhancements.js'],
        'display_search' => ['city-autocomplete-enhanced.js', 'search-enhancements.js'],
        'home' => ['city-autocomplete-enhanced.js', 'search-enhancements.js', 'global-search.js'],
        'history' => ['rating-report-modals.js'],
        'user_profile' => ['rating-report-modals.js']
    ];
    
    if (isset($pageJs[$action])) {
        $js = is_array($pageJs[$action]) ? $pageJs[$action] : [$pageJs[$action]];
        foreach ($js as $file) {
            echo '<script src="/CarShare/assets/js/' . $file . '" defer></script>';
        }
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

    case "offers":
        require_once __DIR__ . "/controller/OffersController.php";
        (new OffersController())->render();
        break;

    case "login":
        require_once __DIR__ . "/controller/LoginController.php";
        (new LoginController())->render();
        break;

    case "forgot_password":
        require_once __DIR__ . "/controller/ForgotPasswordController.php";
        (new ForgotPasswordController())->render();
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

    case "disconnect":
        session_start();
        session_unset();
        session_destroy();
        header('Location: /CarShare/index.php?action=home');
        exit();
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

    case "cgu":
        require_once __DIR__ . "/controller/CGUController.php";
        require_once __DIR__ . "/view/CGUView.php";
        (new CGUController())->index();
        break;

    case "legal":
        require_once __DIR__ . "/controller/LegalController.php";
        require_once __DIR__ . "/view/LegalView.php";
        (new LegalController())->index();
        break;

    case "cgv":
        require_once __DIR__ . "/controller/CGVController.php";
        require_once __DIR__ . "/view/CGVView.php";
        (new CGVController())->index();
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

    case "user_profile":
        require_once __DIR__ . "/controller/UserProfileController.php";
        (new UserProfileController())->viewProfile();
        break;

    case "messaging":
        require_once __DIR__ . "/controller/MessagingController.php";
        (new MessagingController())->index();
        break;

    case "messaging_conversation":
        require_once __DIR__ . "/controller/MessagingController.php";
        (new MessagingController())->conversation();
        break;

    case "send_message":
        require_once __DIR__ . "/controller/MessagingController.php";
        (new MessagingController())->sendMessage();
        break;

    case "get_new_messages":
        require_once __DIR__ . "/controller/MessagingController.php";
        (new MessagingController())->getNewMessages();
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
