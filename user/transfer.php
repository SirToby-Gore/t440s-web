<?php
require_once __DIR__ . '/../php/init.php';

if (isset($_POST['do_transfer'])) {
    $amount = (float)$_POST['amount'];
    $pot = SavingsPot::from_id($_POST['pot_id']);
    
    $pot->current_balance += $amount;
    $pot->update();

    $tx = new Transaction(
        Random::random_string(16),
        $account->user->user_id,
        $amount,
        'transfer',
        'Savings',
        "Moved to " . $pot->name,
        date('Y-m-d H:i:s'),
        null
    );
    $tx->create();

    header("Location: /dashboard");
}