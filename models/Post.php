<?php
// models/Post.php

class Post {
    private $pdo;
    private $hasAttachmentColumns = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function supportsAttachments() {
        if ($this->hasAttachmentColumns !== null) {
            return $this->hasAttachmentColumns;
        }

        try {
            $stmtImage = $this->pdo->query("SHOW COLUMNS FROM posts LIKE 'image_path'");
            $stmtDoc = $this->pdo->query("SHOW COLUMNS FROM posts LIKE 'doc_path'");
            $this->hasAttachmentColumns = (bool)$stmtImage->fetch(PDO::FETCH_ASSOC)
                && (bool)$stmtDoc->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $this->hasAttachmentColumns = false;
        }

        return $this->hasAttachmentColumns;
    }

    public function create($userId, $content, $imagePath = null, $docPath = null) {
        $userId = (int)$userId;
        $content = trim((string)$content);

        if ($userId <= 0 || $content === '') {
            return false;
        }

        if ($this->supportsAttachments()) {
            $sql = "INSERT INTO posts (user_id, content, image_path, doc_path)
                    VALUES (:user_id, :content, :image_path, :doc_path)";
        } else {
            $sql = "INSERT INTO posts (user_id, content)
                    VALUES (:user_id, :content)";
            $imagePath = null;
            $docPath = null;
        }
        $stmt = $this->pdo->prepare($sql);

        if ($this->supportsAttachments()) {
            $success = $stmt->execute([
                'user_id'    => $userId,
                'content'    => $content,
                'image_path' => $imagePath,
                'doc_path'   => $docPath,
            ]);
        } else {
            $success = $stmt->execute([
                'user_id' => $userId,
                'content' => $content,
            ]);
        }

        return $success ? (int)$this->pdo->lastInsertId() : false;
    }

    public function getRecent($limit = 20, $search = '', $filter = 'all', $currentUserId = 0) {
    $sql = "SELECT p.*, u.first_name, u.last_name 
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            WHERE 1=1"; // Astuce SQL pour ajouter des AND facilement

    $params = [];

    // Filtre de recherche textuelle
    if (!empty($search)) {
        $sql .= " AND (p.content LIKE :search)";
        $params['search'] = "%$search%";
    }

    // --- NOS NOUVEAUX FILTRES ---
    if ($filter === 'mine') {
        $sql .= " AND p.user_id = :uid";
        $params['uid'] = $currentUserId;
    } elseif ($filter === 'images') {
        $sql .= " AND p.image_path IS NOT NULL AND p.image_path != ''";
    } elseif ($filter === 'docs') {
        $sql .= " AND p.doc_path IS NOT NULL AND p.doc_path != ''";
    }

    $sql .= " ORDER BY p.created_at DESC LIMIT " . (int)$limit;

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /**
     * Handle a file upload securely.
     * Validates MIME type via finfo (not just extension), limits size,
     * stores under assets/uploads/{images|docs}/ with a random filename.
     *
     * @param array|null $file  Entry from $_FILES
     * @param string     $type  'image' or 'doc'
     * @return string|null      Relative URL path, or null on failure / no file
     */
   /**
     * Handle a file upload securely.
     */
    public function handleUpload($file, $type) {
        // On vérifie si le fichier existe et s'il n'y a pas d'erreur
        if (!$file || (!isset($file['error'])) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $maxSize = ($type === 'image') ? 5 * 1024 * 1024 : 10 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return null;
        }

        $allowedMimes = ($type === 'image')
            ? ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
            : [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
              ];

        // Utilisation de finfo pour vérifier le vrai type du fichier
        $finfo    = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $allowedMimes, true)) {
            return null;
        }

        $mimeToExt = [
            'image/jpeg'  => 'jpg',  'image/png'  => 'png',
            'image/gif'   => 'gif',  'image/webp' => 'webp',
            'application/pdf'    => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'text/plain'   => 'txt',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        ];

        $ext       = isset($mimeToExt[$mimeType]) ? $mimeToExt[$mimeType] : 'bin';
        $subdir    = ($type === 'image') ? 'images' : 'docs';
        $uploadDir = __DIR__ . '/../assets/uploads/' . $subdir . '/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // On génère un nom aléatoire pour la sécurité
        $filename    = md5(uniqid(rand(), true)) . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        return 'assets/uploads/' . $subdir . '/' . $filename;
    }

public function getFilteredPosts($limit, $search, $authorFilter, $sortOrder, $currentUserId) {
        // Ajout de u.status ici 👇
     $sql = "SELECT p.*, u.first_name, u.last_name, u.user_type 
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            WHERE 1=1";
        $params = [];

        // Recherche
        if (!empty($search)) {
            $sql .= " AND p.content LIKE :search";
            $params['search'] = "%$search%";
        }

        // Filtrage Auteur
        if ($authorFilter === 'mine') {
            $sql .= " AND p.user_id = :uid";
            $params['uid'] = $currentUserId;
        }

        // Tri Chronologique
        $direction = ($sortOrder === 'asc') ? 'ASC' : 'DESC';
        $sql .= " ORDER BY p.created_at $direction LIMIT " . (int)$limit;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($postId, $userId) {
    $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = :pid AND user_id = :uid");
    return $stmt->execute(['pid' => $postId, 'uid' => $userId]);
}
}
?>