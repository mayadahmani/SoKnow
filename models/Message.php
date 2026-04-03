<?php
// models/Message.php

class Message {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function sendMessage($senderId, $receiverId, $content, $isInitial = false) {
        $senderId = (int)$senderId;
        $receiverId = (int)$receiverId;
        $content = trim((string)$content);

        if ($senderId <= 0 || $receiverId <= 0 || $senderId === $receiverId || $content === '') {
            return false;
        }

        if (!$this->userExists($receiverId)) {
            return false;
        }

        $sql = "INSERT INTO messages (sender_id, receiver_id, content, is_initial, is_read)\n                VALUES (:sender_id, :receiver_id, :content, :is_initial, 0)";

        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'content' => $content,
            'is_initial' => $isInitial ? 1 : 0,
        ]);

        return $success ? (int)$this->pdo->lastInsertId() : false;
    }

    public function getConversations($userId) {
        $sql = "SELECT
                    u.id AS contact_id,
                    u.first_name,
                    u.last_name,
                    u.avatar_url,
                    (
                        SELECT m2.content
                        FROM messages m2
                        WHERE (
                            (m2.sender_id = :user_id AND m2.receiver_id = u.id)
                            OR
                            (m2.sender_id = u.id AND m2.receiver_id = :user_id)
                        )
                        ORDER BY m2.created_at DESC, m2.id DESC
                        LIMIT 1
                    ) AS last_content,
                    (
                        SELECT m3.created_at
                        FROM messages m3
                        WHERE (
                            (m3.sender_id = :user_id AND m3.receiver_id = u.id)
                            OR
                            (m3.sender_id = u.id AND m3.receiver_id = :user_id)
                        )
                        ORDER BY m3.created_at DESC, m3.id DESC
                        LIMIT 1
                    ) AS last_created_at,
                    (
                        SELECT COUNT(*)
                        FROM messages m4
                        WHERE m4.sender_id = u.id
                          AND m4.receiver_id = :user_id
                          AND m4.is_read = 0
                    ) AS unread_count
                FROM users u
                WHERE u.id <> :user_id
                  AND EXISTS (
                    SELECT 1
                    FROM messages m
                    WHERE (
                        (m.sender_id = :user_id AND m.receiver_id = u.id)
                        OR
                        (m.sender_id = u.id AND m.receiver_id = :user_id)
                    )
                  )
                ORDER BY last_created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => (int)$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchContacts($userId, $query = '', $limit = 100) {
        $userId = (int)$userId;
        $query = trim((string)$query);
        $limit = max(1, (int)$limit);

        $sql = "SELECT u.id, u.first_name, u.last_name, u.avatar_url
                FROM users u
                WHERE u.id <> :user_id";

        $params = ['user_id' => $userId];

        if ($query !== '') {
            $sql .= " AND (
                        u.first_name LIKE :q
                        OR u.last_name LIKE :q
                        OR CONCAT(u.first_name, ' ', u.last_name) LIKE :q
                        OR u.email LIKE :q
                      )";
            $params['q'] = '%' . $query . '%';
        }

        $sql .= " ORDER BY u.first_name ASC, u.last_name ASC LIMIT :lim";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        if ($query !== '') {
            $stmt->bindValue(':q', $params['q'], PDO::PARAM_STR);
        }
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContact($userId, $contactId) {
        $sql = "SELECT id, first_name, last_name, avatar_url
                FROM users
                WHERE id = :contact_id AND id <> :user_id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'contact_id' => (int)$contactId,
            'user_id' => (int)$userId,
        ]);

        $contact = $stmt->fetch(PDO::FETCH_ASSOC);
        return $contact ?: null;
    }

    public function getConversationMessages($userId, $contactId, $limit = 200) {
        $sql = "SELECT id, sender_id, receiver_id, content, is_initial, is_read, created_at
                FROM messages
                WHERE (
                    (sender_id = :user_id AND receiver_id = :contact_id)
                    OR
                    (sender_id = :contact_id AND receiver_id = :user_id)
                )
                ORDER BY created_at ASC, id ASC
                LIMIT :lim";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', (int)$userId, PDO::PARAM_INT);
        $stmt->bindValue(':contact_id', (int)$contactId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markConversationAsRead($userId, $contactId) {
        $sql = "UPDATE messages
                SET is_read = 1
                WHERE sender_id = :contact_id
                  AND receiver_id = :user_id
                  AND is_read = 0";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'contact_id' => (int)$contactId,
            'user_id' => (int)$userId,
        ]);
    }

    public function getUnreadChatsCount($userId) {
        $sql = "SELECT COUNT(DISTINCT sender_id) AS unread_chats
                FROM messages
                WHERE receiver_id = :user_id
                  AND is_read = 0";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => (int)$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($row['unread_chats'] ?? 0);
    }

    private function userExists($userId) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => (int)$userId]);
        return (bool)$stmt->fetch();
    }
}