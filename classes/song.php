<?php

require_once __DIR__ . '/_classes.php';

class Song {
    public function __construct(
        public string $song_id,
        public string $title,
        public int $duration_sec,
        public string $album_id,
        public int $silver_price,
        public string $file_path,
        public int $track_number,
        public string $genre,
        public string $artist_id,
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
        
        $stmt = $db->prepare("SELECT * FROM `Song` WHERE `song_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
         
        return $stmt->get_result()->num_rows > 0;
    }

    public static function from_id(string $id): self {
        global $db;
        
        if (!self::is_id_in_use($id)) {
            die("No Song found with id $id");
        }

        $stmt = $db->prepare("SELECT * FROM `Song` WHERE `song_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return new self(
            $result['song_id'],
            $result['title'],
            $result['duration_sec'],
            $result['album_id'],
            $result['silver_price'],
            $result['file_path'],
            $result['track_number'],
            $result['genre'],
            $result['artist_id'],
            $result['cover_image_path'],
        );
    }

    public function create(): void {
        global $db;

        $stmt = $db->prepare("INSERT INTO `Song` (`song_id`, `title`, `duration_sec`, `album_id`, `silver_price`, `file_path`, `track_number`, `genre`, `artist_id`, `cover_image_path`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'ssisisisss',
            $this->song_id,
            $this->title,
            $this->duration_sec,
            $this->album_id,
            $this->silver_price,
            $this->file_path,
            $this->track_number,
            $this->genre,
            $this->artist_id,
            $this->cover_image_path,
        );
        $stmt->execute();
    }

    public function update(): void {
        global $db;

        $stmt = $db->prepare("UPDATE `Song` SET `title`=?, `duration_sec`=?, `album_id`=?, `silver_price`=?, `file_path`=?, `track_number`=?, `genre`=?, `artist_id`=?, `cover_image_path`=? WHERE `song_id`=?");
        $stmt->bind_param(
            'sisisissss',
            $this->title,
            $this->duration_sec,
            $this->album_id,
            $this->silver_price,
            $this->file_path,
            $this->track_number,
            $this->genre,
            $this->artist_id,
            $this->cover_image_path,
            $this->song_id,
        );
        $stmt->execute();
    }

    public function delete(): void {
        global $db;

        $stmt = $db->prepare("DELETE FROM `Song` WHERE `song_id`=?");
        $stmt->bind_param('s', $this->song_id);
        $stmt->execute();
    }

    public function to_assoc(): array {
        return [
            'song-id' => $this->song_id,
            'title' => $this->title,
            'duration-sec' => $this->duration_sec,
            'album-id' => $this->album_id,
            'album-title' => Album::from_id($this->album_id)->title,
            'silver-price' => $this->silver_price,
            'file-path' => $this->file_path,
            'track-number' => $this->track_number,
            'genre' => $this->genre,
            'artist-id' => $this->artist_id,
            'artist-name' => Artist::from_id($this->artist_id)->name,
            'cover-image-path' => $this->cover_image_path,
        ];
    }

    /**
     * Helper function to render a single Song item.
     * All it needs is a 'data-song-id' for the JS to find it in the cookie queue.
     */
    function render(bool $minimal = false): string {
        $songId = htmlspecialchars($this->song_id, ENT_QUOTES);
        $title = htmlspecialchars($this->title, ENT_QUOTES);
        $cover = htmlspecialchars($this->cover_image_path, ENT_QUOTES);

        $art = $minimal ? '' : '<img class="song-art" src="' . $cover . '">';
        $trackNum = $minimal ? "<span class='song-track-number'>{$this->track_number}. </span>" : '';

        return <<<HTML
            <div class="song-item" data-song-id="$songId">
                $art
                <div class="song-details">
                    $trackNum
                    <p class="song-title">$title</p>
                </div>
            </div>
        HTML;
    }
}