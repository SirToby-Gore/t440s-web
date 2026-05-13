<?php

require_once '_classes.php';

class User
{
    public function __construct(
        public string $user_id,
        public string $name,
        public string $email,
        public string $password_hash,
        public string $salt,
        public bool $two_factor_enabled,
        public ?string $biometric_token,
        public string $role,
        public string $created_at,
    ) {
    }

    public static function from_id(string $user_id): ?self
    {
        global $conn;

        $stmt = $conn->prepare('SELECT * FROM `Users` WHERE `user_id` = ?');
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public static function from_email(string $user_email): ?self
    {
        global $conn;

        $stmt = $conn->prepare('SELECT * FROM `Users` WHERE `email` = ?');
        $stmt->bind_param('s', $user_email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(): bool
    {
        global $conn;

        $stmt = $conn->prepare('INSERT INTO `Users` (`user_id`, `name`, `email`, `password_hash`, `salt`, `two_factor_enabled`, `biometric_token`, `role`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssssisss', $this->user_id, $this->name, $this->email, $this->password_hash, $this->salt, $this->two_factor_enabled, $this->biometric_token, $this->role, $this->created_at);
        return $stmt->execute();
    }

    public function update(): bool
    {
        global $conn;

        $stmt = $conn->prepare('UPDATE `Users` SET `name` = ?, `email` = ?, `password_hash` = ?, `salt` = ?, `two_factor_enabled` = ?, `biometric_token` = ?, `role` = ?, `created_at` = ? WHERE `user_id` = ?');
        $stmt->bind_param('ssssissss', $this->name, $this->email, $this->password_hash, $this->salt, $this->two_factor_enabled, $this->biometric_token, $this->role, $this->created_at, $this->user_id);
        return $stmt->execute();
    }

    public function delete(): bool
    {
        global $conn;

        $stmt = $conn->prepare('DELETE FROM `Users` WHERE `user_id` = ?');
        $stmt->bind_param('s', $this->user_id);
        return $stmt->execute();
    }

    public static function hash_password(string $password, string $salt)
    {
        return hash('sha256', $password . $salt);
    }

    public static function get_new_user_id(): string
    {
        global $conn;

        $stmt = $conn->prepare("SELECT `user_id` FROM `Users` WHERE `user_id` = ?");

        while (true) {
            $new_id = Random::random_string(64);
            $stmt->bind_param('s', $new_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 0) {
                return $new_id;
            }
        }
    }
}