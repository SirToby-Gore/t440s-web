<?php

require_once '_classes.php';

class Client
{
    public function __construct(
        public string $client_id,
        public string $advisor_id,
        public string $user_id,
        public string $status,
        public string $notes,
    ) {}

    public static function from_id(string $client_id): ?self
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM `Clients` WHERE `client_id` = ?');
        $stmt->bind_param('s', $client_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(): bool
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO `Clients` (`client_id`, `advisor_id`, `user_id`, `status`, `notes`) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sssss', $this->client_id, $this->advisor_id, $this->user_id, $this->status, $this->notes);
        return $stmt->execute();
    }

    public function update(): bool
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE `Clients` SET `advisor_id` = ?, `user_id` = ?, `status` = ?, `notes` = ? WHERE `client_id` = ?');
        $stmt->bind_param('sssss', $this->advisor_id, $this->user_id, $this->status, $this->notes, $this->client_id);
        return $stmt->execute();
    }

    public function delete(): bool
    {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM `Clients` WHERE `client_id` = ?');
        $stmt->bind_param('s', $this->client_id);
        return $stmt->execute();
    }
}
