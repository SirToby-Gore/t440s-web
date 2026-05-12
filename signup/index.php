<?php

require_once __DIR__ . '/../php/init.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password_1 = $_POST['password-1'] ?? '';
$password_2 = $_POST['password-2'] ?? '';

$activePage = 'signup';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?= $header ?>

    <title>Sign up | The One App</title>
</head>

<body>

    <div class="page">

        <?= $navbar ?>

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

                            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>"
                                class="form-group__input" required>

                        </label>

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

                            <input type="password" name="password-1" value="" class="form-group__input" required>

                        </label>

                        <label class="form-group">

                            <span class="form-group__label">
                                Confirm Password
                            </span>

                            <input type="password" name="password-2" value="" class="form-group__input" required>

                        </label>

                        <button type="submit" class="button button--large button--full">
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

        <?= $footer ?>

    </div>

</body>

</html>