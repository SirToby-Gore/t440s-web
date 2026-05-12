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

    public static function from_id(mysqli $connection, string $client_id): ?self
    {
        $statement = $connection->prepare('SELECT * FROM `Clients` WHERE `client_id` = ?');
        $statement->bind_param('s', $client_id);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(mysqli $connection): bool
    {
        $statement = $connection->prepare('INSERT INTO `Clients` (`client_id`, `advisor_id`, `user_id`, `status`, `notes`) VALUES (?, ?, ?, ?, ?)');
        $statement->bind_param('sssss', $this->client_id, $this->advisor_id, $this->user_id, $this->status, $this->notes);
        return $statement->execute();
    }

    public function update(mysqli $connection): bool
    {
        $statement = $connection->prepare('UPDATE `Clients` SET `advisor_id` = ?, `user_id` = ?, `status` = ?, `notes` = ? WHERE `client_id` = ?');
        $statement->bind_param('sssss', $this->advisor_id, $this->user_id, $this->status, $this->notes, $this->client_id);
        return $statement->execute();
    }

    public function delete(mysqli $connection): bool
    {
        $statement = $connection->prepare('DELETE FROM `Clients` WHERE `client_id` = ?');
        $statement->bind_param('s', $this->client_id);
        return $statement->execute();
    }
}
