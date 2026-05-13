<?php

require_once '_classes.php';

class UserDashboard
{
    public function __construct(
        public string $dash_board_id,
        public string $user_id,
        public float $balance,
        public float $total_spending,
    ) {
    }

    public static function from_id(string $dash_board_id, string $user_id): ?self
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM `UserDashboards` WHERE `dash_board_id` = ? AND `user_id` = ?');
        $stmt->bind_param('ss', $dash_board_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(): bool
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO `UserDashboards` (`dash_board_id`, `user_id`, `balance`, `total_spending`) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssdd', $this->dash_board_id, $this->user_id, $this->balance, $this->total_spending);
        return $stmt->execute();
    }

    public function update(): bool
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE `UserDashboards` SET `balance` = ?, `total_spending` = ? WHERE `dash_board_id` = ? AND `user_id` = ?');
        $stmt->bind_param('ddss', $this->balance, $this->total_spending, $this->dash_board_id, $this->user_id);
        return $stmt->execute();
    }

    public function delete(): bool
    {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM `UserDashboards` WHERE `dash_board_id` = ? AND `user_id` = ?');
        $stmt->bind_param('ss', $this->dash_board_id, $this->user_id);
        return $stmt->execute();
    }

    public static function get_total_balance(string $user_id): float
    {
        global $conn;
        $stmt = $conn->prepare('SELECT SUM(`current_balance`) as total FROM `SavingsPots` WHERE `user_id` = ?');
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (float) ($row['total'] ?? 0.0);
    }

    public static function get_spending_by_period(string $user_id, string $interval): float
    {
        global $conn;

        $query = "SELECT SUM(`amount`) as total FROM `Transactions` 
              WHERE `user_id` = ? 
              AND `type` = 'expense' 
              AND `transaction_date` >= NOW() - INTERVAL $interval";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (float) ($row['total'] ?? 0.0);
    }
}
