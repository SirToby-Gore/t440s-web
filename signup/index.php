<?php

require_once __DIR__ . '/../php/init.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password_1 = $_POST['password-1'] ?? '';
$password_2 = $_POST['password-2'] ?? '';

$activePage = 'signup';

$i = 0;

$invalid_name = 1 << $i++;
$invalid_email = 1 << $i++;
$passwords_no_match = 1 << $i++;
$invalid_password_1 = 1 << $i++;
$invalid_password_2 = 1 << $i++;
$email_taken = 1 << $i++;

function signup(): int
{
    global $name;
    global $password_1;
    global $email;
    global $password_2;

    global $invalid_name;
    global $invalid_email;
    global $passwords_no_match;
    global $invalid_password_1;
    global $invalid_password_2;
    global $email_taken;

    $code = 0;

    if (
        !$name
        || !$email
        || !$password_1
        || !$password_2
    ) {
        return $code;
    }

    if (strlen($name) < 3) {
        $code += $invalid_name;
    }

    if (!preg_match("/[A-Z0-9._%+-]+@[A-Z0-9-]+.+.[A-Z]{2,4}/im", $email)) {
        $code += $invalid_email;
    }

    if ($password_1 != $password_2) {
        $code += $passwords_no_match;
    }

    if (strlen($password_1) < 8) {
        $code += $invalid_password_1;
    }

    if (strlen($password_2) < 8) {
        $code += $invalid_password_2;
    }

    if (User::from_email($email)) {
        $code += $email_taken;
        return $code;
    }

    if (!$code) {
        
        $salt = Random::random_string(16);
        $new_user = new User(
            User::get_new_user_id(),
            $name,
            $email,
            User::hash_password($password_1, $salt),
            $salt,
            false,
            null,
            'user',
            date("Y-m-d H:i:s"),
        );
        $new_user->create();

        log_user_in($new_user->email, $password_1);
    }

    return $code;
}

$error_code = signup();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= html_get_header() ?>

    <title>Sign up | The One App</title>
</head>

<body>

    <div class="page">

        <?= html_get_navbar() ?>

        <main class="auth">

            <div class="container">

                <div class="auth__card">

                    <h1 class="auth__title">
                        Create account
                    </h1>

                    <p class="auth__subtitle">
                        Join The One App and start managing your finances today.
                    </p>

                    <form method="POST" class="auth__form">

                        <label class="form-group">

                            <span class="form-group__label">
                                Name
                            </span>

                            <?php if ($error_code & $invalid_name): ?>
                                <div class="error">Length of your name is too short</div>
                            <?php endif ?>

                            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>"
                                class="form-group__input" minlength="3" required>

                        </label>

                        <label class="form-group">

                            <span class="form-group__label">
                                Email
                            </span>

                            <?php if ($error_code & $invalid_email): ?>
                                <div class="error">Your email is invalid</div>
                            <?php endif ?>

                            <?php if ($error_code & $email_taken): ?>
                                <div class="error">Email is already in use</div>
                            <?php endif ?>

                            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"
                                class="form-group__input" required>

                        </label>

                        <label class="form-group">

                            <span class="form-group__label">
                                Password
                            </span>

                            <?php if ($error_code & $passwords_no_match): ?>
                                <div class="error">Passwords do not match</div>
                            <?php endif ?>

                            <?php if ($error_code & $invalid_password_1): ?>
                                <div class="error">Password is invalid</div>
                            <?php endif ?>

                            <input type="password" name="password-1" value="" class="form-group__input" required>

                        </label>

                        <label class="form-group">

                            <span class="form-group__label">
                                Confirm Password
                            </span>

                            <?php if ($error_code & $invalid_password_2): ?>
                                <div class="error">Password confirmation is invalid</div>
                            <?php endif ?>

                            <input type="password" name="password-2" value="" class="form-group__input" required>

                        </label>

                        <button type="submit" class="button button--large button button--large button--full">
                            Create account
                        </button>

                    </form>

                    <p class="auth__footer">
                        Already have an account?
                        <a href="/login" class="auth__link">
                            Sign in
                        </a>
                    </p>

                </div>

            </div>

        </main>

        <?= html_get_footer() ?>

    </div>

</body>

</html>