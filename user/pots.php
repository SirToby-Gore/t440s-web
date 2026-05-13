<?php
require_once __DIR__ . '/../php/init.php';

if (!$account) {
    header('Location: /login');
    exit;
}

$activePage = 'pots';
$user_id = $account->user->user_id;

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
                    style="display: flex; justify-content: space-between; align-items: center;">
                    <h1 class="section-title">Your Savings Pots</h1>
                    <button class="button button--primary"
                        onclick="document.getElementById('add-pot-modal').style.display='flex'">+ Create New
                        Pot</button>
                </div>

                <div class="pots-grid">
                    <?php foreach ($pots as $pot): ?>
                        <div class="pot-card">
                            <h2 class="pot-card__title"><?= htmlspecialchars($pot->name) ?></h2>
                            <p class="pot-card__balance">£<?= number_format($pot->current_balance, 2) ?></p>

                            <div class="pot-card__actions">
                                <a href="./transfer.php?to=<?= $pot->saving_id ?>"
                                    class="button button--small button--outline">Add Money</a>
                                <a href="./pots.php?delete=<?= $pot->saving_id ?>" class="button button--small"
                                    style="color: #e63946;" onclick="return confirm('Delete this pot?')">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

        <div id="add-pot-modal" class="modal-overlay" style="display:none;">
            <div class="card" style="width: 400px;">
                <h3>New Savings Pot</h3>
                <form method="POST" action="./pots.php">
                    <div class="form-group">
                        <label>Pot Name</label>
                        <input type="text" name="name" required placeholder="e.g. New Car">
                    </div>
                    <div class="form-group">
                        <label>Target Amount (£)</label>
                        <input type="number" name="target" step="0.01" value="0.00">
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="submit" name="create_pot" class="button button--primary">Create</button>
                        <button type="button" class="button button--outline"
                            onclick="this.closest('.modal-overlay').style.display='none'">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <?= html_get_footer() ?>
    </div>
</body>

</html>