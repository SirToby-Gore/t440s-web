<?php
require_once __DIR__ . '/../php/init.php';

if (!$account || $account->user->role !== 'finance') {
    header('Location: /login');
    exit;
}

$activePage = 'market';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= html_get_header() ?>
    <title>Market News | The One App</title>
</head>

<body>
    <div class="page">
        <?= html_get_navbar() ?>

        <main class="market section">
            <a href="latest-news.php">Latest News</a>
            <a href="templates.php">templates</a>
        </main>

        <?= html_get_footer() ?>
    </div>
</body>

</html>