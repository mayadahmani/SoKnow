<?php
// index.php - Point d'entrée unique (Le Routeur)

// 1. Sécurité et Session
session_start();

// 2. Chargement de l'Autoloader
// Vérifie bien que le nom du fichier est autoload.php et pas autoloader.php !
require_once __DIR__ . '/config/autoloader.php';

// 3. Inclusion de la connexion DB (à décommenter quand db.php sera prêt)
// require_once __DIR__ . '/config/db.php'; 


// ==========================================
// --- LE HEADER (Haut de page) ---
// S'affichera sur toutes les pages
// ==========================================
include 'includes/header.php'; 


// 4. Récupération de la page (ex: index.php?page=agenda)
$page = isset($_GET['page']) ? $_GET['page'] : 'home';


// 5. Système d'aiguillage (Le contenu qui change)
echo '<div id="content">'; // Optionnel : pour englober ton contenu principal
switch ($page) {
    case 'home':
        include 'views/home.php';
        break;

    case 'search':
      
        include 'views/search.php';
        break;

    case 'agenda':
        
        include 'views/agenda.php';
        break;

    case 'login':
        include 'views/login.php';
        break;

    default:
        // Si la page n'existe pas
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
        break;
}
echo '</div>';


// ==========================================
// --- LE FOOTER (Bas de page) ---
// S'affichera sur toutes les pages
// ==========================================
include 'includes/footer.php'; 
?>
