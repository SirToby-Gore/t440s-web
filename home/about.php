<?php

require_once __DIR__ . '/../php/init.php';

$activePage = 'about';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?= html_get_header() ?>

    <title>About | The One App</title>

</head>

<body>

    <div class="page">
        <?= html_get_navbar() ?>

        <main class="about section">

            <div class="container">

                <div class="about__header">

                    <h1 class="section-title">
                        Helping you take control of your finances
                    </h1>

                    <p class="section-text about__intro">
                        The One App is designed to simplify money management,
                        help you save smarter, and give you full visibility
                        over your financial future.
                    </p>

                </div>

                <div class="about__grid">

                    <article class="card">

                        <h2 class="card__title">
                            Our Mission
                        </h2>

                        <p class="card__text">
                            We aim to make personal finance simple,
                            accessible, and stress-free for everyone.
                        </p>

                    </article>

                    <article class="card">

                        <h2 class="card__title">
                            Smart Budgeting
                        </h2>

                        <p class="card__text">
                            Track spending, build healthy habits,
                            and stay in control with intuitive tools.
                        </p>

                    </article>

                    <article class="card">

                        <h2 class="card__title">
                            Future Focused
                        </h2>

                        <p class="card__text">
                            We continuously improve our platform
                            with features built around real user needs.
                        </p>

                    </article>

                </div>

            </div>

        </main>

        <?= html_get_footer() ?>

    </div>

</body>

</html>