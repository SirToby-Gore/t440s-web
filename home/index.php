<?php

require_once __DIR__ . '/../php/init.php';

$activePage = 'home';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?= $header ?>

    <title>Home | The One App</title>

</head>

<body>

    <div class="page">

        <?= $navbar ?>

        <main class="hero">

            <div class="container">

                <div class="hero__content">

                    <span class="section-label">
                        Smart finance management
                    </span>

                    <h1 class="hero__title">
                        Take control of your finances
                    </h1>

                    <p class="hero__description">
                        Manage your spending, grow your savings, and build
                        smarter financial habits with The One App.
                    </p>

                    <div class="hero__actions">

                        <a href="/signup" class="button button--large">
                            Get Started
                        </a>

                        <a href="/features" class="button button--outline">
                            Explore Features
                        </a>

                    </div>

                </div>

            </div>

        </main>

        <section class="home-features section">

            <div class="container">

                <div class="home-features__header">

                    <span class="section-label">
                        Features
                    </span>

                    <h2 class="section-title">
                        Everything you need in one place
                    </h2>

                </div>

                <div class="home-features__grid">

                    <article class="feature-card">

                        <div class="feature-card__icon">
                            $
                        </div>

                        <h3 class="feature-card__title">
                            Budget Tracking
                        </h3>

                        <p class="feature-card__text">
                            Track income and expenses with an easy-to-use
                            budgeting dashboard.
                        </p>

                    </article>

                    <article class="feature-card">

                        <div class="feature-card__icon">
                            %
                        </div>

                        <h3 class="feature-card__title">
                            Savings Goals
                        </h3>

                        <p class="feature-card__text">
                            Set goals, monitor progress, and build
                            better saving habits over time.
                        </p>

                    </article>

                    <article class="feature-card">

                        <div class="feature-card__icon">
                            #
                        </div>

                        <h3 class="feature-card__title">
                            Smart Insights
                        </h3>

                        <p class="feature-card__text">
                            Understand your spending with visual reports
                            and personalised insights.
                        </p>

                    </article>

                </div>

            </div>

        </section>

        <?= $footer ?>

    </div>

</body>

</html>
