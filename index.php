<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href="./assets/styles/index.css" rel="stylesheet">
    <link href="./assets/styles/header.css" rel="stylesheet">
    <link href="./assets/styles/footer.css" rel="stylesheet">
    <link href="./assets/styles/searchPage.css" rel="stylesheet">
    <link href="./assets/styles/anchor.css" rel="stylesheet">
    <link href="./assets/styles/message_anchor.css" rel="stylesheet">
    <script src="./script/index.js"></script>
    
    <title>CarShare</title>
</head>
<body>
    <?php
    //A enlever en PROD
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
    //Fin à enlever
        
        require_once("./model/Database.php");
        include_once("./view/components/header.html");

        Database::instanciateDb();

        $action = "home";

        if(isset($_GET["action"])){
            $action = $_GET["action"];
        }
    ?>
        <?php
            switch ($action) {
                case "home":
                    require_once("./controller/HomeController.php");
                    $mainController = new HomeController();
                    $mainController->index();
                    break;
                case "search":
                case "display_search":
                    require_once "./controller/SearchPageController.php";
                    $searchPageController = new SearchPageController();
                    $searchPageController->render();
                    break;
                case "login":
                    require_once("./controller/LoginController.php");
                    $loginController = new LoginController();
                    $loginController->index();
                    break;
                case "register":
                    require_once("./controller/RegisterController.php");
                    $registerController = new RegisterController();
                    $registerController->index();
                    break;
                case "profile":
                    require_once("./controller/ProfileController.php");
                    $profileController = new ProfileController();
                    $profileController->index();
                    break;
                case "carpooling":
                    require_once("./controller/CarpoolingController.php");
                    $carpoolingController = new CarpoolingController();
                    $carpoolingController->index();
                    break;
                case "admin":
                    require_once("./controller/AdminController.php");
                    $adminController = new AdminController();
                    $adminController->index();
                    break;
                case "faq":
                    require_once("./controller/FAQController.php");
                    $faqController = new FAQController();
                    $faqController->index();
                    break;
                case "utils":
                    require_once("./model/Utils.php");
                    break;
                default:
                    http_response_code(404);
                    echo "Page non trouvée";
                    break;
                }
            
        ?>
    <?php
        include_once("./view/components/footer.html");
        include_once("./view/components/message_anchor.html");
        include_once("./view/components/anchor.html");
    ?>
</body>
<script src="./script/searchPage.js"></script>
</html>