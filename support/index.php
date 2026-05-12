<?php

require_once __DIR__ . '/../php/init.php';

$activePage = 'support';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?= $header ?>

    <title>Support | The One App</title>

</head>

<body>

    <div class="page">

        <?= $navbar ?>

        <main class="support section">

            <div class="container">

                <div class="support__header">

                    <span class="section-label">
                        Support
                    </span>

                    <h1 class="section-title">
                        How can we help?
                    </h1>

                    <p class="section-text support__intro">
                        If you need help with your account, budgeting tools,
                        or technical issues, we're here to support you.
                    </p>

                </div>

                <div class="support__grid">

                    <a href="/faq" class="support-card">

                        <h2 class="support-card__title">
                            FAQs
                        </h2>

                        <p class="support-card__text">
                            Find answers to the most commonly asked questions.
                        </p>

                    </a>

                    <a href="mailto:support@theoneapp.com" class="support-card">

                        <h2 class="support-card__title">
                            Email Support
                        </h2>

                        <p class="support-card__text">
                            Contact our support team directly via email.
                        </p>

                    </a>

                    <a href="#" class="support-card">

                        <h2 class="support-card__title">
                            Account Help
                        </h2>

                        <p class="support-card__text">
                            Get help with login, registration, and account settings.
                        </p>

                    </a>

                    <a href="#" class="support-card">

                        <h2 class="support-card__title">
                            Technical Issues
                        </h2>

                        <p class="support-card__text">
                            Report bugs or issues with the platform.
                        </p>

                    </a>

                </div>

            </div>

        </main>

        <?= $footer ?>

    </div>

</body>

</html>