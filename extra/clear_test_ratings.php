<?php
/**
 * Script pour supprimer les données de test de la table ratings
 * À exécuter une seule fois pour nettoyer les notations de test
 */

session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/Database.php';

if (!isset($_SESSION['logged']) || !$_SESSION['logged']) {
    die('Vous devez être connecté');
}

$userId = $_SESSION['user_id'];

// Vérification de sécurité
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Supprimer les notations de test</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
            .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin: 20px 0; }
            .info { background: #d1ecf1; border: 1px solid #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0; }
            button { background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
            button:hover { background: #c82333; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background: #f8f9fa; }
        </style>
    </head>
    <body>
        <h1>🧹 Nettoyage des notations de test</h1>
        
        <div class="info">
            <strong>ℹ️ Information</strong>
            <p>Ce script supprime toutes les notations de test de la base de données qui empêchent de noter les trajets.</p>
        </div>

        <?php
        try {
            $db = Database::getDb();
            
            // Afficher les notations actuelles de l'utilisateur
            $stmt = $db->prepare("
                SELECT r.*, c.start_date, u.first_name, u.last_name
                FROM ratings r
                LEFT JOIN carpoolings c ON r.carpooling_id = c.id
                LEFT JOIN users u ON c.provider_id = u.id
                WHERE r.rater_id = ?
                ORDER BY r.id DESC
            ");
            $stmt->execute([$userId]);
            $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($ratings)) {
                echo "<h2>Vos notations actuelles (qui seront supprimées) :</h2>";
                echo "<table>";
                echo "<tr><th>Rating ID</th><th>Trajet ID</th><th>Note</th><th>Commentaire</th><th>Conducteur</th></tr>";
                foreach ($ratings as $rating) {
                    echo "<tr>";
                    echo "<td>{$rating['id']}</td>";
                    echo "<td>{$rating['carpooling_id']}</td>";
                    echo "<td>{$rating['rating']}/5</td>";
                    echo "<td>" . htmlspecialchars($rating['content'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars(($rating['first_name'] ?? '') . ' ' . ($rating['last_name'] ?? '')) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Aucune notation existante pour votre compte.</p>";
            }
            
        } catch (Exception $e) {
            echo "<div class='warning'>Erreur: " . $e->getMessage() . "</div>";
        }
        ?>

        <div class="warning">
            <strong>⚠️ Attention</strong>
            <p>Cette action est irréversible. Toutes vos notations seront supprimées.</p>
        </div>

        <form method="POST">
            <button type="submit">Supprimer mes notations</button>
        </form>

        <p><a href="javascript:history.back()">← Retour</a></p>
    </body>
    </html>
    <?php
    exit;
}

// POST request - effectuer la suppression
try {
    $db = Database::getDb();
    
    $stmt = $db->prepare("DELETE FROM ratings WHERE rater_id = ?");
    $stmt->execute([$userId]);
    
    $count = $stmt->rowCount();
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Suppression terminée</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; text-align: center; }
            .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 5px; margin: 20px 0; }
            a { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
            a:hover { background: #0056b3; }
        </style>
    </head>
    <body>
        <div class='success'>
            <h2>✅ Suppression réussie</h2>
            <p>$count notation(s) supprimée(s).</p>
            <p>Vous pouvez maintenant noter vos trajets normalement.</p>
        </div>
        <a href='../index.php?action=history'>Retour à l'historique</a>
    </body>
    </html>";
    
} catch (Exception $e) {
    echo "<!DOCTYPE html>
    <html>
    <head><title>Erreur</title></head>
    <body style='font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px;'>
        <h2 style='color: #dc3545;'>❌ Erreur</h2>
        <p>" . htmlspecialchars($e->getMessage()) . "</p>
        <a href='javascript:history.back()'>← Retour</a>
    </body>
    </html>";
}
