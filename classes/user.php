<?php

require_once __DIR__ . '/_classes.php';

class User {
    
    public function __construct(
        public string $user_id,
        public string $username,
        public string $email,
        public string $password_hash,
        public int $silver_credits,
        public int $gold_credits,
        public bool $is_subscribed,
        public ?DateTime $subscription_date,
        // RENAMED from $creation_date to match database column
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

    public static function is_id_in_use(string $id): bool {
        global $db;
        
        $stmt = $db->prepare("SELECT * FROM `User` WHERE `user_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
         
        return $stmt->get_result()->num_rows > 0;
    }

    public static function from_id(string $id): self {
        global $db;
        
        if (!self::is_id_in_use($id)) {
            die("No User found with id $id");
        }

        $stmt = $db->prepare("SELECT * FROM `User` WHERE `user_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return new self(
            $result['user_id'],
            $result['username'],
            $result['email'],
            $result['password_hash'],
            $result['silver_credits'],
            $result['gold_credits'],
            $result['is_subscribed'],
            $result['subscription_date'] ? new DateTime($result['subscription_date']) : null,
            // Uses the correct database column name
            new DateTime($result['created_at']),
        );
    }

    public static function from_username(string $username): self | null {
        global $db;
        
        $stmt = $db->prepare("SELECT * FROM `User` WHERE `username`=?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result) {
            return null;
        }

        return new self(
            $result['user_id'],
            $result['username'],
            $result['email'],
            $result['password_hash'],
            $result['silver_credits'],
            $result['gold_credits'],
            (bool)$result['is_subscribed'],
            $result['subscription_date'] ? new DateTime($result['subscription_date']) : null,
            new DateTime($result['created_at']),
        );
    }

    public static function from_email(string $email): self | null {
        global $db;
        
        $stmt = $db->prepare("SELECT * FROM `User` WHERE `email`=?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result) {
            return null;
        }

        return new self(
            $result['user_id'],
            $result['username'],
            $result['email'],
            $result['password_hash'],
            $result['silver_credits'],
            $result['gold_credits'],
            (bool)$result['is_subscribed'],
            $result['subscription_date'] ? new DateTime($result['subscription_date']) : null,
            new DateTime($result['created_at']),
        );
    }

    public static function from_username_or_email(string $identifier): self | null {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return self::from_email($identifier);
        } else {
            return self::from_username($identifier);
        }
    }

    public function create(): void {
        global $db;

        $subscription_date_str = $this->subscription_date ? $this->subscription_date->format('Y-m-d') : null;

        // Note: created_at has a default timestamp, so we don't include it in the INSERT query.
        $stmt = $db->prepare("INSERT INTO `User` (`user_id`, `username`, `email`, `password_hash`, `silver_credits`, `gold_credits`, `is_subscribed`, `subscription_date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Corrected bind_param: ssss (strings) ii (ints) i (bool/int) s (date string/null)
        $stmt->bind_param(
            'ssssiiis',
            $this->user_id,
            $this->username,
            $this->email,
            $this->password_hash,
            $this->silver_credits,
            $this->gold_credits,
            $this->is_subscribed,
            $subscription_date_str,
        );
        $stmt->execute();
    }

    public function update(): void {
        global $db;

        $subscription_date_str = $this->subscription_date ? $this->subscription_date->format('Y-m-d') : null;

        $stmt = $db->prepare("UPDATE `User` SET `username`=?, `email`=?, `password_hash`=?, `silver_credits`=?, `gold_credits`=?, `is_subscribed`=?, `subscription_date`=? WHERE `user_id`=?");
        
        // Corrected bind_param: sss (strings) ii (ints) i (bool/int) s (date string/null) s (user_id)
        $stmt->bind_param(
            'sssiiss',
            $this->username,
            $this->email,
            $this->password_hash,
            $this->silver_credits,
            $this->gold_credits,
            $this->is_subscribed,
            $subscription_date_str,
            $this->user_id,
        );
        $stmt->execute();
    }

    public function delete(): void {
        global $db;

        $stmt = $db->prepare("DELETE FROM `User` WHERE `user_id`=?");
        $stmt->bind_param('s', $this->user_id);
        $stmt->execute();
    }

    public function get_library_songs(): array {
        global $db;
        $stmt = $db->prepare("SELECT `song_id` FROM `OwnedSong` WHERE `user_id`=? ORDER BY `acquisition_date` LIMIT 100");
        $stmt->bind_param('s', $this->user_id);
        $stmt->execute();
        $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $songs = [];
        foreach ($results as $row) {
            $songs[] = Song::from_id($row['song_id']);
        }
        return $songs;
    }

    public function get_library_albums(): array {
        global $db;
        // NOTE: Replace this placeholder logic with a query against your Transaction/UserAlbum table
        $stmt = $db->prepare("SELECT `album_id` FROM `OwnedAlbum` WHERE `user_id`=? ORDER BY `acquisition_date` LIMIT 100");
        $stmt->bind_param('s', $this->user_id);
        $stmt->execute();
        $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $albums = [];
        foreach ($results as $row) {
            $albums[] = Album::from_id($row['album_id']);
        }
        return $albums;
    }

    public function get_library_artists(): array {
        global $db;
        // NOTE: Replace this placeholder logic with a query based on owned songs/albums
        $stmt = $db->prepare("SELECT DISTINCT T1.artist_id FROM Artist T1 JOIN Album T2 ON T1.artist_id = T2.artist_id ORDER BY RAND() LIMIT 5");
        $stmt->execute();
        $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $artists = [];
        foreach ($results as $row) {
            $artists[] = Artist::from_id($row['artist_id']);
        }
        return $artists;
    }
}
