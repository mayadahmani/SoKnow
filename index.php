<?php
// index.php
session_start();

require_once __DIR__ . '/config/database.php'; 
require_once __DIR__ . '/config/autoloader.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ── Language handling ──
$allowedLangs = ['fr', 'en', 'sq', 'vi'];
if (isset($_GET['lang']) && in_array($_GET['lang'], $allowedLangs, true)) {
    $_SESSION['lang'] = $_GET['lang'];
    // Redirect to the same page without the lang param to keep URL clean
    $params = $_GET;
    unset($params['lang']);
    $redirect = 'index.php' . ($params ? '?' . http_build_query($params) : '');
    header("Location: $redirect");
    exit;
}
$currentLang = $_SESSION['lang'] ?? 'en';
$lang = require __DIR__ . '/lang/' . $currentLang . '.php';

// Helper function to get a translation
function __($key) {
    global $lang;
    return $lang[$key] ?? $key;
}

$page = $_GET['page'] ?? 'home';
$donnees = []; 

switch ($page) {
    case 'home':
        $vue = new VueAccueil();
        $vue->afficher($donnees);
        break;

   case 'dashboard':
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $postModel = new Post($pdo);   // Assure-toi que la classe Post existe
        $eventModel = new Event($pdo); // Assure-toi que la classe Event existe

        // 1. ACTION : Publier un nouveau post
      // 1. ACTION : Publier un nouveau post
        if (isset($_GET['action']) && $_GET['action'] === 'createPost') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $content = $_POST['content'] ?? '';
                
                $imagePath = $postModel->handleUpload($_FILES['post_image'] ?? null, 'image');
                $docPath   = $postModel->handleUpload($_FILES['post_doc'] ?? null, 'doc');
                
                // 🚨 --- DÉBUT DU MODE DEBUG --- 🚨
                if (isset($_FILES['post_image']) && $_FILES['post_image']['size'] > 0 && $imagePath === null) {
                    echo "<h3>❌ Erreur d'upload de l'image !</h3>";
                    echo "Code erreur PHP : " . $_FILES['post_image']['error'] . "<br>";
                    echo "Le dossier 'assets/uploads/images/' existe-t-il sur ton FTP avec les droits 777 ou 755 ?<br>";
                    echo "<pre>"; var_dump($_FILES['post_image']); echo "</pre>";
                    die("Arrêt du script pour que tu puisses lire l'erreur.");
                }
                // 🚨 --- FIN DU MODE DEBUG --- 🚨

                // On enregistre le post en BDD
                $postModel->create($userId, $content, $imagePath, $docPath);
                
                header("Location: index.php?page=dashboard");
                exit;
            }
        }

        // 2. PRÉPARATION DES DONNÉES POUR LA VUE
    // index.php -> case 'dashboard'
// Dans case 'dashboard':
$searchQuery = $_GET['q'] ?? '';
$authorFilter = $_GET['author'] ?? 'all';
$sortOrder = $_GET['sort'] ?? 'desc';

