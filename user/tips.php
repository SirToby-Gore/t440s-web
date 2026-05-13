<?php

require_once __DIR__ . '/../php/init.php';

if (!$account) {
    header('Location: /login');
    exit;
}

$activePage = 'tips';

$banking_tips = [
    [
        'title' => 'How to Secure Your Account',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'description' => 'Learn the essential steps to keeping your digital banking credentials safe from phishing and unauthorized access.'
    ],
    [
        'title' => 'Understanding Interest Rates',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'description' => 'A deep dive into how AER and APR affect your savings and loans over the long term.'
    ],
    [
        'title' => 'Budgeting for Beginners',
        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        'description' => 'Follow our 50/30/20 rule guide to manage your monthly income effectively and build your savings pots.'
    ]
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= html_get_header() ?>
    <title>Banking Tips | The One App</title>
</head>

<body>
    <div class="page">
        <?= html_get_navbar() ?>

        <main class="tips-page section">
            <div class="container">
                <header class="section__header">
                    <h1 class="section-title">Banking tips:</h1>
                </header>

                <div class="tips-container">
                    <?php foreach ($banking_tips as $tip): ?>
                        <article class="tip-card">
                            <h2 class="tip-card__title"><?= htmlspecialchars($tip['title']) ?>:</h2>

                            <div class="tip-card__video-wrapper">
                                <iframe src="<?= $tip['video_url'] ?>" title="Banking Tip Video" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            </div>

                            <div class="tip-card__description">
                                <p><?= htmlspecialchars($tip['description']) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

        <?= html_get_footer() ?>
    </div>
</body>

</html>