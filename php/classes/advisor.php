<?php

require_once '_classes.php';

class Advisor
{
    public function __construct(
        public string $advisor_id,
        public string $user_id,
        public string $specialization,
    ) {}

    public static function from_id(mysqli $connection, string $advisor_id): ?self
    {
        $statement = $connection->prepare('SELECT * FROM `Advisors` WHERE `advisor_id` = ?');
        $statement->bind_param('s', $advisor_id);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(mysqli $connection): bool
    {
        $statement = $connection->prepare('INSERT INTO `Advisors` (`advisor_id`, `user_id`, `specialization`) VALUES (?, ?, ?)');
        $statement->bind_param('sss', $this->advisor_id, $this->user_id, $this->specialization);
        return $statement->execute();
    }

    public function update(mysqli $connection): bool
    {
        $statement = $connection->prepare('UPDATE `Advisors` SET `user_id` = ?, `specialization` = ? WHERE `advisor_id` = ?');
        $statement->bind_param('sss', $this->user_id, $this->specialization, $this->advisor_id);
        return $statement->execute();
    }

    public function delete(mysqli $connection): bool
    {
        $statement = $connection->prepare('DELETE FROM `Advisors` WHERE `advisor_id` = ?');
        $statement->bind_param('s', $this->advisor_id);
        return $statement->execute();
    }
}
