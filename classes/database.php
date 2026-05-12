<?php

require_once __DIR__ . '/_classes.php';

class DataBase {
    private mysqli $connection;

    public function __construct() {
        $env = self::get_env_data();
        
        $this->connection = new mysqli(
            hostname: $env['hostname'], 
            username: $env['username'],
            password: $env['password'],
            database: $env['database'],
        );
    }

    private static function get_env_data(): array {
        if (!file_exists(__DIR__ . '/../.env')) {
            return [];
        }

        return parse_ini_file(__DIR__ . '/../.env');
    }

    public function prepare(string $sql): mysqli_stmt {
        $stmt = $this->connection->prepare($sql);

        if (!$stmt) {
            die("connection unsuccessful");
        }

        return $stmt;
    }

    public function get_songs(): array {
        $stmt = $this->prepare("SELECT `song_id` FROM `Song`");
        $stmt->execute();

        $songs = [];

        foreach ($stmt->get_result()->fetch_all() as $row) {
            $songs[] = Song::from_id($row[0]);
        }

        return $songs;
    }

    public function get_albums(): array {
        $stmt = $this->prepare("SELECT `album_id` FROM `Album`");
        $stmt->execute();

        $albums = [];

        foreach ($stmt->get_result()->fetch_all() as $row) {
            $albums[] = Album::from_id($row[0]);
        }

        return $albums;
    }

    public function get_artists(): array {
        $stmt = $this->prepare("SELECT `artist_id` FROM `Artist`");
        $stmt->execute();

        $artists = [];

        foreach ($stmt->get_result()->fetch_all() as $row) {
            $artists[] = Artist::from_id($row[0]);
        }

        return $artists;
    }
}
