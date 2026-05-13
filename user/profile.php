<?php
require_once __DIR__ . '/../php/init.php';

if (!$account) {
    header("Location: /login.php");
    exit;
}

$user = $account->user;
$userId = $user->user_id;

$totalAssets = UserDashboard::get_total_balance($userId);
$recentTransactions = Transaction::get_recent_for_user($userId, 5);

$budgets = [];
$stmt = $conn->prepare('SELECT * FROM `Budgets` WHERE `user_id` = ?');
$stmt->bind_param('s', $userId);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $budgets[] = new Budget(...$row);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= html_get_header() ?>
</head>

<body>

    <main class="page dashboard">
        <?= html_get_navbar() ?>

        <div class="container container--wide">
            <div class="dashboard__header-row">
                <div class="profile-hero-card">
                    <div class="profile-hero-card__avatar"></div>
                    <div class="profile-hero-card__info">
                        <h1 class="section-title no-margin"><?= htmlspecialchars(strtoupper($user->name)) ?></h1>
                        <p class="text-muted">Client since <?= date('M Y', strtotime($user->created_at)) ?></p>
                        <ul class="profile-contact-list">
                            <li><i class="fas fa-envelope"></i> <?= htmlspecialchars($user->email) ?></li>
                            <li><i class="fas fa-phone"></i> +44 7700 900123</li>
                            <li><i class="fas fa-map-marker-alt"></i> London, United Kingdom</li>
                            <li class="profile-contact-list__id">Client ID: <?= substr($userId, 0, 10) ?></li>
                        </ul>
                    </div>
                </div>

                <div class="card card--flex-grow">
                    <div class="card__header-flex">
                        <h3 class="dashboard__subheading no-margin">Client Overview:</h3>
                        <button class="button button--primary button--small">
                            <i class="fas fa-edit"></i> Edit Client
                        </button>
                    </div>
                    <div class="dashboard-stats-grid">
                        <div class="dashboard-stat-item">
                            <div class="card__icon card__icon--centered"><i class="fas fa-wallet"></i></div>
                            <div class="stat-value">£<?= number_format($totalAssets, 2) ?></div>
                            <div class="text-muted text-xs">total assets</div>
                        </div>
                        <div class="dashboard-stat-item">
                            <div class="card__icon card__icon--centered"><i class="fas fa-chart-pie"></i></div>
                            <div class="stat-value">£62,120</div>
                            <div class="text-muted text-xs">Total Investments</div>
                        </div>
                        <div class="dashboard-stat-item">
                            <div class="card__icon card__icon--centered"><i class="fas fa-clipboard-list"></i></div>
                            <div class="stat-value">MEDIUM</div>
                            <div class="text-muted text-xs">Risk Profile</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard__main-layout">

                <div class="card">
                    <h3 class="card__title">Client Insights</h3>

                    <div class="insight-block">
                        <h4 class="insight-block__status insight-block__status--positive">Spending Trend</h4>
                        <p class="card__text text-sm">Spending decreased by 12% compared to last month.</p>
                        <div class="chart-placeholder chart-placeholder--small"></div>
                    </div>

                    <div class="insight-block">
                        <h4 class="insight-block__status insight-block__status--positive">Investment Performance</h4>
                        <p class="card__text text-sm">Your portfolio is performing above target by 2.1% this year.</p>
                        <div class="chart-placeholder chart-placeholder--small"></div>
                    </div>

                    <div class="insight-block">
                        <h4 class="insight-block__status insight-block__status--positive">Risk Assessment</h4>
                        <p class="card__text text-sm">Your risk profile is stable. Review suggested in 6 months.</p>
                        <div class="insight-block__footer-icon"><i class="fas fa-shield-alt"></i></div>
                    </div>
                </div>

                <div class="card card--center-content">
                    <div class="card__header-flex">
                        <h3 class="card__title">Financial Summary</h3>
                        <a href="#" class="card__link">View details</a>
                    </div>

                    <div class="summary-visualizer">
                        <div class="summary-visualizer__donut">
                            <span class="text-xs text-muted">Total Assets</span>
                            <strong class="summary-visualizer__total">£<?= number_format($totalAssets, 0) ?></strong>
                        </div>
                        <ul class="summary-visualizer__legend">
                            <li><span>Investments</span> <strong>73%</strong></li>
                            <li><span>Cash</span> <strong>15%</strong></li>
                            <li><span>Pensions</span> <strong>7%</strong></li>
                            <li><span>Other Assets</span> <strong>5%</strong></li>
                        </ul>
                    </div>

                    <hr class="divider">

                    <div class="net-worth-display">
                        <h3 class="text-muted text-sm">Net worth:</h3>
                        <div class="net-worth-display__value">£<?= number_format($totalAssets, 0) ?></div>
                        <div class="trend trend--up">
                            <i class="fas fa-caret-up"></i> 6.21% vs last month
                        </div>
                    </div>
                </div>

                <div class="dashboard__sidebar-stack">
                    <div class="card card--compact">
                        <div class="card__header-flex margin-bottom-sm">
                            <h4 class="dashboard__subheading no-margin">Recent Transactions</h4>
                            <a href="#" class="card__link text-sm">View all</a>
                        </div>
                        <table class="transaction-list">
                            <?php foreach ($recentTransactions as $tx): ?>
                                <tr class="transaction-list__row">
                                    <td><strong><?= htmlspecialchars($tx->category) ?></strong></td>
                                    <td class="text-muted"><?= date('j M Y', strtotime($tx->transaction_date)) ?></td>
                                    <td
                                        class="transaction-list__amount <?= $tx->type === 'expense' ? 'trend--down' : 'trend--up' ?>">
                                        <?= $tx->type === 'expense' ? '-' : '+' ?>£<?= number_format($tx->amount, 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </table>
                    </div>

                    <div class="card card--goals-theme">
                        <h3 class="card__title text-center">Goals and planning:</h3>
                        <div class="goals-list">
                            <?php foreach ($budgets as $budget): ?>
                                <div class="goal-pill">
                                    <i class="fas fa-piggy-bank goal-pill__icon"></i>
                                    <div class="goal-pill__info">
                                        <div class="goal-pill__name"><?= htmlspecialchars($budget->category) ?></div>
                                        <div class="text-muted text-xs">Target:
                                            £<?= number_format($budget->limit_amount, 0) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <?= html_get_footer() ?>

</body>

</html>