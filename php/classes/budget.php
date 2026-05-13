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

    public static function from_id(string $budget_id): ?self
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM `Budgets` WHERE `budget_id` = ?');
        $stmt->bind_param('s', $budget_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(): bool
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO `Budgets` (`budget_id`, `user_id`, `category`, `limit_amount`, `current_spending`, `notification_threshold`) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssddd', $this->budget_id, $this->user_id, $this->category, $this->limit_amount, $this->current_spending, $this->notification_threshold);
        return $stmt->execute();
    }

    public function update(): bool
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE `Budgets` SET `user_id` = ?, `category` = ?, `limit_amount` = ?, `current_spending` = ?, `notification_threshold` = ? WHERE `budget_id` = ?');
        $stmt->bind_param('ssddds', $this->user_id, $this->category, $this->limit_amount, $this->current_spending, $this->notification_threshold, $this->budget_id);
        return $stmt->execute();
    }

    public function delete(): bool
    {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM `Budgets` WHERE `budget_id` = ?');
        $stmt->bind_param('s', $this->budget_id);
        return $stmt->execute();
    }
}
