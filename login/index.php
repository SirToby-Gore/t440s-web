<?php

require_once __DIR__ . '/../php/init.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$activePage = 'login';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?= $header ?>

    <title>Sign In | The One App</title>

</head>

<body>

    <div class="page">

        <?= $navbar ?>

        <main class="auth">

            <div class="container">

                <div class="auth__card">

                    <h1 class="auth__title">
                        Sign In
                    </h1>

                    <p class="auth__subtitle">
                        Welcome back. Please enter your details.
                    </p>

                    <form method="POST" class="auth__form">

                        <label class="form-group">

                            <span class="form-group__label">
                                Email
                            </span>

                            <input
                                type="email"
                                name="email"
                                value="<?= htmlspecialchars($email) ?>"
                                class="form-group__input"
                                required
                            >

                        </label>

                        <label class="form-group">

                            <span class="form-group__label">
                                Password
                            </span>

                            <input
                                type="password"
                                name="password"
                                value=""
                                class="form-group__input"
                                required
                            >

                        </label>

                        <button type="submit" class="button button--large button--full">
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

        <?= $footer ?>

    </div>

</body>

</html>