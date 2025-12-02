<?php
    //A enlever en PROD
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    //Fin à enlever

    //démarrage de la session
    session_start();

    //Chargement automatiques des controlleurs, des modèles et des vues
    spl_autoload_register(function ($class){
        $paths = [
            './controller/',
            './model/',
            './view/'
        ];
        foreach ($paths as $path) {
            $file = $path . DIRECTORY_SEPARATOR . $class .'.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    });

    //Chargement de la BDD
    Database::instanciateDb();

    //Variables de session
    $userId = $_SESSION["user_id"] ?? null; //est ce que l'utilsateur est déjà log ?
    $profilePicturePath = null;

    if ($userId) {
        $profilePicturePath = UserModel::getUserProfilePicturePath($userId);
    }
?>
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
    <link rel="stylesheet" href="./assets/styles/register.css">
    <link rel="stylesheet" href="./assets/styles/login.css">
    <script src="./script/index.js"></script>
    
    <title>CarShare</title>
</head>
<body>
    <?php
        include_once("./view/components/header.php");
    
        

        

        $action = "home";

        if(isset($_GET["action"])){
            $action = $_GET["action"];
        }

        switch ($action) {
            case "home":
                require_once("./controller/HomeController.php");
                $controller = new HomeController();
                $controller->index();
                break;
            case "search":
            case "display_search":
                require_once "./controller/SearchPageController.php";
                $controller = new SearchPageController();
                $controller->render();
                break;
            case "login":
                require_once("./controller/LoginController.php");
                $controller = new LoginController();
                $controller->render();
                break;
            case "register":
                require_once("./controller/RegisterController.php");
                $controller = new RegisterController();
                $controller->render();
                break;
            case "profile":
                require_once("./controller/ProfileController.php");
                $controller = new ProfileController();
                $controller->index();
                break;
            case "carpooling":
                require_once("./controller/CarpoolingController.php");
                $controller = new CarpoolingController();
                $controller->index();
                break;
            case "admin":
                require_once("./controller/AdminController.php");
                $controller = new AdminController();
                $controller->index();
                break;
            case "faq":
                require_once("./controller/FAQController.php");
                $controller = new FAQController();
                $controller->index();
                break;
            case "disconnect":
                session_unset();
                session_destroy();
                header("Location: index.php");
                break;
            default:
                http_response_code(404);
                echo "Page non trouvée";
                break;
            }

        include_once("./view/components/footer.html");
        include_once("./view/components/message_anchor.html");
        include_once("./view/components/anchor.html");
    ?>
</body>
<script src="./script/searchPage.js"></script>
</html>