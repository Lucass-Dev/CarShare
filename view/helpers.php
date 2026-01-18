<?php
/**
 * Helpers pour les vues
 * Ce fichier fournit des fonctions utilitaires pour toutes les vues
 */

// S'assurer que config.php est chargé
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/../config.php';
}

/**
 * Inclut les fonctions de config.php pour les vues
 * Ces fonctions sont déjà définies dans config.php :
 * - url($path)          : génère un chemin relatif
 * - full_url($path)     : génère une URL complète
 * - asset($asset)       : génère un chemin vers un asset
 */
