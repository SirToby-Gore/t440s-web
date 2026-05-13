<?php

require_once '_classes.php';

class SavingsPot
{
    public function __construct(
        public string $saving_id,
        public string $user_id,
        public string $name,
        public ?float $target_amount,
        public float $current_balance,
        public bool $is_long_term,
    ) {
    }

    public static function from_id(string $saving_id): ?self
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM `SavingsPots` WHERE `saving_id` = ?');
        $stmt->bind_param('s', $saving_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(): bool
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO `SavingsPots` (`saving_id`, `user_id`, `name`, `target_amount`, `current_balance`, `is_long_term`) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssddi', $this->saving_id, $this->user_id, $this->name, $this->target_amount, $this->current_balance, $this->is_long_term);
        return $stmt->execute();
    }

    public function update(): bool
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE `SavingsPots` SET `user_id` = ?, `name` = ?, `target_amount` = ?, `current_balance` = ?, `is_long_term` = ? WHERE `saving_id` = ?');
        $stmt->bind_param('ssddis', $this->user_id, $this->name, $this->target_amount, $this->current_balance, $this->is_long_term, $this->saving_id);
        return $stmt->execute();
    }

    public function delete(): bool
    {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM `SavingsPots` WHERE `saving_id` = ?');
        $stmt->bind_param('s', $this->saving_id);
        return $stmt->execute();
    }

    public static function get_all_for_user(string $user_id): array
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM `SavingsPots` WHERE `user_id` = ?');
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $pots = [];
        while ($row = $result->fetch_assoc()) {
            $pots[] = new self(...$row);
        }
        return $pots;
    }
}
