<?php
// models/Event.php

class Event {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // --- 1. EVENT CREATION ---
    public function create($organizer_id, $data) {
        $sql = "INSERT INTO events (organizer_id, title, description, event_type, start_datetime, end_datetime) 
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

    // --- 2. GET EVENTS BY USER ---
    // Includes: Own events, Public events, and Shared events where invited/participating
    public function getEventsByUser($user_id) {
        $sql = "SELECT e.*, u.first_name, u.last_name 
                FROM events e
                JOIN users u ON e.organizer_id = u.id
                LEFT JOIN event_participants ep ON e.id = ep.event_id AND ep.user_id = :user_id
                WHERE e.organizer_id = :user_id 
                   OR e.event_type = 'public'
                   OR (e.event_type = 'shared' AND ep.user_id IS NOT NULL)
                ORDER BY e.start_datetime ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- 3. GET PUBLIC EVENTS ---
    public function getPublicEvents() {
        $stmt = $this->pdo->query("SELECT e.*, u.first_name, u.last_name 
                                   FROM events e 
                                   JOIN users u ON e.organizer_id = u.id 
                                   WHERE e.event_type = 'public' 
                                   ORDER BY e.start_datetime ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- 4. PARTICIPANT MANAGEMENT ---
    
    // Invite or add a user to a shared event (default status: pending)
    public function addParticipant($event_id, $user_id) {
        $sql = "INSERT IGNORE INTO event_participants (event_id, user_id, status) 
                VALUES (?, ?, 'pending')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$event_id, $user_id]);
    }

    // Update participation status (accepted or declined)
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

    // --- 5. DASHBOARD WIDGET ---
    // Returns the next N upcoming events for a given user (own + public + shared/invited)
    public function getUpcomingEvents($user_id, $limit = 3) {
        $sql = "SELECT e.*, u.first_name, u.last_name 
                FROM events e
                JOIN users u ON e.organizer_id = u.id
                LEFT JOIN event_participants ep ON e.id = ep.event_id AND ep.user_id = :user_id
                WHERE e.start_datetime >= NOW()
                  AND (
                      e.organizer_id = :user_id
                      OR e.event_type = 'public'
                      OR (e.event_type = 'shared' AND ep.status = 'accepted')
                  )
                ORDER BY e.start_datetime ASC
                LIMIT :lim";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':lim',     $limit,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get pending invitations for a user
    public function getPendingInvites($user_id) {
        $sql = "SELECT e.*, u.first_name, u.last_name 
                FROM events e
                JOIN users u ON e.organizer_id = u.id
                JOIN event_participants ep ON e.id = ep.event_id
                WHERE ep.user_id = ? AND ep.status = 'pending'
                ORDER BY e.start_datetime ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} // End of Event class
?>
