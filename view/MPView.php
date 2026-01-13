<?php
    class MPView{

        static function display_MP(array $resumes){
        ?>
            
                <div class="mp-resume-container">
                    <?php
                        foreach($resumes as $resume){
                    ?>
                    
                        <a class="resume" href="?controller=profile&action=mp&chat_with=<?php echo $resume["id_conv"] ?>">
                            <div class="mp-photo">
                                    <?php $profilePath = file_exists(UPP_BASE_PATH . $resume["profile_picture_path"]) ? UPP_BASE_PATH . $resume["profile_picture_path"] : "assets/upp/default_pp.svg"; ?>

                                <img src="<?php echo $profilePath?>" alt="<?php echo $resume["first_name"]?>">
                                <span><?php echo $resume["first_name"]?></span>
                            </div>
                            <span><?php echo $resume["content"]?></span>
                        </a>
                    <?php
                        }
                    ?>
                </div>
        <?php
        }

        static public function display_discussion($discussion){
            ?>
            <div class="mp-window-container">
                <div class="messages-container">
            <?php
                global $user_id;
                foreach($discussion as $key => $value){
            ?>
                    <div class="message <?php echo $value["sender_id"] == $user_id ? "right-message" : "left-message" ?>">
                        <span class="sender-name <?php echo $value["sender_id"] == $user_id ? "right-message" : "left-message" ?>"><?php echo $value["first_name"]?> a dit :</span>
                        <span class="sender-message <?php echo $value["sender_id"] == $user_id ? "send" : "received" ?>"><?php echo $value["content"]?></span>
                        <span class="send-time <?php echo $value["sender_id"] == $user_id ? "right-message" : "left-message" ?>"><?php echo $value["send_at"]?></span>
                    </div>
                <?php
                }
                ?>
                </div>
                <form class="message-input-form" controller="?controller=profile&action=mp&chat_with=<?php echo $_GET['chat_with']?>" method="POST">
                    <input type="text" name="sended_message_content" id="sended_message_content" placeholder="Écrivez un message...">
                    <button type="submit">Envoyer</button>
                </form>
            </div>
            <?php
        }
    }
?>