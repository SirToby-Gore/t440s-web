<?php

require_once __DIR__ . '/../php/init.php';

if (isset($_GET['logout'])) {
    $account->token->delete();
    unset($_SESSION['token']);
    header('Location: /home');
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$activePage = 'login';


$i = 0;
$email_or_password_invalid = 1 << $i++;

function login(): int
{
    global $email;
    global $password;

    global $email_or_password_invalid;

    $code = 0;

    if (!$email || !$password) {
        return $code;
    }

    log_user_in($email, $password);

    $code += $email_or_password_invalid;

    return $code;
}

$error_code = login();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?= html_get_header() ?>

    <title>Sign In | The One App</title>

</head>

<body>

    <div class="page">

        <?= html_get_navbar() ?>

        <main class="auth">

            <div class="container">

                <div class="auth__card">

                    <h1 class="auth__title">
                        Sign In
                    </h1>

                    <p class="auth__subtitle">
                        Welcome back. Please enter your details.
                    </p>


                    <?php if ($error_code & $email_or_password_invalid): ?>
                        <div class="error">Email or password is incorrect</div>
                    <?php endif ?>

                    <form method="POST" class="auth__form">

                        <label class="form-group">

                            <span class="form-group__label">
                                Email
                            </span>

                            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"
                                class="form-group__input" required>

                        </label>

                        <label class="form-group">

                            <span class="form-group__label">
                                Password
                            </span>

                            <input value="<?= $password ?>" type="password" name="password" value=""
                                class="form-group__input" required>

                        </label>

                        <button type="submit" class="button button--large button button--large button--full">
                            Sign In
                        </button>

                    </form>

                    <p class="auth__footer">
                        Don't have an account?
                        <a href="/signup" class="auth__link">
                            Create one
                        </a>
                    </p>

                </div>

            </div>

        </main>

        <?= html_get_footer() ?>

    </div>

</body>

</html>