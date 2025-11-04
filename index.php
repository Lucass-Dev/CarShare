<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href="./assets/styles/index.css" rel="stylesheet">
    <script src="./script/index.js"></script>
    <title>CarShare</title>
</head>
<body>
    <?php
    

        $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        echo $path;

        switch ($path) {
            case "/home":
                require "./controller/HomeController.php";
                $mainController = new HomeController();
                $mainController->index();
                break;
            default:
                http_response_code(404);
                echo "Page non trouvée";
                break;
            }

    ?>
</body>
</html>