$donnees = [
    'posts' => $postModel->getFilteredPosts(20, $searchQuery, $authorFilter, $sortOrder, $userId),
    'upcoming_events' => $eventModel->getUpcomingEvents($userId, 10),
    'search_query' => $searchQuery,
    'author' => $authorFilter,
    'sort' => $sortOrder
];

        $vue = new VueDashboard();
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


    case 'profile':
        // 1. Vérification : L'utilisateur est-il connecté ?
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userModel = new User($pdo);
        
        // 2. Déterminer quel profil afficher
        // Soit un ID précis passé en URL (?page=profile&id=12), soit le mien
        $userIdToShow = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_SESSION['user_id'];
        
        // 3. Récupération des données
        $profileUser = $userModel->getById($userIdToShow); // Tu dois avoir cette méthode dans ton modèle User
        
        if (!$profileUser) {
            http_response_code(404);
            die("Utilisateur introuvable");
        }

        // 4. Préparation des données pour la vue
        $donnees = [
            'user'        => $profileUser,
            'user_skills' => $userModel->getUserSkills($userIdToShow), // Récupère les compétences de cet utilisateur
            'user_badges' => [], // Optionnel : à remplir si tu as une table badges
            'all_skills'  => $userModel->getAllSkills(),   // Nécessaire pour la liste dans la modal d'édition
            'languages'   => $userModel->getAllLanguages(), // Nécessaire pour la modal d'édition
            'impact'      => [
                'helped' => 14, // Tu peux dynamiser ça plus tard avec une requête COUNT
                'rating' => 4.9
            ]
        ];

        // 5. Affichage
        $vue = new VueProfil();
        $vue->afficher($donnees);
        break;

    case 'agenda':
        // 0. AUTHENTICATION : Ensure the user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $eventModel = new Event($pdo);
        $userModel = new User($pdo);
        $action = $_GET['action'] ?? 'view';
        $donnees = [];

        // 1. ACTION : Handle new event creation
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
            $eventData = [
                'title'          => $_POST['title'] ?? '',
                'description'    => $_POST['description'] ?? '',
                'event_type'     => $_POST['event_type'] ?? 'private',
                'start_datetime' => $_POST['start_datetime'] ?? '',
                'end_datetime'   => $_POST['end_datetime'] ?? ''
            ];

            $eventId = $eventModel->create($userId, $eventData);
            
            // Invite participants if it's a shared event
            if ($eventId && $eventData['event_type'] === 'shared' && !empty($_POST['invited_users'])) {
                foreach ($_POST['invited_users'] as $invitedId) {
                    if ($invitedId != $userId) {
                        $eventModel->addParticipant($eventId, $invitedId);
                    }
                }
            }
            header("Location: index.php?page=agenda");
            exit;
        }

        // 2. ACTION : Handle invitation responses (Accept/Decline)
        if ($action === 'accept' && isset($_GET['event_id'])) {
            $eventModel->updateStatus($_GET['event_id'], $userId, 'accepted');
            header("Location: index.php?page=agenda");
            exit;
        } elseif ($action === 'decline' && isset($_GET['event_id'])) {
            $eventModel->updateStatus($_GET['event_id'], $userId, 'declined');
            header("Location: index.php?page=agenda");
            exit;
        }

        // 3. DISPLAY : Prepare data and render the Agenda view
        $donnees = [
            'events'          => $eventModel->getEventsByUser($userId),
            'pending_invites' => $eventModel->getPendingInvites($userId),
            'all_users'       => $userModel->getAllMembersWithSkills()
        ];
        
        $vue = new VueAgenda();
        $vue->afficher($donnees);
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

    case 'calendar':
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $eventModel = new Event($pdo);
        $action = $_GET['action'] ?? 'view';

        // Handle new event creation
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
            $eventData = [
                'title'          => $_POST['title'] ?? '',
                'description'    => $_POST['description'] ?? '',
                'event_type'     => $_POST['event_type'] ?? 'private',
                'start_datetime' => $_POST['start_datetime'] ?? '',
                'end_datetime'   => $_POST['end_datetime'] ?? ''
            ];
            $eventModel->create($userId, $eventData);
            header("Location: index.php?page=calendar");
            exit;
        }

        $donnees = [
            'events'          => $eventModel->getEventsByUser($userId),
            'upcoming'        => $eventModel->getUpcomingEvents($userId, 5),
        ];

        $vue = new VueCalendrier();
        $vue->afficher($donnees);
        break;

        case 'mentions-legales':
        $vue = new VueMentionsLegales();
        // Utilise la méthode que tu utilises pour tes autres vues (souvent $vue->generer(); ou $vue->afficher();)
         $vue->afficher($donnees);
        break;


        case 'confidentialite':
        $vue = new VueConfidentialite();
        $vue->afficher($donnees);
        break;
