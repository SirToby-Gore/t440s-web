<?php

require_once __DIR__ . '/../init.php';

foreach ($db->get_songs() as $song) {
    $song->file_path = str_replace('music//', 'music/', $song->file_path);
    $song->update();
}
