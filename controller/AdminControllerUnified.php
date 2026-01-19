<?php
require_once __DIR__ . '/../model/AdminModelEnhanced.php';

class AdminControllerUnified {
    private $model;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model = new AdminModelEnhanced();
    }

    /**
     * Vérifie si l'utilisateur est un admin connecté
     */
    private function checkAdminAuth() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
            redirect(url('index.php?action=admin_login'));
            exit;
        }
    }

    /**
     * Dashboard principal
     */
    public function dashboard() {
        $this->checkAdminAuth();
        
        $stats = $this->model->getDashboardStats();
        $recentTransactions = $this->model->getRecentTransactions(10);
        
        $pageTitle = 'Tableau de bord';
        $currentPage = 'dashboard';
        
        ob_start();
        require_once __DIR__ . '/../view/admin/dashboard_content.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../view/admin_layout.php';
    }

    /**
     * Gestion des utilisateurs
     */
    public function users() {
        $this->checkAdminAuth();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
        
        $limit = 20;
        $users = $this->model->getUsers($page, $limit, $search, $filter);
        $totalUsers = $this->model->getUsersCount($search, $filter);
        $totalPages = ceil($totalUsers / $limit);
        
        $pageTitle = 'Utilisateurs';
        $currentPage = 'users';
        
        ob_start();
        require_once __DIR__ . '/../view/admin/users_content.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../view/admin_layout.php';
    }

    /**
     * Détails d'un utilisateur
     */
    public function userDetails() {
        $this->checkAdminAuth();
        
        if (!isset($_GET['id'])) {
            redirect(url('index.php?action=admin_users'));
            exit;
        }
        
        $userId = (int)$_GET['id'];
        $user = $this->model->getUserDetails($userId);
        
        if (!$user) {
            $_SESSION['admin_error'] = "Utilisateur introuvable";
            redirect(url('index.php?action=admin_users'));
            exit;
        }
        
        $stats = $this->model->getUserStats($userId);
        $history = $this->model->getUserHistory($userId);
        
        $pageTitle = 'Détails utilisateur #' . $userId;
        $currentPage = 'users';
        
        ob_start();
        require_once __DIR__ . '/../view/admin/user_details_content.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../view/admin_layout.php';
    }

    /**
     * Suppression d'un utilisateur
     */
    public function deleteUser() {
        $this->checkAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['user_id'])) {
            redirect(url('index.php?action=admin_users'));
            exit;
        }
        
        $userId = (int)$_POST['user_id'];
        
        if ($this->model->deleteUser($userId)) {
            $_SESSION['admin_success'] = "Utilisateur supprimé avec succès";
        } else {
            $_SESSION['admin_error'] = "Erreur lors de la suppression de l'utilisateur";
        }
        
        redirect(url('index.php?action=admin_users'));
        exit;
    }

    /**
     * Basculer la vérification d'un utilisateur
     */
    public function toggleVerification() {
        $this->checkAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['user_id'])) {
            redirect(url('index.php?action=admin_users'));
            exit;
        }
        
        $userId = (int)$_POST['user_id'];
        
        if ($this->model->toggleUserVerification($userId)) {
            $_SESSION['admin_success'] = "Statut de vérification mis à jour";
        } else {
            $_SESSION['admin_error'] = "Erreur lors de la mise à jour";
        }
        
        // Retour à la page précédente ou users par défaut
        $returnUrl = isset($_POST['return_url']) ? $_POST['return_url'] : url('index.php?action=admin_users');
        redirect($returnUrl);
        exit;
    }

    /**
     * Gestion des trajets
     */
    public function trips() {
        $this->checkAdminAuth();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
        $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
        $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
        
        $limit = 20;
        $trips = $this->model->getTrips($page, $limit, $search, $filter, $dateFrom, $dateTo);
        $totalTrips = $this->model->getTripsCount($search, $filter, $dateFrom, $dateTo);
        $totalPages = ceil($totalTrips / $limit);
        
        $pageTitle = 'Trajets';
        $currentPage = 'trips';
        
        ob_start();
        require_once __DIR__ . '/../view/admin/trips_content.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../view/admin_layout.php';
    }

    /**
     * Suppression d'un trajet
     */
    public function deleteTrip() {
        $this->checkAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['trip_id'])) {
            redirect(url('index.php?action=admin_trips'));
            exit;
        }
        
        $tripId = (int)$_POST['trip_id'];
        
        if ($this->model->deleteTrip($tripId)) {
            $_SESSION['admin_success'] = "Trajet supprimé avec succès";
        } else {
            $_SESSION['admin_error'] = "Erreur lors de la suppression du trajet";
        }
        
        redirect(url('index.php?action=admin_trips'));
        exit;
    }

    /**
     * Registre des véhicules
     */
    public function vehicles() {
        $this->checkAdminAuth();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $limit = 20;
        $vehicles = $this->model->getVehicles($page, $limit, $search);
        $totalVehicles = $this->model->getVehiclesCount($search);
        $totalPages = ceil($totalVehicles / $limit);
        
        $pageTitle = 'Véhicules';
        $currentPage = 'vehicles';
        
        ob_start();
        require_once __DIR__ . '/../view/admin/vehicles_content.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../view/admin_layout.php';
    }

    /**
     * Profil de l'admin
     */
    public function profile() {
        $this->checkAdminAuth();
        
        $adminId = $_SESSION['user_id'];
        $admin = $this->model->getAdminProfile($adminId);
        
        if (!$admin) {
            $_SESSION['admin_error'] = "Profil introuvable";
            redirect(url('index.php?action=admin_dashboard'));
            exit;
        }
        
        $pageTitle = 'Mon profil';
        $currentPage = 'profile';
        
        ob_start();
        require_once __DIR__ . '/../view/admin/profile_content.php';
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../view/admin_layout.php';
    }

    /**
     * Mise à jour du profil admin
     */
    public function updateProfile() {
        $this->checkAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(url('index.php?action=admin_profile'));
            exit;
        }
        
        $adminId = $_SESSION['user_id'];
        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? '')
        ];
        
        // Validation basique
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
            $_SESSION['admin_error'] = "Tous les champs sont obligatoires";
            redirect(url('index.php?action=admin_profile'));
            exit;
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['admin_error'] = "Email invalide";
            redirect(url('index.php?action=admin_profile'));
            exit;
        }
        
        if ($this->model->updateAdminProfile($adminId, $data)) {
            $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
            $_SESSION['admin_success'] = "Profil mis à jour avec succès";
        } else {
            $_SESSION['admin_error'] = "Erreur lors de la mise à jour du profil";
        }
        
        redirect(url('index.php?action=admin_profile'));
        exit;
    }

    /**
     * Mise à jour du mot de passe admin
     */
    public function updatePassword() {
        $this->checkAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(url('index.php?action=admin_profile'));
            exit;
        }
        
        $adminId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['admin_error'] = "Tous les champs sont obligatoires";
            redirect(url('index.php?action=admin_profile'));
            exit;
        }
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['admin_error'] = "Les mots de passe ne correspondent pas";
            redirect(url('index.php?action=admin_profile'));
            exit;
        }
        
        if (strlen($newPassword) < 8) {
            $_SESSION['admin_error'] = "Le mot de passe doit contenir au moins 8 caractères";
            redirect(url('index.php?action=admin_profile'));
            exit;
        }
        
        // Vérifier l'ancien mot de passe
        if (!$this->model->verifyAdminPassword($adminId, $currentPassword)) {
            $_SESSION['admin_error'] = "Mot de passe actuel incorrect";
            redirect(url('index.php?action=admin_profile'));
            exit;
        }
        
        // Mettre à jour le mot de passe
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        if ($this->model->updateAdminPassword($adminId, $newPasswordHash)) {
            $_SESSION['admin_success'] = "Mot de passe mis à jour avec succès";
        } else {
            $_SESSION['admin_error'] = "Erreur lors de la mise à jour du mot de passe";
        }
        
        redirect(url('index.php?action=admin_profile'));
        exit;
    }

    /**
     * Afficher le formulaire de connexion admin
     */
    public function showLogin() {
        // Si déjà connecté en tant qu'admin, rediriger vers dashboard
        if (isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
            redirect(url('index.php?action=admin_dashboard'));
            exit;
        }
        
        require_once __DIR__ . '/../view/admin_login.php';
    }

    /**
     * Traiter la connexion admin
     */
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(url('index.php?action=admin_login'));
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['admin_error'] = "Veuillez remplir tous les champs";
            redirect(url('index.php?action=admin_login'));
            exit;
        }
        
        // Vérifier les credentials admin
        $admin = $this->model->authenticateAdmin($email, $password);
        
        if ($admin) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['user_email'] = $admin['email'];
            $_SESSION['user_name'] = $admin['first_name'] . ' ' . $admin['last_name'];
            $_SESSION['is_admin'] = 1;
            $_SESSION['logged'] = true;
            
            redirect(url('index.php?action=admin_dashboard'));
            exit;
        } else {
            $_SESSION['admin_error'] = "Email ou mot de passe incorrect";
            redirect(url('index.php?action=admin_login'));
            exit;
        }
    }

    /**
     * Déconnexion admin
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        redirect(url('index.php?action=admin_login'));
        exit;
    }

    /**
     * Réinitialiser le mot de passe d'un utilisateur
     */
    public function resetUserPassword() {
        $this->checkAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['user_id'])) {
            redirect(url('index.php?action=admin_users'));
            exit;
        }
        
        $userId = (int)$_POST['user_id'];
        $newPassword = $_POST['new_password'] ?? '';
        
        if (empty($newPassword) || strlen($newPassword) < 8) {
            $_SESSION['admin_error'] = "Le mot de passe doit contenir au moins 8 caractères";
            redirect(url('index.php?action=admin_user_details&id=' . $userId));
            exit;
        }
        
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        if ($this->model->resetUserPassword($userId, $newPasswordHash)) {
            $_SESSION['admin_success'] = "Mot de passe réinitialisé avec succès";
        } else {
            $_SESSION['admin_error'] = "Erreur lors de la réinitialisation";
        }
        
        redirect(url('index.php?action=admin_user_details&id=' . $userId));
        exit;
    }
}
