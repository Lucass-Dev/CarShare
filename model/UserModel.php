<?php
    class UserModel{
        static public function getUserProfilePicturePath($id){
            $str = "SELECT profile_picture_path FROM users WHERE id=:id";
            $stmt = Database::getDb()->prepare($str);
            $stmt->execute([
                ":id"=> $id
            ]);
            $result = $stmt->fetchObject();
            return $result->profile_picture_path;
        }
    }
?>