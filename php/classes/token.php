<?php

require_once '_classes.php';

class Token
{
    public function __construct(
        public string $user_id,
        public string $token,
        public string $created_on,
    ) {
    }

    public static function from_id(string $token): ?self
    {
        global $conn;
        $conn->query("DELETE FROM `Tokens` WHERE `created_on` < NOW() - INTERVAL 1 DAY");
        $stmt = $conn->prepare('SELECT * FROM `Tokens` WHERE `token` = ?');
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(): bool
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO `Tokens` (`user_id`, `token`, `created_on`) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $this->user_id, $this->token, $this->created_on);
        return $stmt->execute();
    }

    public function update(): bool
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE `Tokens` SET `created_on` = ? WHERE `user_id` = ? AND `token` = ?');
        $stmt->bind_param('sss', $this->created_on, $this->user_id, $this->token);
        return $stmt->execute();
    }

    public function delete(): bool
    {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM `Tokens` WHERE `user_id` = ? AND `token` = ?');
        $stmt->bind_param('ss', $this->user_id, $this->token);
        return $stmt->execute();
    }

    public static function get_new_token(): string
    {
        while (true) {
            $new_token = Random::random_string(64);

            if (!self::from_id($new_token)) {
                return $new_token;
            }
        }
    }
}
