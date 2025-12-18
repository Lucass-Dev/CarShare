<?php
    class UserModel{
        static public function getUserProfilePicturePath($id){
            // La colonne profile_picture_path n'existe pas dans la base de données
            // Retourne null ou une image par défaut
            return null;
        }
    }
?>