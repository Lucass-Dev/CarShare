<?php
require_once __DIR__ . "/../model/BookingModel.php";
require_once __DIR__ . "/../model/CarpoolingModel.php";
require_once __DIR__ . "/../model/MessagingModel.php";
require_once __DIR__ . "/../model/EmailService.php";
require_once __DIR__ . "/../model/Database.php";

class PaymentController {

    public function render() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $returnUrl = urlencode($_SERVER['REQUEST_URI']);
            header('Location: ' . url('index.php?action=login&return_url=' . $returnUrl));
            exit();
        }

        $carpoolingId = isset($_GET['carpooling_id']) ? (int) $_GET['carpooling_id'] : null;
        if (!$carpoolingId || $carpoolingId <= 0) {
            header('Location: ' . url('index.php?action=home'));
            exit();
        }

        $carpoolingModel = new CarpoolingModel();
        $carpooling = $carpoolingModel->getCarpoolingById($carpoolingId);

        if (!$carpooling) {
            header('Location: ' . url('index.php?action=home'));
            exit();
        }

        // Calculer le nombre de places déjà réservées pour ce trajet
        $bookingModel = new BookingModel();
        $bookedPlacesCount = $bookingModel->getBookedPlacesCount($carpoolingId);

        $error = null;
        $success = null;

        // CSRF token pour le formulaire de réservation
        if (empty($_SESSION['payment_csrf_token'])) {
            $_SESSION['payment_csrf_token'] = bin2hex(random_bytes(32));
        }
        $csrfToken = $_SESSION['payment_csrf_token'];

        // Traitement du formulaire de réservation
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation CSRF
            $postedToken = $_POST['csrf_token'] ?? '';
            if (empty($postedToken) || !hash_equals($_SESSION['payment_csrf_token'], $postedToken)) {
                $error = "Votre session a expiré ou le formulaire n'est pas valide. Veuillez recharger la page et réessayer.";
            } else {
                // Validate card information
                $errors = [];
                
                // Card holder validation
                $cardHolder = $this->sanitizeInput($_POST['card_holder'] ?? '');
                if (empty($cardHolder)) {
                    $errors[] = "Le nom du titulaire est obligatoire";
                } else if (!$this->validateCardHolder($cardHolder)) {
                    $errors[] = "Le nom du titulaire contient des caractères invalides";
                }
                
                // Card number validation
                $cardNumber = preg_replace('/\s/', '', $_POST['card_number'] ?? '');
                if (empty($cardNumber)) {
                    $errors[] = "Le numéro de carte est obligatoire";
                } else if (!$this->validateCardNumber($cardNumber)) {
                    $errors[] = "Le numéro de carte est invalide";
                }
                
                // Expiry date validation
                $expiryDate = $_POST['expiry_date'] ?? '';
                if (empty($expiryDate)) {
                    $errors[] = "La date d'expiration est obligatoire";
                } else if (!$this->validateExpiryDate($expiryDate)) {
                    $errors[] = "La date d'expiration est invalide ou expirée";
                }
                
                // CVV validation
                $cvv = $_POST['cvv'] ?? '';
                if (empty($cvv)) {
                    $errors[] = "Le CVV est obligatoire";
                } else if (!$this->validateCVV($cvv)) {
                    $errors[] = "Le CVV est invalide";
                }
                
                // Terms acceptance
                $acceptedTerms = isset($_POST['accept_terms']);
                if (!$acceptedTerms) {
                    $errors[] = "Vous devez accepter les CGV, les CGU et les Mentions légales";
                }
                
                // Seats count validation
                $seatsCount = isset($_POST['seats_count']) ? (int)$_POST['seats_count'] : 1;
                if ($seatsCount < 1) {
                    $errors[] = "Le nombre de places doit être au moins 1";
                } else if ($seatsCount > $carpooling['available_places']) {
                    $errors[] = "Le nombre de places demandé n'est plus disponible";
                }
                
                if (!empty($errors)) {
                    $error = implode('<br>', $errors);
                } else {
                    $bookingModel = new BookingModel();
                    $bookingId = $bookingModel->createMultipleBookings($_SESSION['user_id'], $carpoolingId, $seatsCount);

                    if ($bookingId) {
                        // Invalide le token CSRF après succès pour éviter la réutilisation
                        unset($_SESSION['payment_csrf_token']);

                        // Envoyer notification privée au conducteur (ne pas bloquer si erreur)
                        try {
                            $this->sendBookingNotification($_SESSION['user_id'], $carpooling, $seatsCount);
                        } catch (Exception $e) {
                            error_log("Erreur notification réservation : " . $e->getMessage());
                        }
                        
                        // Envoyer emails de confirmation (ne pas bloquer si erreur)
                        try {
                            $this->sendBookingEmails($_SESSION['user_id'], $carpooling, $seatsCount);
                        } catch (Exception $e) {
                            error_log("Erreur emails confirmation : " . $e->getMessage());
                        }

                        // Redirection immédiate vers la page de confirmation
                        header('Location: ' . url('index.php?action=booking_confirmation&booking_id=' . $bookingId));
                        exit();
                    } else {
                        $error = "Impossible de créer la réservation. Le trajet est peut-être complet.";
                    }
                }
            }
        }

        require __DIR__ . "/../view/PaymentView.php";
    }
    
    /**
     * Sanitize input to prevent XSS
     */
    private function sanitizeInput($input) {
        if (!is_string($input)) {
            return '';
        }
        
        // Remove control characters
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        
        // Trim whitespace
        $input = trim($input);
        
        return $input;
    }
    
    /**
     * Validate card holder name
     */
    private function validateCardHolder($name) {
        // Check for SQL injection patterns
        if (preg_match('/(\bSELECT\b|\bINSERT\b|\bUPDATE\b|\bDELETE\b|\bDROP\b|--|;|\/\*)/i', $name)) {
            return false;
        }
        
        // Check for special characters (allow only letters, spaces, hyphens, apostrophes)
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'-]+$/', $name)) {
            return false;
        }
        
        // Length check
        if (strlen($name) < 2 || strlen($name) > 50) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate card number using Luhn algorithm
     */
    private function validateCardNumber($number) {
        // Check if only digits
        if (!preg_match('/^\d+$/', $number)) {
            return false;
        }
        
        // Check length (16 digits for most cards)
        if (strlen($number) !== 16) {
            return false;
        }
        
        // Luhn algorithm
        $sum = 0;
        $numDigits = strlen($number);
        $parity = $numDigits % 2;
        
        for ($i = 0; $i < $numDigits; $i++) {
            $digit = (int)$number[$i];
            
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
        }
        
        return ($sum % 10) === 0;
    }
    
    /**
     * Validate expiry date (MM/YY format)
     */
    private function validateExpiryDate($date) {
        // Check format MM/YY
        if (!preg_match('/^\d{2}\/\d{2}$/', $date)) {
            return false;
        }
        
        list($month, $year) = explode('/', $date);
        $month = (int)$month;
        $year = (int)$year;
        
        // Validate month
        if ($month < 1 || $month > 12) {
            return false;
        }
        
        // Check if expired
        $currentYear = (int)date('y'); // Last 2 digits of year
        $currentMonth = (int)date('m');
        
        if ($year < $currentYear || ($year === $currentYear && $month < $currentMonth)) {
            return false;
        }
        
        // Check if too far in future (10 years max)
        if ($year > $currentYear + 10) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate CVV (3 or 4 digits)
     */
    private function validateCVV($cvv) {
        // Check if only digits
        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Envoyer un message automatique au conducteur après réservation
     */
    private function sendBookingNotification($bookerId, $carpooling, $seatsCount = 1) {
        try {
            $messagingModel = new MessagingModel();
            
            // Créer ou récupérer la conversation
            $conversationId = $messagingModel->getOrCreateConversation($bookerId, $carpooling['provider_id']);
            
            // Formater le message pour le conducteur (sans emoji)
            $message = "Nouvelle reservation pour votre trajet\n\n";
            $message .= "Depart : " . $carpooling['start_location'] . "\n";
            $message .= "Arrivee : " . $carpooling['end_location'] . "\n";
            $message .= "Date : " . date('d/m/Y', strtotime($carpooling['start_date'])) . "\n";
            $message .= "Heure : " . date('H:i', strtotime($carpooling['start_date'])) . "\n";
            $message .= "Nombre de places reservees : " . $seatsCount . "\n";
            $message .= "Prix total : " . number_format($carpooling['price'] * $seatsCount, 2) . " EUR\n\n";
            $message .= "Un passager vient de reserver " . $seatsCount . " place" . ($seatsCount > 1 ? 's' : '') . " sur ce trajet.\n";
            $message .= "Vous pouvez gerer cette reservation depuis votre tableau de bord.\n\n";
            $message .= "Bon voyage !";
            
            // Envoyer le message au conducteur
            $messagingModel->sendMessage($conversationId, $bookerId, $carpooling['provider_id'], $message);
            
            return true;
        } catch (Exception $e) {
            error_log("Erreur envoi message de confirmation : " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Envoyer les emails de confirmation de réservation
     */
    private function sendBookingEmails($bookerId, $carpooling, $seatsCount = 1) {
        try {
            $emailService = new EmailService();
            $db = Database::getDb();
            
            // Récupérer les infos du passager (booker)
            $stmt = $db->prepare("SELECT email, first_name, last_name FROM users WHERE id = ?");
            $stmt->execute([$bookerId]);
            $booker = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Récupérer les infos du conducteur (provider)
            $stmt = $db->prepare("SELECT email, first_name, last_name FROM users WHERE id = ?");
            $stmt->execute([$carpooling['provider_id']]);
            $provider = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$booker || !$provider) {
                error_log("Erreur : impossible de récupérer les infos utilisateur pour l'envoi d'emails");
                return false;
            }
            
            // 1. Email au passager
            $bookerEmail = $this->buildBookerConfirmationEmail($booker, $provider, $carpooling, $seatsCount);
            $mailSent1 = $emailService->sendBookingConfirmationToBooker(
                $booker['email'],
                $booker['first_name'] . ' ' . $booker['last_name'],
                $bookerEmail['subject'],
                $bookerEmail['body'],
                $bookerEmail['altBody']
            );
            
            // 2. Email au conducteur
            $providerEmail = $this->buildProviderNotificationEmail($booker, $provider, $carpooling, $seatsCount);
            $mailSent2 = $emailService->sendBookingNotificationToProvider(
                $provider['email'],
                $provider['first_name'] . ' ' . $provider['last_name'],
                $providerEmail['subject'],
                $providerEmail['body'],
                $providerEmail['altBody']
            );
            
            return $mailSent1 && $mailSent2;
        } catch (Exception $e) {
            error_log("Erreur envoi emails de confirmation : " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Construire l'email de confirmation pour le passager
     */
    private function buildBookerConfirmationEmail($booker, $provider, $carpooling, $seatsCount = 1) {
        $subject = "Reservation confirmee - CarShare";
        
        $totalPrice = $carpooling['price'] * $seatsCount;
        
        $body = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .trip-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #4CAF50; }
        .detail-row { margin: 10px 0; }
        .label { font-weight: bold; color: #666; }
        .footer { text-align: center; color: #64748b; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reservation Confirmee</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>' . htmlspecialchars($booker['first_name'] . ' ' . $booker['last_name']) . '</strong>,</p>
            <p>Votre reservation a ete confirmee avec succes. Voici les details de votre trajet :</p>
            
            <div class="trip-details">
                <div class="detail-row">
                    <span class="label">Depart :</span> ' . htmlspecialchars($carpooling['start_location']) . '
                </div>
                <div class="detail-row">
                    <span class="label">Arrivee :</span> ' . htmlspecialchars($carpooling['end_location']) . '
                </div>
                <div class="detail-row">
                    <span class="label">Date :</span> ' . date('d/m/Y', strtotime($carpooling['start_date'])) . '
                </div>
                <div class="detail-row">
                    <span class="label">Heure de depart :</span> ' . date('H:i', strtotime($carpooling['start_date'])) . '
                </div>
                <div class="detail-row">
                    <span class="label">Conducteur :</span> ' . htmlspecialchars($provider['first_name'] . ' ' . $provider['last_name']) . '
                </div>
                <div class="detail-row">
                    <span class="label">Nombre de places reservees :</span> ' . $seatsCount . '
                </div>
                <div class="detail-row">
                    <span class="label">Prix par place :</span> ' . number_format($carpooling['price'], 2) . ' EUR
                </div>
                <div class="detail-row">
                    <span class="label">Prix total :</span> <strong>' . number_format($totalPrice, 2) . ' EUR</strong>
                </div>
            </div>
            
            <p>Vous pouvez contacter votre conducteur via la messagerie CarShare.</p>
            
            <p>Bon voyage !</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 CarShare - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>';
        
        $altBody = "Bonjour " . $booker['first_name'] . " " . $booker['last_name'] . ",\n\n";
        $altBody .= "Votre reservation a ete confirmee avec succes !\n\n";
        $altBody .= "Details du trajet :\n";
        $altBody .= "- Depart : " . $carpooling['start_location'] . "\n";
        $altBody .= "- Arrivee : " . $carpooling['end_location'] . "\n";
        $altBody .= "- Date : " . date('d/m/Y', strtotime($carpooling['start_date'])) . "\n";
        $altBody .= "- Heure : " . date('H:i', strtotime($carpooling['start_date'])) . "\n";
        $altBody .= "- Conducteur : " . $provider['first_name'] . " " . $provider['last_name'] . "\n";
        $altBody .= "- Nombre de places : " . $seatsCount . "\n";
        $altBody .= "- Prix par place : " . number_format($carpooling['price'], 2) . " EUR\n";
        $altBody .= "- Prix total : " . number_format($totalPrice, 2) . " EUR\n\n";
        $altBody .= "Bon voyage !\n\n";
        $altBody .= "L'equipe CarShare";
        
        return ['subject' => $subject, 'body' => $body, 'altBody' => $altBody];
    }
    
    /**
     * Construire l'email de notification pour le conducteur
     */
    private function buildProviderNotificationEmail($booker, $provider, $carpooling, $seatsCount = 1) {
        $subject = "Nouvelle reservation sur votre trajet - CarShare";
        
        $totalPrice = $carpooling['price'] * $seatsCount;
        
        $body = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
        .trip-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2196F3; }
        .detail-row { margin: 10px 0; }
        .label { font-weight: bold; color: #666; }
        .alert-box { background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; color: #64748b; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚗 Nouvelle Réservation !</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>' . htmlspecialchars($provider['first_name'] . ' ' . $provider['last_name']) . '</strong>,</p>
            <p>Bonne nouvelle ! Un passager vient de réserver une place sur votre trajet :</p>
            
            <div class="trip-details">
                <div class="detail-row">
                    <span class="label">📍 Départ :</span> ' . htmlspecialchars($carpooling['start_location']) . '
                </div>
                <div class="detail-row">
                    <span class="label">📍 Arrivée :</span> ' . htmlspecialchars($carpooling['end_location']) . '
                </div>
                <div class="detail-row">
                    <span class="label">📅 Date :</span> ' . date('d/m/Y', strtotime($carpooling['start_date'])) . '
                </div>
                <div class="detail-row">
                    <span class="label">🕐 Heure de départ :</span> ' . date('H:i', strtotime($carpooling['start_date'])) . '
                </div>
                <div class="detail-row">
                    <span class="label">👤 Passager :</span> ' . htmlspecialchars($booker['first_name'] . ' ' . $booker['last_name']) . '
                </div>
                <div class="detail-row">
                    <span class="label">💰 Prix :</span> ' . number_format($carpooling['price'], 2) . ' €
                </div>
            </div>
            
            <div class="alert-box">
                <p><strong>📬 Un message vous attend dans votre messagerie CarShare</strong></p>
                <p>Vous pouvez contacter votre passager pour finaliser les détails du trajet.</p>
            </div>
            
            <p><strong>Gestion de la reservation :</strong><br>
Vous pouvez gerer cette reservation (annuler si necessaire) depuis votre tableau de bord CarShare.</p>
            
            <p>Bonne route !</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 CarShare - Tous droits reserves</p>
        </div>
    </div>
</body>
</html>';
        
        $altBody = "Bonjour " . $provider['first_name'] . " " . $provider['last_name'] . ",\n\n";
        $altBody .= "Bonne nouvelle ! Un passager vient de reserver " . $seatsCount . " place" . ($seatsCount > 1 ? 's' : '') . " sur votre trajet :\n\n";
        $altBody .= "Details du trajet :\n";
        $altBody .= "- Depart : " . $carpooling['start_location'] . "\n";
        $altBody .= "- Arrivee : " . $carpooling['end_location'] . "\n";
        $altBody .= "- Date : " . date('d/m/Y', strtotime($carpooling['start_date'])) . "\n";
        $altBody .= "- Heure : " . date('H:i', strtotime($carpooling['start_date'])) . "\n";
        $altBody .= "- Passager : " . $booker['first_name'] . " " . $booker['last_name'] . "\n";
        $altBody .= "- Nombre de places : " . $seatsCount . "\n";
        $altBody .= "- Prix par place : " . number_format($carpooling['price'], 2) . " EUR\n";
        $altBody .= "- Prix total : " . number_format($totalPrice, 2) . " EUR\n\n";
        $altBody .= "Un message vous attend dans votre messagerie CarShare.\n";
        $altBody .= "Vous pouvez gerer cette reservation depuis votre tableau de bord.\n\n";
        $altBody .= "Bonne route !\n\n";
        $altBody .= "L'équipe CarShare";
        
        return ['subject' => $subject, 'body' => $body, 'altBody' => $altBody];
    }
}
