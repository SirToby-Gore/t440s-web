<?php

require_once '_classes.php';

class Budget
{
    public function __construct(
        public string $budget_id,
        public string $user_id,
        public string $category,
        public float $limit_amount,
        public float $current_spending,
        public float $notification_threshold,
    ) {}

    public static function from_id(mysqli $connection, string $budget_id): ?self
    {
        $statement = $connection->prepare('SELECT * FROM `Budgets` WHERE `budget_id` = ?');
        $statement->bind_param('s', $budget_id);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(mysqli $connection): bool
    {
        $statement = $connection->prepare('INSERT INTO `Budgets` (`budget_id`, `user_id`, `category`, `limit_amount`, `current_spending`, `notification_threshold`) VALUES (?, ?, ?, ?, ?, ?)');
        $statement->bind_param('sssddd', $this->budget_id, $this->user_id, $this->category, $this->limit_amount, $this->current_spending, $this->notification_threshold);
        return $statement->execute();
    }

    public function update(mysqli $connection): bool
    {
        $statement = $connection->prepare('UPDATE `Budgets` SET `user_id` = ?, `category` = ?, `limit_amount` = ?, `current_spending` = ?, `notification_threshold` = ? WHERE `budget_id` = ?');
        $statement->bind_param('ssddds', $this->user_id, $this->category, $this->limit_amount, $this->current_spending, $this->notification_threshold, $this->budget_id);
        return $statement->execute();
    }

    public function delete(mysqli $connection): bool
    {
        $statement = $connection->prepare('DELETE FROM `Budgets` WHERE `budget_id` = ?');
        $statement->bind_param('s', $this->budget_id);
        return $statement->execute();
    }
}
