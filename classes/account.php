<?php

class Account {
    public function __construct(
        public Token|null $token = null,
        public User|null $user = null,
        public bool $logged_in = false,
    ) {}

    static public function from_token(string|null $token = null): self {
        global $db;
        
        // the `?? null` shuts up the warning
        $token = $token ?? $_SESSION['token'] ?? null;

        if (!isset($token)) {
            return new self();
        } 
        
        // FIX 1 (Recap from previous fix): Hash the raw token value before looking up the database
        // $token_to_check = Token::hash($token);

        self::drop_old_tokens();
        
        $stmt = $db->prepare("SELECT * FROM `Token` WHERE `token_hash`=?");
        $stmt->bind_param('s', $token);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        
        if (!$result) {
            return new self();
        }

        $account_token = Token::from_id($result['token_id']);

        return new self(
            $account_token,
            User::from_id($account_token->user_id),
            true,
        );
    }

    public static function drop_old_tokens(): void {
        global $db;

        // FIX 2: Corrected SQL to delete tokens where expires_at is older than the current time (NOW())
        $stmt = $db->prepare("DELETE FROM `Token` WHERE `expires_at` < NOW()");
        $stmt->execute();
    }
}