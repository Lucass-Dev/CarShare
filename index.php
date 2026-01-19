<?php
// Charger la configuration globale
require_once __DIR__ . '/config.php';

// Start output buffering to allow header redirects after content output
ob_start();

// Handle disconnect before any output
if (isset($_GET['action']) && $_GET['action'] === 'disconnect') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_unset();
    session_destroy();
    header('Location: ' . url('index.php?action=home'));
    exit();
}

$action = $_GET['action'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CarShare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Configuration JavaScript globale -->
    <script>
        // Configuration accessible par tous les scripts JavaScript
        window.APP_CONFIG = {
            basePath: '<?= BASE_PATH ?>',
            baseUrl: '<?= BASE_URL ?>'
        };
    </script>
    
    <!-- Anti-cache et performance -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <!-- Google Maps API -->
    <?php if (in_array($action, ['trip_details', 'display_search', 'create_trip', 'edit_trip'])): ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= API_MAPS ?>&libraries=places&language=fr" defer></script>
    <?php endif; ?>

    <?php if ($action === 'rating'): ?>
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <?php endif; ?>
    
    <?php $cacheBuster = '?v=' . time(); ?>
    <link rel="stylesheet" href="<?= asset('styles/header.css') . $cacheBuster ?>">
    <link rel="stylesheet" href="<?= asset('styles/footer.css') . $cacheBuster ?>">
    <link rel="stylesheet" href="<?= asset('styles/index.css') . $cacheBuster ?>">
    <link rel="stylesheet" href="<?= asset('styles/home.css') . $cacheBuster ?>">
    <link rel="stylesheet" href="<?= asset('styles/searchPage.css') . $cacheBuster ?>">
    <link rel="stylesheet" href="<?= asset('styles/form-enhancements.css') . $cacheBuster ?>">
    <link rel="stylesheet" href="<?= asset('styles/global-enhancements.css') . $cacheBuster ?>">
    <link rel="stylesheet" href="<?= asset('styles/autocomplete.css') . $cacheBuster ?>">
    <link rel="stylesheet" href="<?= asset('styles/design-improvements.css') ?>">
    <link rel="stylesheet" href="<?= asset('styles/custom-dialogs.css') ?>">
    <link rel="stylesheet" href="<?= asset('styles/notification-system.css') ?>">
    
    <?php
    // Load page-specific CSS based on action
    $pageCss = [
        'create_trip' => ['create-trip-enhanced.css', 'city-autocomplete.css'],
        'edit_trip' => ['create-trip-enhanced.css', 'city-autocomplete.css'],
        'rating' => 'rating.css',
        'signalement' => 'report-user.css',
        'login' => 'conn.css',
        'admin_login' => 'conn.css',
        'forgot_password' => 'conn.css',
        'reset_password' => 'conn.css',
        'register' => ['inscr.css', 'password-validator.css', 'register-validation.css'],
        'admin_register' => ['inscr.css', 'password-validator.css', 'register-validation.css'],
        'registration_pending' => 'inscr.css',
        'admin_registration_pending' => 'inscr.css',
        'validate_email' => 'inscr.css',
        'validate_admin_email' => 'inscr.css',
        'trip_details' => 'trip-details.css',
        'payment' => 'trip_payment.css',
        'booking_confirmation' => 'confirmation_reservation.css',
        'history' => ['history-enhanced.css', 'modal-system.css'],
        'my_bookings' => 'history.css',
        'my_trips' => 'my-trips.css',
        'profile' => ['user-profile.css', 'page_profil.css', 'modal-system.css'],
        'user_profile' => ['user-profile.css', 'modal-system.css'],
        'legal' => 'legal.css',
        'cgu' => 'legal.css',
        'cgv' => 'legal.css',
        'faq' => 'FAQ.css',
        'contact' => 'contact-modern.css',
        'about' => 'about.css',
        'search' => ['search-enhancements.css', 'city-autocomplete.css'],
        'display_search' => ['search-enhancements.css', 'search-with-map.css', 'city-autocomplete.css'],
        'home' => ['search-enhancements.css', 'city-autocomplete.css'],
        'user_search' => 'user-search-enhanced.css',
        'offers' => 'offers.css',
        'daily_carshare' => 'daily-carshare.css',
        'messaging' => 'mp.css',
        'messaging_conversation' => 'mp.css'
    ];
    
    if (isset($pageCss[$action])) {
        $css = is_array($pageCss[$action]) ? $pageCss[$action] : [$pageCss[$action]];
        foreach ($css as $file) {
            echo '<link rel="stylesheet" href="' . asset('styles/' . $file) . '">';
        }
    }
    ?>
    
    <!-- Core JavaScript -->
    <script src="<?= asset('js/url-helper.js') ?>"></script>
    <script src="<?= asset('js/fix-copy-paste.js') ?>"></script>
    <script src="<?= asset('js/custom-dialogs.js') ?>"></script>
    <script src="<?= asset('js/form-enhancements.js') ?>" defer></script>
    <script src="<?= asset('js/notification-system.js') ?>" defer></script>
    <script src="<?= asset('js/validation-message-hub.js') ?>" defer></script>
    <script src="<?= asset('js/global-enhancements.js') ?>" defer></script>
    
    <?php
    // Load page-specific JavaScript based on action
    $pageJs = [
        'register' => ['password-validator.js', 'register.js'],
        'admin_register' => ['password-validator.js', 'register.js'],
        'login' => ['login.js'],
        'admin_login' => ['login.js'],
        'create_trip' => ['city-autocomplete-enhanced.js', 'create-trip-enhanced.js'],
        'edit_trip' => ['city-autocomplete-enhanced.js', 'create-trip-enhanced.js'],
        'trip_details' => ['trip.js'],
        'rating' => ['rating.js', 'rating-form.js'],
        'signalement' => ['signalement.js', 'signalement-form.js'],
        'search' => ['city-autocomplete-enhanced.js', 'search-enhancements.js'],
        'display_search' => ['city-autocomplete-enhanced.js', 'search-enhancements.js', 'searchMapIntegration.js'],
        'home' => ['city-autocomplete-enhanced.js', 'search-enhancements.js', 'global-search.js'],
        'history' => ['rating-report-modals.js'],
        'user_profile' => ['rating-report-modals.js'],
        'profile' => ['profile.js']
    ];
    
    if (isset($pageJs[$action])) {
        $js = is_array($pageJs[$action]) ? $pageJs[$action] : [$pageJs[$action]];
        foreach ($js as $file) {
            echo '<script src="' . asset('js/' . $file) . '" defer></script>';
        }
    }
    ?>
</head>

<body<?= ($action === 'create_trip' || $action === 'edit_trip' || $action === 'trip_details') ? ' class="page page--create"' : '' ?>>

<?php
// Routes that don't need header/footer wrapper
// Admin routes (unified controller) - EXCLUDE registration routes
$excludedAdminRoutes = ['admin_register', 'admin_registration_pending', 'admin_email_validation', 'admin_login'];
if (strpos($action, 'admin_') === 0 && !in_array($action, $excludedAdminRoutes)) {
    require_once __DIR__ . "/controller/AdminControllerUnified.php";
    $controller = new AdminControllerUnified();
    
    switch ($action) {
        case 'admin_process_login':
            $controller->processLogin();
            break;
        case 'admin_dashboard':
            $controller->dashboard();
            break;
        case 'admin_users':
            $controller->users();
            break;
        case 'admin_user_details':
            $controller->userDetails();
            break;
        case 'admin_delete_user':
            $controller->deleteUser();
            break;
        case 'admin_toggle_verification':
            $controller->toggleVerification();
            break;
        case 'admin_trips':
            $controller->trips();
            break;
        case 'admin_delete_trip':
            $controller->deleteTrip();
            break;
        case 'admin_vehicles':
            $controller->vehicles();
            break;
        case 'admin_profile':
            $controller->profile();
            break;
        case 'admin_profile_update':
            $controller->updateProfile();
            break;
        case 'admin_password_update':
            $controller->updatePassword();
            break;
        case 'admin_delete_account':
            $controller->deleteAccount();
            break;
        case 'admin_logout':
            $controller->logout();
            break;
        case 'admin_reset_user_password':
            $controller->resetUserPassword();
            break;
        default:
            // Redirect to dashboard for unknown admin actions
            redirect(url('index.php?action=admin_dashboard'));
            break;
    }
    exit;
}

// Old admin route (redirect to new unified system)
if ($action === 'admin') {
    redirect(url('index.php?action=admin_dashboard'));
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

    case "user_search":
        require_once __DIR__ . "/controller/UserSearchController.php";
        (new UserSearchController())->render();
        break;

    case "offers":
        require_once __DIR__ . "/controller/OffersController.php";
        (new OffersController())->render();
        break;

    case "login":
        require_once __DIR__ . "/controller/LoginController.php";
        (new LoginController())->render();
        break;
    
    case "admin_login":
        require_once __DIR__ . "/controller/AdminLoginController.php";
        (new AdminLoginController())->render();
        break;

    case "forgot_password":
        require_once __DIR__ . "/controller/ForgotPasswordController.php";
        (new ForgotPasswordController())->render();
        break;

    case "register":
        require_once __DIR__ . "/controller/RegisterController.php";
        (new RegisterController())->render();
        break;
    
    case "admin_register":
        require_once __DIR__ . "/controller/AdminRegisterController.php";
        (new AdminRegisterController())->render();
        break;
    
    case "admin_registration_pending":
        require __DIR__ . "/view/AdminRegistrationPendingView.php";
        break;
    
    case "admin_email_validation":
        require __DIR__ . "/view/AdminEmailValidationView.php";
        break;
    
    case "admin_validate_email":
        require_once __DIR__ . "/controller/AdminEmailValidationController.php";
        (new AdminEmailValidationController())->validateEmail();
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
        // Already handled at the top of the file to avoid headers already sent error
        break;

    case "create_trip":
        require_once __DIR__ . "/controller/TripFormController.php";
        (new TripFormController())->render();
        break;

    case "create_trip_submit":
        require_once __DIR__ . "/controller/TripFormController.php";
        (new TripFormController())->submit();
        break;

    case "edit_trip":
        require_once __DIR__ . "/controller/TripFormController.php";
        (new TripFormController())->renderEdit();
        break;

    case "edit_trip_submit":
        require_once __DIR__ . "/controller/TripFormController.php";
        (new TripFormController())->submitEdit();
        break;

    case "delete_trip":
        require_once __DIR__ . "/controller/TripFormController.php";
        (new TripFormController())->deleteTrip();
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

    case "my_bookings":
        require_once __DIR__ . "/controller/BookingController.php";
        (new BookingController())->myBookings();
        break;
    
    case "my_trips":
        require_once __DIR__ . "/controller/BookingController.php";
        (new BookingController())->myTrips();
        break;

    case "cancel_booking":
        require_once __DIR__ . "/controller/BookingController.php";
        (new BookingController())->cancelBooking();
        break;

    case "faq":
        require_once __DIR__ . "/controller/FAQController.php";
        (new FAQController())->render();
        break;

    case "daily_carshare":
        require_once __DIR__ . "/controller/DailyCarShareController.php";
        (new DailyCarShareController())->render();
        break;

    case "about":
        require_once __DIR__ . "/controller/AboutController.php";
        (new AboutController())->render();
        break;

    case "contact":
        require_once __DIR__ . "/controller/ContactController.php";
        (new ContactController())->render();
        break;

    case "contact_submit":
        require_once __DIR__ . "/controller/ContactController.php";
        (new ContactController())->submit();
        break;
    
    case "registration_pending":
        require_once __DIR__ . "/controller/EmailValidationController.php";
        (new EmailValidationController())->pending();
        break;
    
    case "admin_registration_pending":
        require_once __DIR__ . "/controller/EmailValidationController.php";
        (new EmailValidationController())->adminPending();
        break;
    
    case "validate_email":
        require_once __DIR__ . "/controller/EmailValidationController.php";
        (new EmailValidationController())->validate();
        break;
    
    case "validate_admin_email":
        require_once __DIR__ . "/controller/EmailValidationController.php";
        (new EmailValidationController())->validateAdmin();
        break;
    
    case "reset_password":
        require_once __DIR__ . "/controller/ForgotPasswordController.php";
        (new ForgotPasswordController())->resetPassword();
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
<?php
// Flush output buffer
ob_end_flush();
?>
