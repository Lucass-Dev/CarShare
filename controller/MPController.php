<?php
    class MPController{
        public function render(){
            $resumes = [];
            global $user_id;
            
            ?>
            <div class="mp-page-container">
                <?php
                if (isset($_GET['send']) && $_GET['send'] == 'true') {
                if (isset($_POST['sended_message_content']) && $_POST['sended_message_content'] !== "" && isset($_GET['chat_with'])) {
                    MPModel::sendMessage($user_id, $_POST['sended_message_content'], $_GET['chat_with']);
                    $_POST= array();
                }
            }
                    if ($user_id && $user_id !== '') {
                        $resumes = MPModel::getResumes($user_id);
                    }
                    MPView::display_MP($resumes);
                    if ($user_id && $user_id !== '' && isset($_GET['chat_with']) && $_GET['chat_with'] !== '') {
                        $discussion = MPModel::getDiscussion($user_id, $_GET['chat_with']);
                        MPView::display_discussion($discussion);
                    }
                ?>
            </div>
            <?php
        }
    }
?>