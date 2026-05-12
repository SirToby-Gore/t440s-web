<?php

require_once __DIR__ . '/_classes.php';

class Album {
    public function __construct(
        public string $album_id,
        public string $title,
        public string $artist_id, // Foreign key to Artist
        public int $gold_price,
        public ?string $cover_image_path = null, // Path to cover art
        public DateTime $release_date = new DateTime(),
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
        
        $stmt = $db->prepare("SELECT * FROM `Album` WHERE `album_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
         
        return $stmt->get_result()->num_rows > 0;
    }

    public static function from_id(string $id): self {
        global $db;
        
        if (!self::is_id_in_use($id)) {
            die("No Album found with id $id");
        }

        $stmt = $db->prepare("SELECT * FROM `Album` WHERE `album_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        // Note: The cover_image_path is not mandatory (nullable)
        return new self(
            $result['album_id'],
            $result['title'],
            $result['artist_id'],
            $result['gold_price'],
            $result['cover_image_path'] ?? null,
            new DateTime($result['release_date']),
        );
    }

    public function create(): void {
        global $db;

        $release_date_str = $this->release_date->format('Y-m-d H:i:s');

        $stmt = $db->prepare("INSERT INTO `Album` (`album_id`, `title`, `artist_id`, `gold_price`, `cover_image_path`, `release_date`) VALUES (?, ?, ?, ?, ?, ?)");
        
        // Corrected bind_param: s (album_id) s (title) s (artist_id-string) i (gold_price-int) s (cover_path) s (date string)
        $stmt->bind_param(
            'sssiss',
            $this->album_id,
            $this->title,
            $this->artist_id,
            $this->gold_price,
            $this->cover_image_path,
            $release_date_str,
        );

        $stmt->execute();
    }

    public function update(): void {
        global $db;
        
        $release_date_str = $this->release_date->format('Y-m-d H:i:s');

        $stmt = $db->prepare("UPDATE `Album` SET `title`=?, `artist_id`=?, `gold_price`=?, `cover_image_path`=?, `release_date`=? WHERE `album_id`=?");
        
        $stmt->bind_param(
            'ssisss',
            $this->title,
            $this->artist_id,
            $this->gold_price,
            $this->cover_image_path,
            $release_date_str,
            $this->album_id,
        );
        $stmt->execute();
    }

    public function delete(): void {
        global $db;

        $stmt = $db->prepare("DELETE FROM `Album` WHERE `album_id`=?");
        $stmt->bind_param('s', $this->album_id);
        $stmt->execute();
    }

    public function get_songs(): array {
        global $db;

        $stmt = $db->prepare("SELECT `song_id` FROM `Song` WHERE `album_id`=? ORDER BY `track_number`");
        $stmt->bind_param('s', $this->album_id);
        $stmt->execute();

        $songs = [];
        foreach ($stmt->get_result()->fetch_all() as $row) {
            $songs[] = Song::from_id($row[0]);
        }

        return $songs;
    }

    public function format_date(): string {
        return $this->release_date->format('d/m/Y');
    }

    public function to_assoc(): array {
        return [
            'album-id' => $this->album_id,
            'title' => $this->title,
            'artist-id' => $this->artist_id,
            'gold-price' => $this->gold_price,
            'cover-image-path' => $this->cover_image_path,
            'release-date' => $this->format_date(),
        ];
    }

    /**
 * Helper function to render a single Album item.
 */
    function render(): string {
        $cover = htmlspecialchars($this->cover_image_path ?? 'favicon.png', ENT_QUOTES);
        $title = htmlspecialchars($this->title ?? '', ENT_QUOTES);
        $url = "?album=" . urlencode($this->album_id);

        return <<<HTML
            <a href="$url" class="album-item-link">
                <div class="album-item">
                    <img class="album-art" src="$cover">
                    <p class="album-title">$title</p>
                </div>
            </a>
        HTML;
    }
}
