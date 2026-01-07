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
    $user_id = $_SESSION["user_id"] ?? null; //est ce que l'utilsateur est déjà log ?
    $profilePicturePath = null;

    if ($user_id) {
        $profilePicturePath = UserModel::getUserProfilePicturePath($user_id);
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href="./assets/styles/main.css" rel="stylesheet">
    <script src="./script/index.js"></script>
    <title>CarShare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php
        include_once("./view/components/header.php");
    
        
?>
<main>
    <?php
        $controller = "home";

        if(isset($_GET["controller"])){
            $controller = $_GET["controller"];
        }

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
                $controller->render();
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
            default:
                http_response_code(404);
                echo "Page non trouvée";
                break;
            }


    ?>
</main>
<?php
        include_once("./view/components/footer.php");
        include_once("./view/components/message_anchor.html");
        ?>
</body>
<script src="./script/searchPage.js"></script>
<script src="./script/profile.js"></script>
<script src="./script/create-trip.js"></script>
</html>
