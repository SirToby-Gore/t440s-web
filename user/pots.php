<?php
require_once __DIR__ . '/../php/init.php';

if (!$account) {
    header('Location: /login');
    exit;
}

$activePage = 'pots';
$user_id = $account->user->user_id;

if (isset($_POST['add_money'])) {
    $amount = (float) $_POST['amount'];
    $pot_id = $_POST['pot_id'];
    $pot = SavingsPot::from_id($pot_id);

    if ($pot && $pot->user_id === $user_id) {
        $pot->current_balance += $amount;
        $pot->update();

        $tx = new Transaction(Random::random_string(16), $user_id, $amount, 'deposit', 'Savings', "Added to {$pot->name}", date('Y-m-d H:i:s'), null);
        $tx->create();
    }
    header("Location: ./pots.php");
    exit;
}

if (isset($_POST['remove_money'])) {
    $amount = (float) $_POST['amount'];
    $pot_id = $_POST['pot_id'];
    $pot = SavingsPot::from_id($pot_id);

    if ($pot && $pot->user_id === $user_id) {
        if ($amount <= $pot->current_balance) {
            $pot->current_balance -= $amount;
            $pot->update();

            $tx = new Transaction(Random::random_string(16), $user_id, $amount, 'withdrawal', 'Savings', "Withdrew from {$pot->name}", date('Y-m-d H:i:s'), null);
            $tx->create();
        }
    }
    header("Location: ./pots.php");
    exit;
}

if (isset($_POST['create_pot'])) {
    $new_pot = new SavingsPot(
        Random::random_string(16),
        $user_id,
        $_POST['name'],
        (float) $_POST['target'],
        0.00,
        false
    );
    $new_pot->create();
    header("Location: ./pots.php");
}

if (isset($_GET['delete'])) {
    $pot_to_delete = SavingsPot::from_id($_GET['delete']);
    if ($pot_to_delete && $pot_to_delete->user_id === $user_id) {
        $pot_to_delete->delete();
    }
    header("Location: ./pots.php");
}

$pots = SavingsPot::get_all_for_user($user_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= html_get_header() ?>
    <title>My Pots | The One App</title>
</head>

<body>
    <div class="page">
        <?= html_get_navbar() ?>

        <main class="section">
            <div class="container">
                <div class="section__header"
                    style="display: flex; flex-direction: column; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h1 class="section-title">Your Savings Pots</h1>
                    <button class="button button--primary" popovertarget="add-pot-popover">
                        + Create New Pot
                    </button>
                </div>

                <div id="add-pot-popover" popover="auto" class="pot-card__popover">
                    <div class="card" style="border: none; box-shadow: none;">
                        <h3 style="margin-bottom: 1rem; color: #000080;">New Savings Pot</h3>
                        <form method="POST" action="./pots.php" class="form">
                            <div class="form-group">
                                <label>Pot Name</label>
                                <input type="text" name="name" required placeholder="e.g., Vacation fund">
                            </div>
                            <div class="form-group">
                                <label>Target Amount (£)</label>
                                <input type="number" name="target" step="0.01" value="0.00">
                            </div>
                            <div style="display: flex; gap: 10px; margin-top: 20px;">
                                <button type="submit" name="create_pot" class="button button--primary">Create
                                    Pot</button>
                                <button type="button" class="button button--outline" popovertarget="add-pot-popover"
                                    popovertargetaction="hide">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="pots-grid">
                    <?php foreach ($pots as $pot):
                        $percent = ($pot->target_amount > 0) ? ($pot->current_balance / $pot->target_amount) * 100 : 0;
                        $is_complete = $percent >= 100;
                        ?>
                        <div class="pot-card <?= $is_complete ? 'pot-card--celebrate' : '' ?>">
                            <h2 class="pot-card__title">
                                <?= htmlspecialchars($pot->name) ?>
                            </h2>

                            <div class="pot-progress">
                                <div class="pot-progress__fill" style="width: <?= min($percent, 100) ?>%"></div>
                            </div>

                            <p class="pot-card__percent">
                                <?= round($percent, 1) ?>% Reached
                            </p>
                            <p class="pot-card__balance">£
                                <?= number_format($pot->current_balance, 2) ?> /
                                £
                                <?= number_format($pot->target_amount, 2) ?>
                            </p>

                            <div class="pot-card__actions" style="display: flex; flex-wrap: wrap; gap: 5px;">
                                <button class="button button--small button--outline"
                                    popovertarget="popover-add-<?= $pot->saving_id ?>">
                                    Add
                                </button>

                                <button class="button button--small button--outline"
                                    popovertarget="popover-remove-<?= $pot->saving_id ?>">
                                    Remove
                                </button>

                                <a href="./pots.php?delete=<?= $pot->saving_id ?>"
                                    class="button button--delete button--small"
                                    onclick="return confirm('Delete this pot?')">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($pots as $pot): ?>
                    <div id="popover-add-<?= $pot->saving_id ?>" popover="auto" class="pot-card__popover">
                        <h3 style="margin-bottom: 10px;">Add to
                            <?= htmlspecialchars($pot->name) ?>
                        </h3>
                        <form method="POST" class="form" action="./pots.php">
                            <input type="hidden" name="pot_id" value="<?= $pot->saving_id ?>">
                            <input type="number" name="amount" step="0.01" placeholder="£0.00" required min="0.01">
                            <button type="submit" name="add_money" class="button button--small button--primary">Confirm
                                Deposit</button>
                        </form>
                    </div>

                    <div id="popover-remove-<?= $pot->saving_id ?>" popover="auto" class="pot-card__popover">
                        <h3 style="margin-bottom: 10px;">Remove from
                            <?= htmlspecialchars($pot->name) ?>
                        </h3>
                        <p style="font-size: 0.8rem; margin-bottom: 10px;">Available: £
                            <?= number_format($pot->current_balance, 2) ?>
                        </p>
                        <form method="POST" class="form" action="./pots.php">
                            <input type="hidden" name="pot_id" value="<?= $pot->saving_id ?>">
                            <input type="number" name="amount" step="0.01" placeholder="£0.00" required min="0.01"
                                max="<?= $pot->current_balance ?>">
                            <button type="submit" name="remove_money" class="button button--small button--primary"
                                style="background-color: #666;">Confirm Withdrawal</button>
                        </form>
                    </div>
                <?php endforeach ?>
        </main>


        <?= html_get_footer() ?>
    </div>
</body>

</html>