<?php
require_once __DIR__ . '/Database.php';

class LoginModel {
    public function send_form($values): string {
        $str = "SELECT id FROM users WHERE email=:email AND password_hash=:pass_hash";

        Database::instanciateDb();
        $stmt = Database::$db->prepare($str);
        $stmt->execute([
            ":email"=> $values["email"],
            ":pass_hash"=> hash("sha256", $values["password"])
        ]);
        $result = $stmt->fetchObject();
        if ($result) {
            $_SESSION["user_id"] = $result->id;
            $_SESSION["logged"] = true;
            session_regenerate_id(true);
            ini_set('session.gc_maxlifetime', 86400);
            header('Location: index.php');
            exit;
        }else{
            return "L'email ou le mot de passe n'est pas correct";
        }


    }
}

?>
