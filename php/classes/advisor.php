<?php

require_once '_classes.php';

class Advisor
{
    public function __construct(
        public string $advisor_id,
        public string $user_id,
        public string $specialization,
    ) {}

    public static function from_id(string $advisor_id): ?self
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM `Advisors` WHERE `advisor_id` = ?');
        $stmt->bind_param('s', $advisor_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(): bool
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO `Advisors` (`advisor_id`, `user_id`, `specialization`) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $this->advisor_id, $this->user_id, $this->specialization);
        return $stmt->execute();
    }

    public function update(): bool
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE `Advisors` SET `user_id` = ?, `specialization` = ? WHERE `advisor_id` = ?');
        $stmt->bind_param('sss', $this->user_id, $this->specialization, $this->advisor_id);
        return $stmt->execute();
    }

    public function delete(): bool
    {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM `Advisors` WHERE `advisor_id` = ?');
        $stmt->bind_param('s', $this->advisor_id);
        return $stmt->execute();
    }
}
