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

    public function getRecent($limit = 20, $search = '') {
        $limit = max(1, (int)$limit);
        $search = trim((string)$search);

        if ($this->supportsAttachments()) {
            $sql = "SELECT p.id, p.user_id, p.content, p.image_path, p.doc_path, p.created_at, u.first_name, u.last_name
                FROM posts p
                JOIN users u ON u.id = p.user_id";
        } else {
            $sql = "SELECT p.id, p.user_id, p.content, NULL AS image_path, NULL AS doc_path, p.created_at, u.first_name, u.last_name
                FROM posts p
                JOIN users u ON u.id = p.user_id";
        }

        $params = [];

        if ($search !== '') {
            $sql .= " WHERE p.content LIKE :search OR u.first_name LIKE :search OR u.last_name LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY p.created_at DESC, p.id DESC LIMIT :lim";

        $stmt = $this->pdo->prepare($sql);

        if ($search !== '') {
            $stmt->bindValue(':search', $params['search'], PDO::PARAM_STR);
        }
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);

        $stmt->execute();
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
    public function handleUpload(?array $file, string $type): ?string {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
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

        $ext       = $mimeToExt[$mimeType] ?? 'bin';
        $subdir    = ($type === 'image') ? 'images' : 'docs';
        $uploadDir = __DIR__ . '/../assets/uploads/' . $subdir . '/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename    = bin2hex(random_bytes(16)) . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        return 'assets/uploads/' . $subdir . '/' . $filename;
    }
}
?>
