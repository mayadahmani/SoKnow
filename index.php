<?php
// index.php
session_start();

require_once __DIR__ . '/config/database.php'; 
require_once __DIR__ . '/config/autoloader.php'; 

$page = $_GET['page'] ?? 'home';
$donnees = []; 

switch ($page) {
    case 'home':
        $vue = new VueAccueil();
        $vue->afficher($donnees);
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User($pdo);
            $user = $userModel->getByEmail($_POST['email']);
            
            if ($user && password_verify($_POST['password'], $user['password_hash'])) {
                $_SESSION['user'] = $user;
                $_SESSION['user_id'] = $user['id'];
                header("Location: index.php?page=dashboard");
                exit;
            } else {
                $donnees['erreur'] = "Identifiants incorrects.";
            }
        }
        $vue = new VueLogin();
        $vue->afficher($donnees);
        break;

    case 'register':
        $userModel = new User($pdo);
        $donnees = [];

        // 1. ACTION : Réception de l'Étape 1 (Infos de base + BIO)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['first_name'])) {
            $email = trim($_POST['email']);
            
            if ($userModel->emailExists($email)) {
                $donnees['erreur'] = "Cet e-mail est déjà utilisé par un autre membre.";
                $vue = new VueRegister1();
                $vue->afficher($donnees);
            } else {
                // On stocke TOUT $_POST (y compris la bio) en session
                $_SESSION['temp_user'] = $_POST;
                $vue = new VueRegister2();
                $vue->afficher();
            }
        } 

        // 2. ACTION : Réception de l'Étape 2 (Rôle, Ville, Compétences)
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['skills'])) {
            
            if (!isset($_SESSION['temp_user'])) {
                header("Location: index.php?page=register");
                exit;
            }

            $s1 = $_SESSION['temp_user']; 
            
            $skills = $_POST['skills'] ?? '';
            $role = $_POST['role'] ?? 'student';
            $location = $_POST['location'] ?? '';
            $languages = $_POST['languages'] ?? '';
            
            // On récupère la bio qui était stockée en session à l'étape 1
            $bio = $s1['bio'] ?? ''; 

            // Étape A : Création de l'utilisateur avec la BIO
            $newUserId = $userModel->register(
                $s1['first_name'],
                $s1['last_name'],
                $s1['email'],
                $s1['password'],
                $role,
                $location,
                $languages,
                $bio // <--- On passe la bio ici
            );

            if ($newUserId) {
                $userModel->addUserSkills($newUserId, $skills, $role);

                $_SESSION['user_id'] = $newUserId;
                $_SESSION['first_name'] = $s1['first_name'];
                
                unset($_SESSION['temp_user']);

                header("Location: index.php?page=dashboard");
                exit;
            } else {
                $donnees['erreur'] = "Une erreur est survenue lors de la création de votre compte.";
                $vue = new VueRegister2();
                $vue->afficher($donnees);
            }
        } 
        else {
            $vue = new VueRegister1();
            $vue->afficher();
        }
        break;

    case 'search':
        // C'est ici que Gerald travaillera
        include 'views/search.php';
        break;

    case 'agenda':
        // C'est ici que Koalima et Jonida travailleront
        include 'views/agenda.php';
        break;

     case 'map':
        $userModel = new User($pdo);

        // La requête récupère u.* donc la colonne 'bio' est incluse automatiquement
       // index.php -> case 'map'
$sqlMembres = "SELECT u.*, GROUP_CONCAT(s.name_fr SEPARATOR ', ') as skills_list
               FROM users u
               LEFT JOIN user_skills us ON u.id = us.user_id
               LEFT JOIN skills s ON us.skill_id = s.id
               GROUP BY u.id"; 
// On enlève le WHERE lat IS NOT NULL pour voir au moins les gens dans la liste à droite !
        
        $stmtMembres = $pdo->query($sqlMembres);
        $membres = $stmtMembres->fetchAll(PDO::FETCH_ASSOC);

        $stmtSkills = $pdo->query("SELECT id, name_fr FROM skills ORDER BY name_fr ASC");
        $allSkills = $stmtSkills->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/VueMap.php';
        $vue = new VueMap();
        
        $vue->afficher([
            'membres' => $membres,
            'allSkills' => $allSkills
        ]); 
        break;
        
    case 'logout':
        session_destroy();
        header("Location: index.php?page=home");
        exit;

    default:
        http_response_code(404);
        echo "404 - Page non trouvée";
        break;
}
echo '</div>';


// ==========================================
// --- LE FOOTER (Bas de page) ---
// S'affichera sur toutes les pages
// ==========================================
include 'includes/footer.php'; 
?>