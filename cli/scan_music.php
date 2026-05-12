<?php

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../vendor/autoload.php';

$songs = [];
$id3 = new getID3;
// Assuming $db->get_albums() and $db->get_artists() exist to load initial data
$albums = $db->get_albums();
$artists = $db->get_artists();
$covers_dir = __DIR__ . '/../covers';

if (!is_dir($covers_dir)) {
    // Make sure the directory creation is handled safely
    if (!mkdir($covers_dir, 0777, true)) {
        // Handle failure to create directory if necessary
        die("Fatal Error: Could not create cover directory at $covers_dir\n");
    }
}

function debug(mixed $message): void {
    echo "DEBUG:\n";
    var_dump($message);
    echo "\n";
    sleep(3);
}

function info(string $message): void {
    echo "INFO:\n" . $message . " \n";
    sleep(0.5);
}

function warning(string $message): void {
    echo "WARNING:\n" . $message . "\n";
    sleep(2);
}

function error(string $message): void {
    echo "ERROR:\n" . $message . "\n";
    sleep(6);
}

function find_songs(string $path): void {
    if (!file_exists($path) && !is_dir($path)) {
        warning("$path is not a path\n");
        return;
    }

    foreach (scandir($path) as $child) {
        if ($child == '.' || $child == '..') {
            continue;
        }
        
        $sub_path = $path . DIRECTORY_SEPARATOR . $child;

        if (is_dir($sub_path)) {
            find_songs($sub_path);
            continue;
        }

        if (is_file($sub_path)) {
            // Updated to look for .flac files
            if (str_ends_with($sub_path, '.flac')) {
                try {
                    add_to_songs($sub_path);
                } finally { /* do nothing */ }
            }
        }
    }
}

/**
 * Checks the global $artists cache. If the artist is not found,
 * it creates the artist in the database, adds them to the cache, and returns the new ID.
 * This function consolidates the artist management steps for reliability.
 */
function get_or_create_artist_id(string $artist_name): string {
    global $artists;
    
    // 1. Check in the local cache (fastest)
    foreach ($artists as $artist) {
        if ($artist->name == $artist_name) {
            return $artist->artist_id;
        }
    }
    
    // 2. Artist not found. Create a new Artist object.
    $artist_id = Artist::new_uuid();
    
    // NOTE: Assuming 'Artist' class is defined elsewhere and has the structure:
    // new Artist($id, $name, $bio, $created_at)
    $artist = new Artist(
        $artist_id,
        $artist_name,
        '', 
        new DateTime(),
        '',
    );

    // 3. Insert into the database (This MUST succeed before the Album insertion)
    $artist->create();

    // 4. Update the local cache immediately
    $artists[] = $artist;

    return $artist_id;
}

function does_album_exist(string $album_name, string $artist_id): bool {
    global $albums;

    foreach ($albums as $album) {
        if ($album->title == $album_name && $album->artist_id == $artist_id) {
            return true;
        }
    }

    return false;
}

function save_album_cover(string $album_id, array $cover_data): string {
    global $covers_dir;
    
    $mime_to_ext = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
    ];

    $mime_type = $cover_data['image_mime'] ?? 'image/jpeg';
    $image_data = $cover_data['data'];
    $extension = $mime_to_ext[$mime_type] ?? 'jpg';

    $filename = $album_id . '.' . $extension;
    $filepath = $covers_dir . DIRECTORY_SEPARATOR . $filename;

    info("Creating cover at \"$filepath\"");
    
    if (file_put_contents($filepath, $image_data) !== false) {
        // Return the web-accessible path
        return 'covers/' . $filename; 
    }

    warning("Failed to save cover art for album $album_id\n");
    return '';
}

function create_album(string $album_name, string $artist_id, ?array $cover_data = null): void {
    global $albums;
    
    $album_id = Album::new_uuid();
    $cover_image_path = '';

    if ($cover_data !== null) {
        $cover_image_path = save_album_cover($album_id, $cover_data);
    }
    
    $album = new Album(
        $album_id,
        $album_name,
        $artist_id,
        0, // gold_price
        $cover_image_path,
    );

    $album->create();

    $albums[] = $album;
}

function get_album_id(string $album_name, string $artist_id): string {
    global $albums;
    
    foreach ($albums as $album) {
        if ($album->title == $album_name && $album->artist_id == $artist_id) {
            return $album->album_id;
        }
    }

    die("no id found for $album_name");
}

