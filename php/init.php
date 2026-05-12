<?php

require_once __DIR__ . '/classes/_classes.php';

$env = parse_ini_file('.env');

$conn = mysqli_connect(
    hostname: $env['hostname'],
    username: $env['username'],
    password: $env['password'],
    database: $env['database'],
);

$header = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="shortcut icon" href="/favicon.jpg" type="image/x-icon">
    <title>The One App</title>
HTML;

$account = Account::get_account();

