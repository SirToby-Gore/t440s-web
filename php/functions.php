<?php

require_once __DIR__ . '/init.php';

function log_user_in($email, $password): ?bool {
    global $account;
    
    $user = User::from_email($email);

    if (!$user) {
        return false;
    }

    if ($user->password_hash != User::hash_password($password, $user->salt)) {
        return false;
    }

    $token = Token::get_new_token();
    $new_token = new Token(
        $user->user_id,
        $token,
        date('Y-m-d H:i:s'),
    );
    $new_token->create();

    $_SESSION['token'] = $token;

    $account = Account::get_account();

    $user_dashboard = get_user_dashboard();

    header("Location: /$user_dashboard");
}