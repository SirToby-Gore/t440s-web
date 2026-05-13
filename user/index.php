<?php
require_once __DIR__ . '/../php/init.php';

if (!$account || $account->user->role != 'user') {
    header('Location: /login');
}

$activePage = 'dashboard';
$user_id = $account->user->user_id;

$savings_pots = SavingsPot::get_all_for_user($user_id);
$transactions = Transaction::get_recent_for_user($user_id, 8);

$total_balance = UserDashboard::get_total_balance($user_id);
$weekly_spend = UserDashboard::get_spending_by_period($user_id, '7 DAY');
$monthly_spend = UserDashboard::get_spending_by_period($user_id, '1 MONTH');
$monthly_save = UserDashboard::get_spending_by_period($user_id, '1 MONTH');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= html_get_header() ?>
    <title>Dashboard | The One App</title>
</head>

<body>
    <div class="page">
        <?= html_get_navbar() ?>

        <main class="dashboard section">
            <div class="container">
                <h1 class="section-title" style="text-align: left; margin-bottom: 2rem;">
                    Welcome back, <?= htmlspecialchars($account->user->name) ?>
                </h1>

                <a href="pots.php">Pots</a>
                <a href="tips.php">Tips</a>
                <a href="profile.php">Profile</a>
                <a href="budgets.php">budgets</a>
                <a href="upload_receipt.php">upload receipt</a>
                <a href="/user/transactions.php" class="card__link text-sm">View all</a>

                <div class="dashboard__grid">

                    <aside class="dashboard__sidebar">
                        <h2 class="dashboard__subheading">Current Accounts</h2>

                        <?php if (empty($savings_pots)): ?>
                            <p class="section-text">No accounts found.</p>
                        <?php else: ?>
                            <?php foreach ($savings_pots as $pot): ?>
                                <div class="account-card">
                                    <div class="account-card__header">
                                        <div>
                                            <h3 class="account-card__name"><?= htmlspecialchars($pot->name) ?></h3>
                                            <p class="account-card__type"><?= $pot->is_long_term ? 'Long Term' : 'Liquid' ?></p>
                                        </div>
                                        <span class="account-card__more">•••</span>
                                    </div>

                                    <div class="account-card__balance">
                                        £<?= number_format($pot->current_balance, 2) ?>
                                    </div>

                                    <?php if ($pot->target_amount > 0):
                                        $percent = ($pot->current_balance / $pot->target_amount) * 100;
                                        ?>
                                        <div class="progress-bar">
                                            <div class="progress-bar__fill" style="width: <?= min($percent, 100) ?>%"></div>
                                        </div>
                                        <p class="account-card__type">
                                            Goal: £<?= number_format($pot->target_amount, 2) ?> (<?= round($percent, 1) ?>%)
                                        </p>
                                    <?php endif; ?>

                                    <div class="account-card__actions">
                                        <button class="button button--small button--outline">Move Money</button>
                                        <button class="button button--small button--outline">Edit</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </aside>

                    <section class="dashboard__stats">
                        <h2 class="dashboard__subheading">Total Balance: £<?= number_format($total_balance, 2) ?></h2>
                        <div class="stats-container">
                            <div class="stat-pill">
                                <span class="stat-pill__label">Weekly Spending</span>
                                <span class="stat-pill__value">£<?= number_format($weekly_spend, 2) ?></span>
                            </div>
                            <div class="stat-pill">
                                <span class="stat-pill__label">Monthly Spending</span>
                                <span class="stat-pill__value">£<?= number_format($monthly_spend, 2) ?></span>
                            </div>
                            <div class="stat-pill">
                                <span class="stat-pill__label">Monthly Savings</span>
                                <span class="stat-pill__value">£<?= number_format($monthly_save, 2) ?></span>
                            </div>
                        </div>
                    </section>

                    <section class="dashboard__main">
                        <h2 class="dashboard__subheading">Recent Transactions</h2>
                        <div class="card" style="padding: 0;">
                            <?php if (empty($transactions)): ?>
                                <p class="section-text" style="text-align: center; padding: 3rem;">
                                    No transactions recorded yet.
                                </p>
                            <?php else: ?>
                                <table style="width: 100%; border-collapse: collapse;">
                                    <?php foreach ($transactions as $trx): ?>
                                        <tr style="border-bottom: 1px solid #eee;">
                                            <td style="padding: 1rem;">
                                                <strong><?= htmlspecialchars($trx->category) ?></strong><br>
                                                <small
                                                    style="color: #666;"><?= date('M j, Y', strtotime($trx->transaction_date)) ?></small>
                                            </td>
                                            <td
                                                style="padding: 1rem; text-align: right; font-weight: 700; color: <?= $trx->type === 'expense' ? '#e63946' : '#2a9d8f' ?>;">
                                                <?= $trx->type === 'expense' ? '-' : '+' ?>£<?= number_format($trx->amount, 2) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php endif; ?>
                        </div>
                    </section>

                </div>
            </div>
        </main>

        <?= html_get_footer() ?>
    </div>
</body>

</html>