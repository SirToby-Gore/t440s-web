<?php

require_once __DIR__ . '/../php/init.php';

$activePage = 'features';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?= $header ?>

    <title>Features | The One App</title>

</head>

<body>

    <div class="page">

        <?= $navbar ?>

        <main class="features section">

            <div class="container">

                <div class="features__header">

                    <span class="section-label">
                        Features
                    </span>

                    <h1 class="section-title">
                        Everything you need to manage your finances
                    </h1>

                    <p class="section-text features__intro">
                        The One App helps you stay organised, save smarter,
                        and understand your spending with powerful tools
                        built for everyday use.
                    </p>

                </div>

                <div class="features__grid">

                    <article class="feature-card">

                        <div class="feature-card__icon">
                            $
                        </div>

                        <h2 class="feature-card__title">
                            Budget Tracking
                        </h2>

                        <p class="feature-card__text">
                            Monitor income and expenses in real time with
                            simple and organised budget tracking tools.
                        </p>

                    </article>

                    <article class="feature-card">

                        <div class="feature-card__icon">
                            %
                        </div>

                        <h2 class="feature-card__title">
                            Smart Savings
                        </h2>

                        <p class="feature-card__text">
                            Create personalised savings goals and track
                            your progress with automated insights.
                        </p>

                    </article>

                    <article class="feature-card">

                        <div class="feature-card__icon">
                            #
                        </div>

                        <h2 class="feature-card__title">
                            Spending Analytics
                        </h2>

                        <p class="feature-card__text">
                            Understand spending habits with easy-to-read
                            charts, reports, and financial summaries.
                        </p>

                    </article>

                    <article class="feature-card">

                        <div class="feature-card__icon">
                            !
                        </div>

                        <h2 class="feature-card__title">
                            Smart Alerts
                        </h2>

                        <p class="feature-card__text">
                            Receive reminders and notifications to stay
                            on top of payments, budgets, and savings.
                        </p>

                    </article>

                    <article class="feature-card">

                        <div class="feature-card__icon">
                            @
                        </div>

                        <h2 class="feature-card__title">
                            Secure Accounts
                        </h2>

                        <p class="feature-card__text">
                            Keep your information protected with secure
                            authentication and account protection tools.
                        </p>

                    </article>

                    <article class="feature-card">

                        <div class="feature-card__icon">
                            &
                        </div>

                        <h2 class="feature-card__title">
                            Cross-Device Access
                        </h2>

                        <p class="feature-card__text">
                            Access your financial dashboard anytime from
                            desktop, tablet, or mobile devices.
                        </p>

                    </article>

                </div>

            </div>

        </main>

        <?= $footer ?>

    </div>

</body>

</html>