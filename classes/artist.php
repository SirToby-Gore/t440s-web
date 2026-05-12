<?php

require_once __DIR__ . '/_classes.php';

class Artist {
    public function __construct(
        public string $artist_id,
        public string $name,
        public string $bio,
        public DateTime $created_at,
        public string $cover_image_path,
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
        
        $stmt = $db->prepare("SELECT * FROM `Artist` WHERE `artist_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
         
        return $stmt->get_result()->num_rows > 0;
    }

    public static function from_id(string $id): self {
        global $db;
        
        if (!self::is_id_in_use($id)) {
            die("No Artist found with id $id");
        }

        $stmt = $db->prepare("SELECT * FROM `Artist` WHERE `artist_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return new self(
            $result['artist_id'],
            $result['name'],
            $result['bio'],
            new DateTime($result['created_at']),
            $result['cover_image_path'],
        );
    }

    public function create(): void {
        global $db;

        $created_at_str = $this->created_at->format('Y-m-d H:i:s');

        $stmt = $db->prepare("INSERT INTO `Artist` (`artist_id`, `name`, `bio`, `created_at`, `cover_image_path`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            'sssss',
            $this->artist_id,
            $this->name,
            $this->bio,
            $created_at_str,
            $this->cover_image_path,
        );
        $stmt->execute();
    }

    public function update(): void {
        global $db;

        $stmt = $db->prepare("UPDATE `Artist` SET `name`=?, `bio`=?, `cover_image_path`=? WHERE `artist_id`=?");
        $stmt->bind_param(
            'ssss',
            $this->name,
            $this->bio,
            $this->cover_image_path,
            $this->artist_id,
        );
        $stmt->execute();
    }

    public function delete(): void {
        global $db;

        $stmt = $db->prepare("DELETE FROM `Artist` WHERE `artist_id`=?");
        $stmt->bind_param('s', $this->artist_id);
        $stmt->execute();
    }

    public function get_albums(): array {
        global $db;

        // Corrected column name from `release_date` to `release_date`
        $stmt = $db->prepare("SELECT `album_id` from `Album` WHERE `artist_id`=? ORDER BY `release_date` ASC");
        $stmt->bind_param('s', $this->artist_id);
        $stmt->execute();

        $albums = [];

        foreach ($stmt->get_result()->fetch_all() as $row) {
            $albums[] = Album::from_id($row[0]);
        }

        return $albums;
    }

    public function get_songs(): array {
        global $db;

        $stmt = $db->prepare("SELECT `song_id` from `Song` WHERE `artist_id`=? ORDER BY `duration_sec`");
        $stmt->bind_param('s', $this->artist_id);
        $stmt->execute();

        $songs = [];

        foreach ($stmt->get_result()->fetch_all() as $row) {
            $songs[] = Song::from_id($row[0]);
        }

        return $songs;
    }

    public function to_assoc(): array {
        return [
            'artist-id' => $this->artist_id,
            'name' => $this->name,
            'bio' => $this->bio ?? '',
            'created-at' => $this->created_at,
            'cover-image-path' => $this->cover_image_path,
        ];
    }

    /**
     * Helper function to render a single Artist item.
     */
    public function render(): string {
        $cover = htmlspecialchars(empty($this->cover_image_path) ? 'favicon.png' : $this->cover_image_path, ENT_QUOTES);
        $name = htmlspecialchars($this->name ?? '', ENT_QUOTES);
        $url = "?artist=" . urlencode($this->artist_id);
        
        return <<<HTML
            <a href="$url" class="artist-item-link">
                <div class="artist-item">
                    <img class="artist-image" src="$cover">
                    <p class="artist-name">$name</p>
                </div>
            </a>
        HTML;
    }
}
