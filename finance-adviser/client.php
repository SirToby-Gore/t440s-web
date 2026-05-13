<?php
require_once __DIR__ . '/../php/init.php';

if (!$account || $account->user->role !== 'finance') {
    header('Location: /login');
    exit;
}

$activePage = 'clients';

$selectedClient = [
    'name' => 'Test Name',
    'id' => 'U001',
    'email' => 'example@email.com',
    'role' => 'Customer',
    'balance' => 1250.50,
    'spending' => 320.70,
    'notes' => 'High priority client. Looking into long-term investment planning options. Currently verified with 2FA enabled.'
];

$clientList = ['Daniel Hale', 'Noah Brooks', 'Sophia Turner', 'Olivia Mitchell', 'Isabella Foster', 'Benjamin Hyes', 'Liam Reed'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= html_get_header() ?>
    <title>Client Info | The One App</title>
</head>

<body>
    <div class="page">
        <?= html_get_navbar() ?>

        <main class="clients section">
            <div class="container container--wide">
                <header class="clients__header">
                    <h1 class="section-title" style="text-align: left;">Clients info:</h1>
                </header>

                <div class="clients-layout">
                    <aside class="client-sidebar">
                        <div class="client-sidebar__header">
                            <h2>Clients</h2>
                            <div class="search-bar">
                                <span class="search-bar__icon">🔍</span>
                                <input type="text" placeholder="Type to search">
                            </div>
                        </div>
                        <nav class="client-list">
                            <?php foreach ($clientList as $name): ?>
                                <button class="client-list__item"><?= $name ?></button>
                            <?php endforeach; ?>
                            <?php for ($i = 0; $i < 10; $i++): ?>
                                <div class="client-list__spacer"></div>
                            <?php endfor; ?>
                        </nav>
                    </aside>

                    <section class="client-details">
                        <div class="client-details__profile-card">
                            <div class="profile-main">
                                <div class="profile-avatar"></div>
                                <div class="profile-info">
                                    <div class="info-group">
                                        <label>Full legal name:</label>
                                        <p><?= $selectedClient['name'] ?></p>
                                    </div>
                                    <div class="info-group">
                                        <label>User ID:</label>
                                        <p><?= $selectedClient['id'] ?></p>
                                    </div>
                                    <div class="info-group">
                                        <label>Email address:</label>
                                        <p><?= $selectedClient['email'] ?></p>
                                    </div>
                                    <div class="info-group">
                                        <label>Role:</label>
                                        <p><?= $selectedClient['role'] ?></p>
                                    </div>
                                </div>
                                <div class="profile-badges">
                                    <span class="badge-2fa">2FA 🔒</span>
                                    <span class="badge-lock">🔓</span>
                                </div>
                            </div>

                            <div class="profile-stats-grid">
                                <div class="stat-box">
                                    <label>Current Balance:</label>
                                    <p class="stat-value">£<?= number_format($selectedClient['balance'], 2) ?></p>
                                </div>
                                <div class="stat-box">
                                    <label>Total Spending:</label>
                                    <p class="stat-value">£<?= number_format($selectedClient['spending'], 2) ?></p>
                                </div>
                            </div>

                            <div class="profile-activity-grid">
                                <div class="activity-card">
                                    <h3>📊 Monthly activity</h3>
                                    <div class="activity-item">
                                        <p>Food Budget: <strong>£50 / £500</strong> <span>10%</span></p>
                                        <div class="mini-progress">
                                            <div class="fill" style="width: 10%"></div>
                                        </div>
                                    </div>
                                    <div class="activity-item">
                                        <p>Transport Budget: <strong>£120 / £300</strong> <span>40%</span></p>
                                        <div class="mini-progress">
                                            <div class="fill" style="width: 40%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity-card">
                                    <h3>🎯 Saving Pots</h3>
                                    <div class="activity-item">
                                        <p>Holiday Fund: <strong>£300 / £2,000</strong> <span>15%</span></p>
                                        <div class="mini-progress">
                                            <div class="fill" style="width: 15%"></div>
                                        </div>
                                    </div>
                                    <div class="activity-item">
                                        <p>Emergency Fund: <strong>£1,200 / £2,000</strong> <span>60%</span></p>
                                        <div class="mini-progress">
                                            <div class="fill" style="width: 60%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-notes">
                                <h3>Notes:</h3>
                                <p><?= $selectedClient['notes'] ?></p>
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