<?php

require_once '_classes.php';

class UserDashboard
{
    public function __construct(
        public string $dash_board_id,
        public string $user_id,
        public float $balance,
        public float $total_spending,
    ) {}

    public static function from_id(mysqli $connection, string $dash_board_id, string $user_id): ?self
    {
        $statement = $connection->prepare('SELECT * FROM `UserDashboards` WHERE `dash_board_id` = ? AND `user_id` = ?');
        $statement->bind_param('ss', $dash_board_id, $user_id);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(mysqli $connection): bool
    {
        $statement = $connection->prepare('INSERT INTO `UserDashboards` (`dash_board_id`, `user_id`, `balance`, `total_spending`) VALUES (?, ?, ?, ?)');
        $statement->bind_param('ssdd', $this->dash_board_id, $this->user_id, $this->balance, $this->total_spending);
        return $statement->execute();
    }

    public function update(mysqli $connection): bool
    {
        $statement = $connection->prepare('UPDATE `UserDashboards` SET `balance` = ?, `total_spending` = ? WHERE `dash_board_id` = ? AND `user_id` = ?');
        $statement->bind_param('ddss', $this->balance, $this->total_spending, $this->dash_board_id, $this->user_id);
        return $statement->execute();
    }

    public function delete(mysqli $connection): bool
    {
        $statement = $connection->prepare('DELETE FROM `UserDashboards` WHERE `dash_board_id` = ? AND `user_id` = ?');
        $statement->bind_param('ss', $this->dash_board_id, $this->user_id);
        return $statement->execute();
    }
}
