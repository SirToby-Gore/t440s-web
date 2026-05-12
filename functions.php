<?php
require_once __DIR__ . '/init.php';

/**
 * Searches the database with typo correction (SOUNDEX)
 * and relevance sorting.
 * This now uses the PHP classes directly.
 */
function search(string $table, string $id_col, string $key_col, bool $random = false, bool $all = false): array {
    global $db;

    $ids = [];
    
    $sql = "SELECT `$id_col` FROM `$table` WHERE `$key_col` LIKE ? OR SOUNDEX(`$key_col`) = SOUNDEX(?)";

    if ($random) {
        $sql = $sql . " OR 1 ORDER BY RAND()";
    }
    if (!$all) {
        $sql = $sql . " LIMIT 20";
    }
    
    $searchQuery = $_GET['search'] ?? '';
    $tags = explode(' ', $searchQuery);
    
    foreach ($tags as $tag) {
        if (empty($tag) && !$random) {
            continue;
        }

        $like_str = "%$tag%";
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            continue;
        }
        
        $stmt->bind_param('ss', $like_str, $tag);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result) {
            foreach ($result->fetch_all() as $row) {
                $id = $row[0];
                if (empty($id)) continue;

                if (isset($ids[$id])) {
                    $ids[$id]++;
                } else {
                    $ids[$id] = 1;
                }
            }
        }
    }

    // Sort by most relevant (highest count) first
    arsort($ids, SORT_DESC);

    // Return the sorted IDs
    return array_keys($ids);
}

function redirect(string $path): void {
    header("Location: $path");
}
