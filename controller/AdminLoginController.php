<?php
require_once __DIR__ . '/../model/LoginModel.php';
require_once __DIR__ . '/../model/AdminModel.php';

class AdminLoginController {
    private $loginModel;
    private $adminModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->loginModel = new LoginModel();
        $this->adminModel = new AdminModel();
    }

    /**
     * Afficher la page de connexion admin
     */
    public function render() {
        // Si déjà connecté en tant qu'admin, rediriger vers le dashboard
        if (isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
            redirect(url('index.php?action=admin_dashboard'));
            exit;
        }

        // Si c'est un POST, traiter la connexion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
            return;
        }

        // Sinon, afficher le formulaire de connexion
        require_once __DIR__ . '/../view/admin_login.php';
    }

    /**
     * Traiter la soumission du formulaire de connexion
     */
    private function processLogin() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation basique
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Veuillez remplir tous les champs";
            redirect(url('index.php?action=admin_login'));
            exit;
        }

        // Vérifier l'email et le mot de passe
        $user = $this->loginModel->authenticate($email, $password);

        if (!$user) {
            $_SESSION['error'] = "Email ou mot de passe incorrect";
            redirect(url('index.php?action=admin_login'));
            exit;
        }

        // Vérifier si l'utilisateur est admin
        if (!isset($user['is_admin']) || $user['is_admin'] != 1) {
            $_SESSION['error'] = "Accès non autorisé - compte administrateur requis";
            redirect(url('index.php?action=admin_login'));
            exit;
        }

        // Vérifier si le compte est validé
        if (!$user['is_validated']) {
            $_SESSION['error'] = "Votre compte n'a pas encore été validé. Veuillez vérifier votre email.";
            redirect(url('index.php?action=admin_login'));
            exit;
        }

        // Connexion réussie - créer la session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['is_admin'] = 1;
        $_SESSION['login_time'] = time();

        // Rediriger vers le dashboard admin
        redirect(url('index.php?action=admin_dashboard'));
        exit;
    }

    /**
     * Déconnecter l'admin
     */
    public function logout() {
        session_unset();
        session_destroy();
        redirect(url('index.php?action=admin_login'));
        exit;
    }
}
