<?php

require_once '_classes.php';

class User
{
    public function __construct(
        public string $user_id,
        public string $email,
        public string $password_hash,
        public bool $two_factor_enabled,
        public ?string $biometric_token,
        public string $role,
        public string $created_at,
    ) {}

    public static function from_id(mysqli $connection, string $user_id): ?self
    {
        $statement = $connection->prepare('SELECT * FROM `Users` WHERE `user_id` = ?');
        $statement->bind_param('s', $user_id);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(mysqli $connection): bool
    {
        $statement = $connection->prepare('INSERT INTO `Users` (`user_id`, `email`, `password_hash`, `two_factor_enabled`, `biometric_token`, `role`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $statement->bind_param('sssisss', $this->user_id, $this->email, $this->password_hash, $this->two_factor_enabled, $this->biometric_token, $this->role, $this->created_at);
        return $statement->execute();
    }

    public function update(mysqli $connection): bool
    {
        $statement = $connection->prepare('UPDATE `Users` SET `email` = ?, `password_hash` = ?, `two_factor_enabled` = ?, `biometric_token` = ?, `role` = ?, `created_at` = ? WHERE `user_id` = ?');
        $statement->bind_param('ssissss', $this->email, $this->password_hash, $this->two_factor_enabled, $this->biometric_token, $this->role, $this->created_at, $this->user_id);
        return $statement->execute();
    }

    public function delete(mysqli $connection): bool
    {
        $statement = $connection->prepare('DELETE FROM `Users` WHERE `user_id` = ?');
        $statement->bind_param('s', $this->user_id);
        return $statement->execute();
    }
}
