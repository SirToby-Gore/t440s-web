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
    ) {}

    public static function from_id(mysqli $connection, string $saving_id): ?self
    {
        $statement = $connection->prepare('SELECT * FROM `SavingsPots` WHERE `saving_id` = ?');
        $statement->bind_param('s', $saving_id);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(mysqli $connection): bool
    {
        $statement = $connection->prepare('INSERT INTO `SavingsPots` (`saving_id`, `user_id`, `name`, `target_amount`, `current_balance`, `is_long_term`) VALUES (?, ?, ?, ?, ?, ?)');
        $statement->bind_param('sssddi', $this->saving_id, $this->user_id, $this->name, $this->target_amount, $this->current_balance, $this->is_long_term);
        return $statement->execute();
    }

    public function update(mysqli $connection): bool
    {
        $statement = $connection->prepare('UPDATE `SavingsPots` SET `user_id` = ?, `name` = ?, `target_amount` = ?, `current_balance` = ?, `is_long_term` = ? WHERE `saving_id` = ?');
        $statement->bind_param('ssddis', $this->user_id, $this->name, $this->target_amount, $this->current_balance, $this->is_long_term, $this->saving_id);
        return $statement->execute();
    }

    public function delete(mysqli $connection): bool
    {
        $statement = $connection->prepare('DELETE FROM `SavingsPots` WHERE `saving_id` = ?');
        $statement->bind_param('s', $this->saving_id);
        return $statement->execute();
    }
}
