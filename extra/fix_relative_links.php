<?php
/**
 * Script pour corriger les liens relatifs qui ne fonctionnent pas avec AlwaysData/racine
 * Remplace ?action= par index.php?action= dans les vues
 */

require_once __DIR__ . '/config.php';

echo "🔧 Correction des liens relatifs pour AlwaysData...\n\n";

$files = [
    'view/OffersView.php',
    'view/UserSearchView.php',
    'view/TripView.php'
];

foreach ($files as $file) {
    $filePath = __DIR__ . '/' . $file;
    
    if (!file_exists($filePath)) {
        echo "⚠️  Fichier non trouvé: {$file}\n";
        continue;
    }
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    $changes = 0;
    
    // Remplacements simples
    $patterns = [
        'href="?action=offers"' => 'href="<?= url(\'index.php?action=offers\') ?>"',
        'href="?action=create_trip"' => 'href="<?= url(\'index.php?action=create_trip\') ?>"',
        'href="?action=user_search"' => 'href="<?= url(\'index.php?action=user_search\') ?>"',
        'href="?action=my_trips"' => 'href="<?= url(\'index.php?action=my_trips\') ?>"',
        'href="?action=login&return_url=<?= urlencode(\'?action=offers\') ?>"' => 
            'href="<?= url(\'index.php?action=login&return_url=\' . urlencode(\'index.php?action=offers\')) ?>"',
        'href="?action=trip_details&id=<?= $offer[\'id\'] ?>"' =>
            'href="<?= url(\'index.php?action=trip_details&id=\' . $offer[\'id\']) ?>"',
    ];
    
    foreach ($patterns as $from => $to) {
        $count = 0;
        $content = str_replace($from, $to, $content, $count);
        if ($count > 0) {
            $changes += $count;
            echo "   ✓ {$file}: Remplacé '{$from}' ({$count}x)\n";
        }
    }
    
    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        echo "✅ {$file}: {$changes} modifications\n\n";
    } else {
        echo "⚪ {$file}: Aucune modification nécessaire\n\n";
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Correction terminée!\n\n";

echo "📋 Vérification de la configuration...\n";
echo "BASE_PATH actuel: " . BASE_PATH . "\n";
echo "BASE_URL actuel: " . BASE_URL . "\n\n";

echo "💡 Pour AlwaysData:\n";
echo "   - Si à la racine (www/): BASE_PATH = '/'\n";
echo "   - Si dans un sous-dossier (www/carshare/): BASE_PATH = '/carshare/'\n";
echo "   - La détection est automatique!\n\n";

echo "🧪 Test des fonctions helper:\n";
echo "   url('index.php?action=home') = " . url('index.php?action=home') . "\n";
echo "   asset('styles/main.css') = " . asset('styles/main.css') . "\n\n";
