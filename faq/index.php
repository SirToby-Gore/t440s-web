<?php

require_once __DIR__ . '/../php/init.php';

$activePage = 'faq';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?= $header ?>

    <title>FAQ | The One App</title>

</head>

<body>

    <div class="page">

        <?= $navbar ?>

        <main class="faq section">

            <div class="container">

                <div class="faq__header">

                    <span class="section-label">
                        FAQ
                    </span>

                    <h1 class="section-title">
                        Frequently asked questions
                    </h1>

                    <p class="section-text faq__intro">
                        Find answers to common questions about The One App,
                        budgeting, account management, and platform features.
                    </p>

                </div>

                <div class="faq__list">

                    <article class="faq-item">

                        <h2 class="faq-item__question">
                            What is The One App?
                        </h2>

                        <p class="faq-item__answer">
                            The One App is a personal finance platform designed
                            to help users manage spending, track budgets,
                            and improve financial habits.
                        </p>

                    </article>

                    <article class="faq-item">

                        <h2 class="faq-item__question">
                            Is The One App free to use?
                        </h2>

                        <p class="faq-item__answer">
                            Yes, core budgeting and tracking features are
                            available for free with optional premium upgrades.
                        </p>

                    </article>

                    <article class="faq-item">

                        <h2 class="faq-item__question">
                            Can I track savings goals?
                        </h2>

                        <p class="faq-item__answer">
                            Absolutely. You can create savings goals,
                            monitor progress, and stay motivated over time.
                        </p>

                    </article>

                    <article class="faq-item">

                        <h2 class="faq-item__question">
                            Is my financial data secure?
                        </h2>

                        <p class="faq-item__answer">
                            Security is a priority. Your data is protected
                            using secure authentication and encryption practices.
                        </p>

                    </article>

                </div>

            </div>

        </main>

        <?= $footer ?>

    </div>

</body>

</html>