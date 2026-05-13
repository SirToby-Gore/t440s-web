<?php

require_once __DIR__ . '/_classes.php';

class Account
{
    public function __construct(
        public User $user,
        public Token $token,
    ) {
    }

    public static function get_account(): ?self
    {
        if (!isset($_SESSION['token'])) {
            return null;
        }

        $token = Token::from_id($_SESSION['token']);

        if (!$token) {
            return null;
        }

        $user = User::from_id($token->user_id);

        if (!$user) {
            return null;
        }

        return new self($user, $token);
    }
}