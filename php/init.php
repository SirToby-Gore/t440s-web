<?php

require_once __DIR__ . '/classes/_classes.php';
require_once __DIR__ . '/html_parts.php';
require_once __DIR__ . '/functions.php';

if (PHP_SESSION_NONE == session_status()) {
    session_start();
}

$env = parse_ini_file('.env');

$conn = mysqli_connect(
    hostname: $env['hostname'],
    username: $env['username'],
    password: $env['password'],
    database: $env['database'],
);

$account = Account::get_account();