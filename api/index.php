<?php

require_once __DIR__ . '/../init.php';

class JsonResponse {
    public function __construct(
        public array $data = [],
        public int $http_status = 200,
    ){}

    public function send(): void {
        http_response_code($this->http_status);

        header('Content-Type: application/json');

        // Note: This property doesn't exist on the class, but was in the original code.
        // $this->http_status_code = $this->http_status; 
        
        $json_output = '';

        try {
            $json_output = json_encode($this->data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        } catch (JsonException $e) {
            http_response_code(500);
            $json_output = json_encode([
                'error' => 'Internal server error: Could not encode data',
                // Keep development errors internal for security
            ]);
        }

        echo $json_output;

        exit();
    }

    // Magic method: Converts snake_case properties to kebab-case keys in $this->data
    public function __set(string $name, mixed $value): void {
        // Example: 'some_data' becomes 'some-data'
        $field_name = str_replace('_', '-', $name);
        $this->data[$field_name] = $value;
    }

    // Magic method: Allows reading properties using snake_case
    public function __get(string $name): mixed {
        $field_name = str_replace('_', '-', $name);
        return $this->data[$field_name] ?? null;
    }
    
    // Magic method: Allows checking if properties exist using snake_case
    public function __isset(string $name): bool {
        $field_name = str_replace('_', '-', $name);
        return isset($this->data[$field_name]);
    }
}

$json = new JsonResponse();

if (isset($_GET['logged-in'])) {
    $json->http_status = 200;

    $json->logged_in = $account->logged_in;

    $json->send();
}

// if (!$account->logged_in) {
//     $json->http_status = 405;
//     $json->href = './?login';
//     $json->message = 'You must be logged in to use the site';
    
//     $json->send();
// }

function search(string $table, string $id, string $key, bool $random = false, bool $all = false): array {
    global $db;

    $ids = [];
    
    // --- CHANGE 1: Updated SQL to include SOUNDEX() ---
    // This now checks for both a LIKE match (substring) AND a "sounds-like" match.
    $sql = "SELECT `$id` FROM `$table` WHERE `$key` LIKE ? OR SOUNDEX(`$key`) = SOUNDEX(?)";

    if ($random) {
        $sql = $sql . " OR 1 ORDER BY RAND()";
    }
    if (!$all) {
        $sql = $sql . " LIMIT 20";
    }

    $tags = explode(' ', $_GET['search']);
    
    foreach ($tags as $tag) {
        if (empty($tag) && !$random) {
            continue;
        }

        $like_str = "%$tag%";
        $stmt = $db->prepare($sql);
        
        // --- CHANGE 2: Bind both parameters ---
        // We bind the $like_str for the LIKE ?
        // And the raw $tag for the SOUNDEX(?)
        $stmt->bind_param('ss', $like_str, $tag);
        $stmt->execute();

        foreach ($stmt->get_result()->fetch_all() as $row) {
            $id = $row[0];

            if (empty($id)) {
                continue;
            }

            if (isset($ids[$id])) {
                $ids[$id]++;
            } else {
                $ids[$id] = 1;
            }
        }
    }

    // --- CHANGE 3: Fixed sorting ---
    // Changed from asort (Ascending) to arsort (Descending)
    // This puts the best matches (highest count) first.
    arsort($ids, SORT_DESC);

    return $ids;
}

if (isset($_GET['search'])) {
    if (empty($_GET['search']) && !(isset($_GET['random']) || isset($_GET['all']))) {
        $json->http_status = 400;
        $json->send();
    }

    $random = isset($_GET['random']);
    $all = isset($_GET['all']);
    
    $songs = [];
    
    foreach (search('Song', 'song_id', 'title', $random, $all) as $id=>$count) {
        $songs[] = Song::from_id($id)->to_assoc();
    }
    $albums = [];
    
    foreach (search('Album', 'album_id', 'title', $random, $all) as $id=>$count) {
        $albums[] = Album::from_id($id)->to_assoc();
    }
    $artists = [];
    
    foreach (search('Artist', 'artist_id', 'name', $random, $all) as $id=>$count) {
        $artists[] = Artist::from_id($id)->to_assoc();
    }
    
    $json->status_code = 200;
    $json->songs = $songs;
    $json->albums = $albums;
    $json->artists = $artists;
    
    $json->send();
}

if  (isset($_GET['song'])) {
    if (!Song::is_id_in_use($_GET['song'])) {
        $json->http_status = 404;
        $json->send();
    }
    
    $json->http_status = 200;
    $json->song = Song::from_id($_GET['song'])->to_assoc();
    $json->send();
}

if  (isset($_GET['album'])) {
    if (!Album::is_id_in_use($_GET['album'])) {
        $json->http_status = 404;
        $json->send();
    }

    $json->http_status = 200;

    $album = Album::from_id($_GET['album']);
    $json->album = $album->to_assoc();
    $songs = [];
    foreach ($album->get_songs() as $song) {
        $songs[] = $song->to_assoc();
    }
    $json->artist = Artist::from_id($album->artist_id)->to_assoc();
    $json->songs = $songs;
    $json->send();
}

if (isset($_GET['artist'])) {
    if (!Artist::is_id_in_use($_GET['artist'])) {
        $json->http_status = 404;
        $json->send();
    }

    $json->http_status = 200;

    $artist = Artist::from_id($_GET['artist']);
    $json->artist = $artist->to_assoc();

    $albums = [];
    foreach ($artist->get_albums() as $album) {
        $albums[] = $album->to_assoc();
    }

    $json->albums = $albums;

    $songs = [];
    foreach ($artist->get_songs() as $song) {
        $songs[] = $song->to_assoc();
    }

    $json->songs = $songs;

    $json->send();
}
