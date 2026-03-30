<?php
// models/Event.php

class Event {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // --- 1. CRÉATION ---
    public function create($organizer_id, $data) {
        $sql = "INSERT INTO events (organizer_id, title, description, event_type, start_date, end_date) 
                VALUES (:org_id, :title, :desc, :type, :start, :end)";
        
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            'org_id' => $organizer_id,
            'title'  => $data['title'],
            'desc'   => $data['description'] ?? null,
            'type'   => $data['event_type'] ?? 'private',
            'start'  => $data['start_datetime'],
            'end'    => $data['end_datetime']
        ]);

        return $success ? $this->pdo->lastInsertId() : false;
    }

    // --- 2. RÉCUPÉRATION (Pour le Calendrier) ---
public function getEventsByUser($user_id) {
    $sql = "SELECT DISTINCT e.*, e.start_date AS start_datetime, e.end_date AS end_datetime, u.first_name, u.last_name 
            FROM events e
            JOIN users u ON e.organizer_id = u.id
            LEFT JOIN event_participants ep ON e.id = ep.event_id
            WHERE e.organizer_id = :user_id 
               -- On ne garde que les RDV acceptés ou créés par nous
               OR (ep.user_id = :user_id AND ep.status = 'accepted')
            ORDER BY e.start_date ASC";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // --- 3. ÉVÉNEMENTS PUBLICS ---
    public function getPublicEvents() {
        $stmt = $this->pdo->query("SELECT e.*, e.start_date AS start_datetime, e.end_date AS end_datetime, u.first_name, u.last_name 
                                   FROM events e 
                                   JOIN users u ON e.organizer_id = u.id 
                                   WHERE e.event_type = 'public' 
                                   ORDER BY e.start_date ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- 4. GESTION DES PARTICIPANTS ---
    public function addParticipant($event_id, $user_id) {
        $sql = "INSERT IGNORE INTO event_participants (event_id, user_id, status) 
                VALUES (?, ?, 'pending')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$event_id, $user_id]);
    }

    public function updateStatus($event_id, $user_id, $status) {
        if (!in_array($status, ['accepted', 'declined'])) return false;
        
        $sql = "UPDATE event_participants SET status = :status 
                WHERE event_id = :event_id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'status'   => $status,
            'event_id' => $event_id,
            'user_id'  => $user_id
        ]);
    }

    // --- 5. WIDGET DASHBOARD (Prochains événements) ---
    public function getUpcomingEvents($user_id, $limit = 3) {
        // Même logique : on ne montre que ce qui est validé ou public
        $sql = "SELECT DISTINCT e.*, e.start_date AS start_datetime, e.end_date AS end_datetime, u.first_name, u.last_name 
                FROM events e
                JOIN users u ON e.organizer_id = u.id
                LEFT JOIN event_participants ep ON e.id = ep.event_id
                WHERE e.start_date >= NOW()
                  AND (
                      e.organizer_id = :user_id
                      OR e.event_type = 'public'
                      OR (e.event_type = 'shared' AND ep.user_id = :user_id AND ep.status = 'accepted')
                  )
                ORDER BY e.start_date ASC
                LIMIT :lim";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':lim',     (int)$limit,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- 6. INVITATIONS EN ATTENTE ---
    public function getPendingInvites($user_id) {
        $sql = "SELECT e.*, e.start_date AS start_datetime, e.end_date AS end_datetime, u.first_name, u.last_name 
                FROM events e
                JOIN users u ON e.organizer_id = u.id
                JOIN event_participants ep ON e.id = ep.event_id
                WHERE ep.user_id = ? AND ep.status = 'pending'
                ORDER BY e.start_date ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}