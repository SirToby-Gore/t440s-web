<?php

require_once __DIR__ . '/../php/init.php';

$activePage = 'home';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?= html_get_header() ?>

    <title>Home | The One App</title>

</head>

<body>

    <div class="page">

        <?= html_get_navbar() ?>

        <main class="hero">

            <div class="container">

                <div class="hero__content">
                    <h1 class="hero__title">
                        Take control of your finances
                    </h1>

                    <p class="hero__description">
                        Manage your spending, grow your savings, and build
                        smarter financial habits with The One App.
                    </p>

                    <div class="hero__actions">

                        <a href="/signup" class="button button--primary button--large">
                            Get Started
                        </a>

                        <a href="/features" class="button button--outline button--large">
                            Explore Features
                        </a>

                    </div>

                </div>

            </div>

        </main>

        <section class="home-features section">

            <div class="container">

                <div class="home-features__header">

                    <h2 class="section-title">
                        Everything you need in one place
                    </h2>

                </div>

                <div class="home-features__grid row">

                    <article class="card card--feature">

                        <div class="card__icon">
                            $
                        </div>

                        <h3 class="card__title">
                            Budget Tracking
                        </h3>

                        <p class="card__text">
                            Track income and expenses with an easy-to-use
                            budgeting dashboard.
                        </p>

                    </article>

                    <article class="card card--feature">

                        <div class="card__icon">
                            %
                        </div>

                        <h3 class="card__title">
                            Savings Goals
                        </h3>

                        <p class="card__text">
                            Set goals, monitor progress, and build
                            better saving habits over time.
                        </p>

                    </article>

                    <article class="card card--feature">

                        <div class="card__icon">
                            #
                        </div>

                        <h3 class="card__title">
                            Smart Insights
                        </h3>

                        <p class="card__text">
                            Understand your spending with visual reports
                            and personalised insights.
                        </p>

                    </article>

                </div>

            </div>

        </section>

        <?= html_get_footer() ?>

    </div>

</body>

</html>