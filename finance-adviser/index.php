<?php
require_once __DIR__ . '/../php/init.php';

if (!$account || $account->user->role != 'finance') {
    header('Location: /login');
    exit;
}

$activePage = 'generate';

$clients = [
    'Daniel Hale',
    'Noah Brooks',
    'Sophia Turner',
    'Olivia Mitchell',
    'Isabella Foster',
    'Benjamin Hyes',
    'Liam Reed'
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= html_get_header() ?>
    <title>Generate Reports | The One App</title>
</head>

<body>
    <div class="page">
        <?= html_get_navbar() ?>

        <main class="generate section">
            <div class="container container--wide">
                <div class="generate-layout">

                    <aside class="client-sidebar">
                        <div class="client-sidebar__header">
                            <h2>Clients</h2>
                            <div class="search-bar">
                                <span class="search-bar__icon">🔍</span>
                                <input type="text" placeholder="Type to search">
                            </div>
                        </div>
                        <nav class="client-list">
                            <?php foreach ($clients as $client): ?>
                                <button class="client-list__item">
                                    <?= htmlspecialchars($client) ?>
                                </button>
                            <?php endforeach; ?>
                            <?php for ($i = 0; $i < 8; $i++): ?>
                                <div class="client-list__spacer"></div>
                            <?php endfor; ?>
                        </nav>
                    </aside>

                    <section class="charts-display">
                        <div class="chart-container">
                            <h3 class="chart-container__title">Spending</h3>
                            <div class="chart-placeholder">
                                <img src="chart2.png" alt="Spending Bar Chart">
                            </div>
                        </div>

                        <div class="chart-container">
                            <h3 class="chart-container__title">Balance</h3>
                            <div class="chart-placeholder">
                                <img src="chart1.png" alt="Balance Bar Chart">
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </main>

        <?= html_get_footer() ?>
    </div>
</body>

</html>