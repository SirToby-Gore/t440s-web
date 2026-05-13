<?php
require_once __DIR__ . '/../php/init.php';

// Redirect if not logged in
if (!$account) {
    header("Location: /login.php");
    exit;
}

$user = $account->user;
$userId = $user->user_id;

// Fetch Budgets
$budgets = [];
$stmt = $conn->prepare('SELECT * FROM `Budgets` WHERE `user_id` = ?');
$stmt->bind_param('s', $userId);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $budgets[] = new Budget(...$row);
}

// Helper to get total spent per category for the current month
function get_spent_this_month($conn, $userId, $category)
{
    $stmt = $conn->prepare("SELECT SUM(amount) as total FROM `Transactions` WHERE `user_id` = ? AND `category` = ? AND `type` = 'expense' AND MONTH(`transaction_date`) = MONTH(CURRENT_DATE()) AND YEAR(`transaction_date`) = YEAR(CURRENT_DATE())");
    $stmt->bind_param("ss", $userId, $category);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return $res['total'] ?? 0;
}

echo html_get_header();
echo html_get_navbar();
?>

<main class="page">
    <div class="container">
        <div class="card">
            <div class="card__header-flex margin-bottom-md">
                <div>
                    <h2 class="card__title no-margin">Smart Budgets</h2>
                    <p class="text-muted text-sm">Monitor your spending limits for this month.</p>
                </div>
                <button class="button button--primary button--small">Set New Budget</button>
            </div>

            <div class="budget-list">
                <?php if (empty($budgets)): ?>
                    <p class="text-center text-muted">You haven't set any budgets yet.</p>
                <?php else: ?>
                    <?php foreach ($budgets as $budget):
                        $spent = get_spent_this_month($conn, $userId, $budget->category);
                        $percentage = ($spent / $budget->limit_amount) * 100;
                        $percentage = min($percentage, 100); // Cap at 100% for the bar width
                
                        // Color coding based on usage
                        $barColor = "#3498db"; // Default Blue
                        if ($percentage > 90)
                            $barColor = "#e74c3c"; // Red if near/over
                        elseif ($percentage > 70)
                            $barColor = "#f39c12"; // Orange
                        ?>
                        <div class="budget-item margin-bottom-lg">
                            <div class="card__header-flex no-margin">
                                <h4 class="no-margin"><?= htmlspecialchars($budget->category) ?></h4>
                                <span class="text-sm">
                                    <strong>£<?= number_format($spent, 2) ?></strong> /
                                    £<?= number_format($budget->limit_amount, 2) ?>
                                </span>
                            </div>
                            <div class="progress-container"
                                style="background: #eee; height: 12px; border-radius: 6px; margin-top: 8px; overflow: hidden;">
                                <div class="progress-bar"
                                    style="width: <?= $percentage ?>%; background: <?= $barColor ?>; height: 100%; transition: width 0.5s ease;">
                                </div>
                            </div>
                            <p class="text-xs text-muted mt-1">
                                <?= $percentage >= 100 ? "Limit reached!" : number_format(100 - $percentage, 1) . "% remaining for this month." ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Tips Section from PDF Wireframe -->
        <div class="card margin-top-md" style="background: #f0f7ff; border-left: 4px solid #3498db;">
            <h3 class="card__title"><i class="fas fa-lightbulb" style="color: #f1c40f;"></i> AI Spending Tips</h3>
            <p class="text-sm">Based on your receipt from <strong>Tesco</strong>, you've spent 15% more on "Groceries"
                than your average. Consider buying store-brand items to save approx. £12 next week.</p>
        </div>
    </div>
</main>

<?php echo html_get_footer(); ?>