case 'cgu':
    $vue = new VueCGU();
    $vue->afficher($donnees);
    break;

    case 'messages':
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $messageModel = new Message($pdo);
        $action = $_GET['action'] ?? 'view';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'send') {
            $receiverId = (int)($_POST['conv_id'] ?? 0);
            $content = trim($_POST['message'] ?? '');

            if ($receiverId > 0 && $content !== '') {
                $messageModel->sendMessage($userId, $receiverId, $content, false);
            }

            header("Location: index.php?page=messages&conv=" . $receiverId);
            exit;
        }

        $activeContactId = (int)($_GET['conv'] ?? 0);
        $searchQuery = trim($_GET['q'] ?? '');

        $formatConversationTime = function ($datetime) {
            if (empty($datetime)) {
                return '';
            }

            $ts = strtotime($datetime);
            if ($ts === false) {
                return '';
            }

            return date('d/m H:i', $ts);
        };

        $conversationsRaw = $messageModel->getConversations($userId);
        $contactsRaw = $messageModel->searchContacts($userId, $searchQuery, 200);

        if ($activeContactId <= 0 && !empty($conversationsRaw)) {
            $activeContactId = (int)$conversationsRaw[0]['contact_id'];
        }

        $conversations = [];
        $conversationById = [];
        foreach ($conversationsRaw as $conv) {
            $contactId = (int)$conv['contact_id'];
            $displayName = trim(($conv['first_name'] ?? '') . ' ' . ($conv['last_name'] ?? ''));
            if ($displayName === '') {
                $displayName = __('msg_user_prefix') . ' #' . $contactId;
            }

            $item = [
                'id' => $contactId,
                'name' => $displayName,
                'avatar' => 'https://i.pravatar.cc/150?u=' . $contactId,
                'online' => false,
                'preview' => preg_replace('/^\[post_preview:\{.*?\}\]\s*/s', '', $conv['last_content']) ?: __('msg_no_message_preview'),
                'time' => $formatConversationTime($conv['last_created_at']),
                'active' => $contactId === $activeContactId,
                'unread_count' => (int)($conv['unread_count'] ?? 0),
            ];

            $conversations[] = $item;
            $conversationById[$contactId] = true;
        }

        foreach ($contactsRaw as $contact) {
            $contactId = (int)$contact['id'];
            if (isset($conversationById[$contactId])) {
                continue;
            }

            $displayName = trim(($contact['first_name'] ?? '') . ' ' . ($contact['last_name'] ?? ''));
            if ($displayName === '') {
                $displayName = __('msg_user_prefix') . ' #' . $contactId;
            }

            $conversations[] = [
                'id' => $contactId,
                'name' => $displayName,
                'avatar' => 'https://i.pravatar.cc/150?u=' . $contactId,
                'online' => false,
                'preview' => __('msg_start_conversation'),
                'time' => '',
                'active' => $contactId === $activeContactId,
                'unread_count' => 0,
            ];
        }

        $activeContact = null;
        $messages = [];

        if ($activeContactId > 0) {
            $activeContactRaw = $messageModel->getContact($userId, $activeContactId);

            if ($activeContactRaw) {
                $activeContactName = trim(($activeContactRaw['first_name'] ?? '') . ' ' . ($activeContactRaw['last_name'] ?? ''));
                if ($activeContactName === '') {
                    $activeContactName = __('msg_user_prefix') . ' #' . $activeContactId;
                }

                $activeContact = [
                    'id' => $activeContactId,
                    'name' => $activeContactName,
                    'avatar' => 'https://i.pravatar.cc/150?u=' . $activeContactId,
                    'online' => false,
                ];

                $messageModel->markConversationAsRead($userId, $activeContactId);

                $messagesRaw = $messageModel->getConversationMessages($userId, $activeContactId);
                foreach ($messagesRaw as $msg) {
                    $messages[] = [
                        'type' => ((int)$msg['sender_id'] === $userId) ? 'sent' : 'received',
                        'text' => $msg['content'],
                        'time' => date('H:i', strtotime($msg['created_at'])),
                        'read' => (bool)$msg['is_read'],
                        'is_initial' => (bool)$msg['is_initial'],
                    ];
                }
            }
        }

        $donnees = [
            'conversations' => $conversations,
            'messages' => $messages,
            'active_contact' => $activeContact,
            'search_query' => $searchQuery,
        ];

        $vue = new VueMessages();
        $vue->afficher($donnees);
        break;


    default:
        http_response_code(404);
        echo "404 - Page non trouvée";
        break;
}
?>