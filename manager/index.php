<?php

require_once __DIR__ . '/../php/init.php';

if (!$account || $account->user->role != 'manager') {
    header('Location: /login');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= html_get_header() ?>
</head>

<body>
    <div class="page">
        <?= html_get_navbar() ?>

        <main class="hero">
            <div class="error">
                this page is under construction
            </div>
        </main>

        <?= html_get_footer() ?>
    </div>
</body>

</html>