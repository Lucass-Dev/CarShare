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
    <link href="./assets/styles/header.css" rel="stylesheet">
    <link href="./assets/styles/footer.css" rel="stylesheet">
    <link href="./assets/styles/index.css" rel="stylesheet">
    <link href="./assets/styles/message_anchor.css" rel="stylesheet">

    <link rel="stylesheet" id="dynamic-css">
    <title>CarShare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<body>
    <?php
        include_once("./view/components/header.php");
    
        
?>
<main>
    <?php
    if (isset($_GET["controller"])) {
        $controller = $_GET["controller"] ;
    }else{
        $controller = "home";
    }

    switch ($controller) {
        case "home":
            $controller = new HomeController();
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
        default:
            http_response_code(404);
            echo "Page non trouvée";
            break;
        
    }


    ?>
</main>
<?php
        include_once("./view/components/message_anchor.html");
        ?>
</body>
<script src="./script/index.js" id="script" defer></script>
</html>
