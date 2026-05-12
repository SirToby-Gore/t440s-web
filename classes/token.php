<?php

require_once __DIR__ . '/_classes.php';

class Token {
    
    public function __construct(
        public string $token_id,
        public string $user_id,
        public string $token_hash,
        public TokenType $token_type,
        public DateTime $expires_at,
        public DateTime $created_at,
    ) {}

    public static function new_uuid(): string {
        while (true) {
            $uuid = generate_uuid();

            if (!self::is_id_in_use($uuid)) {
                return $uuid;
            }
        }
    }

    public static function hash(string $uuid): string {
        return hash('sha256', $uuid);
    }

    public static function is_id_in_use(string $id): bool {
        global $db;
        
        $stmt = $db->prepare("SELECT * FROM `Token` WHERE `token_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
         
        return $stmt->get_result()->num_rows > 0;
    }

    public static function from_id(string $id): self {
        global $db;
        
        if (!self::is_id_in_use($id)) {
            die("No Token found with id $id");
        }

        $stmt = $db->prepare("SELECT * FROM `Token` WHERE `token_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return new self(
            $result['token_id'],
            $result['user_id'],
            $result['token_hash'],
            TokenType::from_str($result['token_type']),
            new DateTime($result['expires_at']),
            new DateTime($result['created_at']),
        );
    }

    public function create(): void {
        global $db;

        $expires_at_str = $this->expires_at->format('Y-m-d H:i:s');
        $created_at_str = $this->created_at->format('Y-m-d H:i:s');

        $stmt = $db->prepare("INSERT INTO `Token` (`token_id`, `user_id`, `token_hash`, `token_type`, `expires_at`, `created_at`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            'ssssss',
            $this->token_id,
            $this->user_id,
            $this->token_hash,
            TokenType::to_str($this->token_type),
            $expires_at_str,
            $created_at_str,
        );
        $stmt->execute();
    }

    public function update(): void {
        global $db;

        $expires_at_str = $this->expires_at->format('Y-m-d H:i:s');
        
        $stmt = $db->prepare("UPDATE `Token` SET `user_id`=?, `token_hash`=?, `token_type`=?, `expires_at`=? WHERE `token_id`=?");
        $stmt->bind_param(
            'sssss',
            $this->user_id,
            $this->token_hash,
            TokenType::to_str($this->token_type),
            $expires_at_str,
            $this->token_id,
        );
        $stmt->execute();
    }

    public function delete(): void {
        global $db;

        $stmt = $db->prepare("DELETE FROM `Token` WHERE `token_id`=?");
        $stmt->bind_param('s', $this->token_id);
        $stmt->execute();
    }

    public static function delete_by_raw_token(string $raw_token): void {
        global $db;
        
        // Hash the raw token to find the corresponding hash in the database
        $token_hash_to_delete = self::hash($raw_token); 
        
        $stmt = $db->prepare("DELETE FROM `Token` WHERE `token_hash`=?");
        $stmt->bind_param('s', $token_hash_to_delete);
        $stmt->execute();
    }
}