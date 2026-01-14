<?php

require_once __DIR__ . '/../model/ContactModel.php';

class ContactController {
    private $contactModel;

    public function __construct() {
        $this->contactModel = new ContactModel();
    }

    public function render() {
        $success = isset($_GET['success']) ? $_GET['success'] : null;
        $error = isset($_GET['error']) ? $_GET['error'] : null;
        
        require_once __DIR__ . '/../view/ContactView.php';
        $view = new ContactView();
        $view->display($success, $error);
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /CarShare/index.php?action=contact&error=invalid_method');
            exit();
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Validation
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            header('Location: /CarShare/index.php?action=contact&error=missing_fields');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /CarShare/index.php?action=contact&error=invalid_email');
            exit();
        }

        if (strlen($message) < 10) {
            header('Location: /CarShare/index.php?action=contact&error=message_too_short');
            exit();
        }

        // Save message to database
        $result = $this->contactModel->saveMessage($name, $email, $subject, $message);

        if ($result) {
            header('Location: /CarShare/index.php?action=contact&success=1');
        } else {
            header('Location: /CarShare/index.php?action=contact&error=db_error');
        }
        exit();
    }
}
