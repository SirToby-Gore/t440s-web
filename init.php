<?php

declare(strict_types=1);

require_once __DIR__. '/functions.php';
require_once __DIR__ . '/classes/_classes.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$db = new DataBase();
$account = Account::from_token();
$cookie_manager = new CookieManager();
$melody_root = '/melody_vault';
