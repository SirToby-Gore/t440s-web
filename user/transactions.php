<?php
require_once __DIR__ . '/../php/init.php';

// Redirect if not logged in
if (!$account) {
    header("Location: /login.php");
    exit;
}

$user = $account->user;
$userId = $user->user_id;

// Fetch all transactions for this user (not just the last 5)
$allTransactions = [];
$stmt = $conn->prepare("SELECT * FROM `Transactions` WHERE `user_id` = ? ORDER BY `transaction_date` DESC");
$stmt->bind_param("s", $userId);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $allTransactions[] = new Transaction(...$row);
}

echo html_get_header();
echo html_get_navbar();
?>

<main class="page">
    <div class="container container--wide">
        <div class="card">
            <div class="card__header-flex margin-bottom-md">
                <div>
                    <h2 class="card__title no-margin">Transaction History</h2>
                    <p class="text-muted text-sm">Full record of your income and expenses.</p>
                </div>
                <a href="/user/upload_receipt.php" class="button button--primary">
                    <i class="fas fa-plus"></i> Scan New Receipt
                </a>
            </div>

            <div style="overflow-x: auto;">
                <table class="transaction-table" style="width: 100%;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eee;">
                            <th style="text-align: left; padding: 10px;">Date</th>
                            <th style="text-align: left; padding: 10px;">Category</th>
                            <th style="text-align: left; padding: 10px;">Description</th>
                            <th style="text-align: right; padding: 10px;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($allTransactions)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted" style="padding: 2rem;">
                                    No transactions found. Upload a receipt to get started!
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($allTransactions as $tx): ?>
                                <tr class="transaction-table__row">
                                    <td class="transaction-table__cell">
                                        <?= date('d M Y', strtotime($tx->transaction_date)) ?>
                                    </td>
                                    <td class="transaction-table__cell">
                                        <span class="text-xs" style="background: #eee; padding: 2px 8px; border-radius: 4px;">
                                            <?= htmlspecialchars(strtoupper($tx->category)) ?>
                                        </span>
                                    </td>
                                    <td class="transaction-table__cell">
                                        <strong><?= htmlspecialchars($tx->description) ?></strong>
                                    </td>
                                    <td class="transaction-table__cell transaction-table__amount <?= $tx->type === 'expense' ? 'trend--down' : 'trend--up' ?>"
                                        style="text-align: right;">
                                        <?= $tx->type === 'expense' ? '-' : '+' ?>£<?= number_format($tx->amount, 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php echo html_get_footer(); ?>