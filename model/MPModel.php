<?php
    class MPModel{

        public static function sendMessage($user_id, $message_content, $discussion){
            $str = "INSERT INTO private_message (id_conv, sender_id, content, send_at) VALUES (:id_conv, :sender_id, :content, NOW())";
            $stmt = Database::$db->prepare($str);
            $stmt->execute([
                ":sender_id"=> $user_id,
                ":content"=> $message_content,
                ":id_conv" => $discussion
            ]);
            $_POST= array();
        }
        public static function getResumes($user_id): array{
            // TODO: La table 'conversations' n'existe pas encore dans la BDD
            // Retourner un tableau vide temporairement
            return [];
            
            /* Code original à réactiver quand la table sera créée :
            $return = [];
            $str = "SELECT c.*, p.*, u.first_name
                    FROM conversations c
                    INNER JOIN private_message p ON p.id_conv = c.id
                    INNER JOIN (
                        SELECT id_conv, MAX(send_at) AS last_send_at
                        FROM private_message GROUP BY id_conv
                        ) pm_last ON pm_last.id_conv = p.id_conv
                    AND pm_last.last_send_at = p.send_at
                    INNER JOIN users u ON u.id = (CASE WHEN c.user1_id=:user_id THEN c.user2_id ELSE c.user1_id END)
                    WHERE c.user1_id=:user_id
                    OR c.user2_id=:user_id";
            $stmt = Database::$db->prepare($str);
            $stmt->execute([":user_id"=> $user_id]);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($res){
                foreach($res as $key => $value){
                    $return[$key] = $value;
                }
            }
            return $return;
            */
        }

        public static function getDiscussion($user_id, $conversation_id): array{
            $return = [];
            $str = "SELECT * FROM conversations WHERE id=:conv_id AND (user1_id=:user_id OR user2_id=:user_id)";
            
            $stmt = Database::$db->prepare($str);
            $stmt->execute([
                ":user_id"=> $user_id,
                ":conv_id"=> $conversation_id
                ]);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($res){
                $str = "SELECT content, sender_id, send_at, first_name
                        FROM private_message
                        INNER JOIN conversations ON private_message.id_conv=conversations.id
                        INNER JOIN users u1 ON u1.id=private_message.sender_id
                        WHERE conversations.id=:id_conv
                        ORDER BY send_at ASC;";
                $stmt = Database::$db->prepare($str);
                $stmt->execute([
                    ":id_conv"=> $conversation_id
                    ]);
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $res;
            }

            
            return $return;
        }
    }
?>