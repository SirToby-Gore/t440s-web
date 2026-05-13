<?php

require_once __DIR__ . '/../php/init.php';

$activePage = 'faq';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?= html_get_header() ?>

    <title>FAQ | The One App</title>

</head>

<body>

    <div class="page">

        <?= html_get_navbar() ?>

        <main class="section">

            <div class="container">

                <div class="section__header">

                    <h1 class="section-title">
                        Frequently asked questions
                    </h1>

                    <p class="section-text">
                        Find answers to common questions about The One App,
                        budgeting, account management, and platform features.
                    </p>

                </div>

                <div class="faq-list">

                    <article class="card card--faq">

                        <h2 class="card__title">
                            What is The One App?
                        </h2>

                        <p class="card__text">
                            The One App is a personal finance platform designed
                            to help users manage spending, track budgets,
                            and improve financial habits.
                        </p>

                    </article>

                    <article class="card card--faq">

                        <h2 class="card__title">
                            Is The One App free to use?
                        </h2>

                        <p class="card__text">
                            Yes, core budgeting and tracking features are
                            available for free with optional premium upgrades.
                        </p>

                    </article>

                    <article class="card card--faq">

                        <h2 class="card__title">
                            Can I track savings goals?
                        </h2>

                        <p class="card__text">
                            Absolutely. You can create savings goals,
                            monitor progress, and stay motivated over time.
                        </p>

                    </article>

                    <article class="card card--faq">

                        <h2 class="card__title">
                            Is my financial data secure?
                        </h2>

                        <p class="card__text">
                            Security is a priority. Your data is protected
                            using secure authentication and encryption practices.
                        </p>

                    </article>

                </div>

            </div>

        </main>

        <?= html_get_footer() ?>

    </div>

</body>

</html>