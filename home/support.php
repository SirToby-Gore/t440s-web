<?php

require_once __DIR__ . '/../php/init.php';

$activePage = 'support';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?= html_get_header() ?>

    <title>Support | The One App</title>

</head>

<body>

    <div class="page">

        <?= html_get_navbar() ?>

        <main class="support section">

            <div class="container">

                <div class="support__header">

                    <h1 class="section-title">
                        How can we help?
                    </h1>

                    <p class="section-text support__intro">
                        If you need help with your account, budgeting tools,
                        or technical issues, we're here to support you.
                    </p>

                </div>

                <div class="support__grid">

                    <a href="/faq" class="card">

                        <h2 class="card__title">
                            FAQs
                        </h2>

                        <p class="card__text">
                            Find answers to the most commonly asked questions.
                        </p>

                    </a>

                    <a href="mailto:support@theoneapp.com" class="card">

                        <h2 class="card__title">
                            Email Support
                        </h2>

                        <p class="card__text">
                            Contact our support team directly via email.
                        </p>

                    </a>

                    <a href="#" class="card">

                        <h2 class="card__title">
                            Account Help
                        </h2>

                        <p class="card__text">
                            Get help with login, registration, and account settings.
                        </p>

                    </a>

                    <a href="#" class="card">

                        <h2 class="card__title">
                            Technical Issues
                        </h2>

                        <p class="card__text">
                            Report bugs or issues with the platform.
                        </p>

                    </a>

                </div>

            </div>

        </main>

        <?= html_get_footer() ?>

    </div>

</body>

</html>