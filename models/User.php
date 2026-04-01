<?php
// Fichier : models/User.php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // --- 1. RÉCUPÉRATION PAR ID (Indispensable pour le profil) ---
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- 2. RÉCUPÉRER LES COMPÉTENCES D'UN UTILISATEUR PRÉCIS ---
    public function getUserSkills($userId) {
        $stmt = $this->pdo->prepare("
            SELECT s.* FROM skills s
            JOIN user_skills us ON s.id = us.skill_id
            WHERE us.user_id = :uid
        ");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- 3. RÉCUPÉRER TOUTES LES COMPÉTENCES (Pour la liste d'édition) ---
    public function getAllSkills() {
        $stmt = $this->pdo->query("SELECT * FROM skills ORDER BY name_fr ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- 4. RÉCUPÉRER TOUTES LES LANGUES ---
    public function getAllLanguages() {
        // On essaie de lire la table languages si elle existe
        try {
            $stmt = $this->pdo->query("SELECT * FROM languages ORDER BY label ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Si la table n'existe pas encore, on renvoie un tableau vide pour éviter le crash
            return [];
        }
    }

    // --- 5. GEOCODING ---
    private function getCoordinates($ville) {
        if (empty($ville)) return ['lat' => null, 'lng' => null];
        
        $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($ville) . "&format=json&limit=1";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SoKnowApp/1.0');
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $data = json_decode($response, true);
            if (!empty($data)) {
                return ['lat' => $data[0]['lat'], 'lng' => $data[0]['lon']];
            }
        }
        return ['lat' => 48.8566, 'lng' => 2.3522]; // Paris par défaut
    }

    // --- 6. VÉRIFICATION EMAIL ---
    public function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ? true : false; 
    }

    // --- 7. INSCRIPTION ---
    public function register($first_name, $last_name, $email, $password, $user_type, $location_name, $spoken_languages, $bio) {
        $coords = $this->getCoordinates($location_name);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $tos_accepted_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO users (first_name, last_name, email, password_hash, user_type, location_name, spoken_languages, bio, tos_accepted_at, lat, lng) 
                VALUES (:fn, :ln, :em, :ph, :ut, :loc, :sl, :bio, :tos, :lat, :lng)";
        
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            'fn' => $first_name, 
            'ln' => $last_name, 
            'em' => $email, 
            'ph' => $password_hash,
            'ut' => $user_type, 
            'loc' => $location_name, 
            'sl' => $spoken_languages,
            'bio' => $bio,
            'tos' => $tos_accepted_at, 
            'lat' => $coords['lat'], 
            'lng' => $coords['lng']
        ]);

        return $success ? $this->pdo->lastInsertId() : false;
    }

    public function addUserSkills($user_id, $skills_string, $user_type) {
        if (empty($skills_string)) return;
        $skills_array = explode(',', $skills_string);
        $skill_type = ($user_type === 'student') ? 'offering' : 'seeking';

        foreach ($skills_array as $skill_name) {
            $skill_name = trim($skill_name);
            if (empty($skill_name)) continue;

            $stmtSearch = $this->pdo->prepare("SELECT id FROM skills WHERE name_fr = :name LIMIT 1");
            $stmtSearch->execute(['name' => $skill_name]);
            $skill = $stmtSearch->fetch();

            if ($skill) {
                $skill_id = $skill['id'];
            } else {
                $stmtInsert = $this->pdo->prepare("INSERT INTO skills (name_fr, name_al, name_vi, category) VALUES (:n, :n, :n, 'general')");
                $stmtInsert->execute(['n' => $skill_name]);
                $skill_id = $this->pdo->lastInsertId();
            }

            $stmtLink = $this->pdo->prepare("INSERT IGNORE INTO user_skills (user_id, skill_id, skill_type) VALUES (?, ?, ?)");
            $stmtLink->execute([$user_id, $skill_id, $skill_type]);
        }
    }

    // --- 8. LOGIN ---
    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(); 
    }

    public function getAllMembersWithSkills() {
        $sql = "SELECT u.*, GROUP_CONCAT(s.name_fr SEPARATOR ', ') as skills_list
                FROM users u
                LEFT JOIN user_skills us ON u.id = us.user_id
                LEFT JOIN skills s ON us.skill_id = s.id
                GROUP BY u.id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}