function clean_artist_name(string $artist_name): string {
    // 1. Define common patterns that mark the start of a feature/remix/collaboration.
    // We use a regular expression (regex) for a powerful, single-line check.
    // The pattern looks for:
    // - (?:...): Non-capturing group.
    // - \s*: Zero or more whitespace characters.
    // - [\[\(,]: Matches an open bracket, open parenthesis, or comma.
    // - | : OR operator.
    // - \s(?:feat|ft|featuring|with|remix|vs)\.?: Matches space followed by 'feat', 'ft', etc., optionally ending with a period.
    $patterns = '/\s*(?:[\[\(,]|(?:feat\.|ft\.|featuring|with|remix|vs\.|feat|ft|featuring|with|remix|vs))\s*.*$/i';
    
    // 2. The 'i' flag at the end makes the search case-insensitive (e.g., 'Feat' or 'fT' still work).
    
    // 3. Use preg_replace to remove everything from the first match to the end of the string.
    $cleaned_name = preg_replace($patterns, '', $artist_name);

    // 4. Trim any leftover whitespace from the result.
    return trim($cleaned_name);
}


function add_to_songs(string $path) {
    global $songs, $albums, $artists, $id3, $scan_dir;

    $song_data = $id3->analyze($path);

    // --- 1. Extract Raw Tag Data ---
    
    // Track Artist (can include "ft. X")
    $track_artist_raw = $song_data['tags']['vorbiscomment']['artist'][0] ?? $song_data['tags']['id3v2']['artist'][0] ?? 'Unknown Artist';
    
    // Album Artist (should be the main artist of the album)
    // We check for the specific 'albumartist' tag first, then fall back to the generic 'artist' tag
    $album_artist_raw = $song_data['tags']['vorbiscomment']['albumartist'][0] 
                        ?? $song_data['tags']['id3v2']['albumartist'][0] 
                        ?? $track_artist_raw; // Fallback to track artist if no album artist tag exists

    $title = $song_data['tags']['vorbiscomment']['title'][0] ?? $song_data['tags']['id3v2']['title'][0] ?? 'Unknown Title';
    $album_name = $song_data['tags']['vorbiscomment']['album'][0] ?? $song_data['tags']['id3v2']['album'][0] ?? 'Unknown Album';
    $genre = $song_data['tags']['vorbiscomment']['genre'][0] ?? $song_data['tags']['id3v2']['genre'][0] ?? 'Unknown Genre';
    $duration_seconds = round($song_data['playtime_seconds']) ?? null;
    $track_number = $song_data['tags']['vorbiscomment']['tracknumber'][0] ?? $song_data['tags']['id3v2']['track_number'][0] ?? null;
    $cover_data = $song_data['comments']['picture'][0] ?? null;


    // --- 2. Determine Primary Artist for Album and Song ---
    
    // The **Album** Artist: Clean up the Album Artist name (usually only the main artist).
    $album_artist_name = clean_artist_name($album_artist_raw);
    
    // The **Song** Artist: Clean up the Track Artist name.
    $track_artist_name = clean_artist_name($track_artist_raw);
    
    // Final Artist for Song: Use the track artist, but if it's 'Unknown Artist' (meaning the tag was empty), 
    // we fall back to the album artist ID. This links the song to an existing artist entry.
    $song_artist_name_for_id = $track_artist_name !== 'Unknown Artist' ? $track_artist_name : $album_artist_name;


    // --- 3. Manage Artist (Album and Song MUST use IDs from this step) ---
    
    // Create/get the ID for the Album Artist (This ID will be used for the Album).
    $album_artist_id = get_or_create_artist_id($album_artist_name);

    // Create/get the ID for the Song Artist (This ID will be used for the Song).
    $song_artist_id = get_or_create_artist_id($song_artist_name_for_id);


    // --- 4. Manage Album (Linked to Album Artist) ---
    if (!does_album_exist($album_name, $album_artist_id)) {
        // Pass the cover data only when creating a new album
        create_album($album_name, $album_artist_id, $cover_data);
    }
    $album_id = get_album_id($album_name, $album_artist_id);
    
    
    // --- 5. Create Song (Linked to Track Artist ID and Album ID) ---
    $song = new Song(
        Song::new_uuid(),
        $title,
        $duration_seconds,
        $album_id,
        0, // Assuming this is silver_price
        str_replace(__DIR__ . '/../music/',  'music/', $path),
        (int)$track_number,
        $genre,
        $song_artist_id,
        $albums[count($albums) - 1]->cover_image_path,
    );

    $song->create();

    $songs[] = $song;
}


$scan_dir = __DIR__ . '/../music/';

find_songs($scan_dir);

echo "done";