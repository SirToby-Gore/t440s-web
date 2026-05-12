<?php

require_once '_classes.php';

class Token
{
    public function __construct(
        public string $user_id,
        public string $token,
        public string $created_on,
    ) {}

    public static function from_id(mysqli $connection, string $user_id, string $token): ?self
    {
        $statement = $connection->prepare('SELECT * FROM `Tokens` WHERE `user_id` = ? AND `token` = ?');
        $statement->bind_param('ss', $user_id, $token);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(mysqli $connection): bool
    {
        $statement = $connection->prepare('INSERT INTO `Tokens` (`user_id`, `token`, `created_on`) VALUES (?, ?, ?)');
        $statement->bind_param('sss', $this->user_id, $this->token, $this->created_on);
        return $statement->execute();
    }

    public function update(mysqli $connection): bool
    {
        $statement = $connection->prepare('UPDATE `Tokens` SET `created_on` = ? WHERE `user_id` = ? AND `token` = ?');
        $statement->bind_param('sss', $this->created_on, $this->user_id, $this->token);
        return $statement->execute();
    }

    public function delete(mysqli $connection): bool
    {
        $statement = $connection->prepare('DELETE FROM `Tokens` WHERE `user_id` = ? AND `token` = ?');
        $statement->bind_param('ss', $this->user_id, $this->token);
        return $statement->execute();
    }
}
