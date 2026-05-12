<?php

require_once __DIR__ . '/../php/init.php';

$activePage = 'blog';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?= $header ?>

    <title>Blog | The One App</title>

</head>

<body>

    <div class="page">

        <!-- NAVBAR -->
        <?= $navbar ?>

        <!-- BLOG -->
        <main class="blog section">

            <div class="container">

                <div class="blog__header">

                    <span class="section-label">
                        BLOG
                    </span>

                    <h1 class="section-title">
                        Financial tips, guides, and updates
                    </h1>

                    <p class="section-text blog__intro">
                        Stay informed with the latest budgeting advice,
                        savings strategies, and financial insights from
                        The One App team.
                    </p>

                </div>

                <div class="blog__grid">

                    <article class="blog-card">

                        <div class="blog-card__content">

                            <span class="blog-card__category">
                                SAVING
                            </span>

                            <h2 class="blog-card__title">
                                5 simple ways to save more money each month
                            </h2>

                            <p class="blog-card__text">
                                Learn practical strategies to reduce unnecessary
                                spending and grow your savings consistently.
                            </p>

                            <a href="#" class="blog-card__link">
                                Read More
                            </a>

                        </div>

                    </article>

                    <article class="blog-card">

                        <div class="blog-card__content">

                            <span class="blog-card__category">
                                BUDGETING
                            </span>

                            <h2 class="blog-card__title">
                                How to build a budget that actually works
                            </h2>

                            <p class="blog-card__text">
                                Create a realistic financial plan that fits
                                your lifestyle and long-term goals.
                            </p>

                            <a href="#" class="blog-card__link">
                                Read More
                            </a>

                        </div>

                    </article>

                    <article class="blog-card">

                        <div class="blog-card__content">

                            <span class="blog-card__category">
                                FINANCE
                            </span>

                            <h2 class="blog-card__title">
                                Understanding your spending habits
                            </h2>

                            <p class="blog-card__text">
                                Discover how tracking your purchases can help
                                improve financial decision-making.
                            </p>

                            <a href="#" class="blog-card__link">
                                Read More
                            </a>

                        </div>

                    </article>

                </div>

            </div>

        </main>

        <!-- FOOTER -->
        <?= $footer ?>

    </div>

</body>

</html>