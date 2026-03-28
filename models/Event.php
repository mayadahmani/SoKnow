<?php
// models/Event.php

class Event {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // --- 1. EVENT CREATION ---
    public function create($organizer_id, $data) {
        $sql = "INSERT INTO events (organizer_id, title, description, event_type, start_date, end_date) 
                VALUES (:org_id, :title, :desc, :type, :start, :end)";
        
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            'org_id' => $organizer_id,
            'title'  => $data['title'],
            'desc'   => $data['description'] ?? null,
            'type'   => $data['event_type'] ?? 'private',
            'start'  => $data['start_date'],
            'end'    => $data['end_date']
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
                ORDER BY e.start_date ASC";
        
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
                                   ORDER BY e.start_date ASC");
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

} // End of Event class
