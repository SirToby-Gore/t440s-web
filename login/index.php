<?php

require_once __DIR__ . '/../php/init.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $header ?>
</head>

<body>
    <form action="./" method="post">
        <label for="email">Email</label>
        <input value="<?= $email ?>" type="email" name="email" required placeholder="example@email.com">

        <label for="password">Password</label>
        <input value="<?= $password ?>" type="password" name="password" required placeholder="password">

        <input type="submit" value="Log in">
    </form>
    <a href="/register">register an account</a>
</body>

</html>