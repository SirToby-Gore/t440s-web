<?php

require_once __DIR__ . '/../php/init.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password_1 = $_POST['password-1'] ?? '';
$password_2 = $_POST['password-2'] ?? '';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $header ?>
</head>

<body>
    <form action="./" method="post">
        <input type="text" name="name" id="name" value="<?= $name ?>">
        <input type="email" name="email" id="email" value="<?= $email ?>">
        <input type="password-1" name="password-1" id="password-1" value="<?= $password_1 ?>">
        <input type="password-2" name="password-2" id="password-2" value="<?= $password_2 ?>">
    </form>
</body>

</html>