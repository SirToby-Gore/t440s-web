<?php
require_once __DIR__ . '/../php/init.php';

if (!$account || $account->user->role !== 'finance') {
    header('Location: /login');
    exit;
}

$activePage = 'market';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= html_get_header() ?>
    <title>Market News | The One App</title>
</head>

<body>
    <div class="page">
        <?= html_get_navbar() ?>

        <main class="market section">
            <div class="container">
                <header class="section__header">
                    <h1 class="section-title">Latest market news</h1>
                </header>

                <div class="market__grid">

                    <section class="market__column">
                        <div class="card card--market-blue">
                            <h2 class="market__subheading">Stock Market updates:</h2>
                            <div class="market__inner-card">
                                <h3 class="market__category-title">MAJOR INDICES:</h3>
                                <ul class="market__list">
                                    <li class="market__item">FTSE 100 — 7,642 <span class="trend trend--up">▲
                                            +0.8%</span></li>
                                    <li class="market__item">S&P 500 — 4,910 <span class="trend trend--up">▲
                                            +0.5%</span></li>
                                    <li class="market__item">NASDAQ — 15,230 <span class="trend trend--down">▼
                                            -0.3%</span></li>
                                    <li class="market__item">Dow Jones — 38,102 <span class="trend trend--up">▲
                                            +0.2%</span></li>
                                </ul>

                                <h3 class="market__category-title">CRYPTO:</h3>
                                <ul class="market__list">
                                    <li class="market__item">Bitcoin — $61,200 <span class="trend trend--up">▲
                                            +1.1%</span></li>
                                    <li class="market__item">Ethereum — $3,250 <span class="trend trend--up">▲
                                            +0.7%</span></li>
                                </ul>

                                <h3 class="market__category-title">COMMODITIES:</h3>
                                <ul class="market__list">
                                    <li class="market__item">Gold — $2,015 <span class="trend trend--up">▲ +0.4%</span>
                                    </li>
                                    <li class="market__item">Oil (Brent) — $83.10 <span class="trend trend--down">▼
                                            -0.6%</span></li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <section class="market__column">
                        <div class="card card--market-blue">
                            <h2 class="market__subheading">Key financial headlines:</h2>

                            <article class="headline-block">
                                <h3 class="headline-block__title">Interest Rates Expected to Rise Again</h3>
                                <p class="headline-block__text">Central banks signal further tightening amid inflation
                                    concerns.</p>
                                <a href="#" class="headline-block__link">[Read more]</a>
                            </article>

                            <article class="headline-block">
                                <h3 class="headline-block__title">Tech Stocks Rebound After Market Dip</h3>
                                <p class="headline-block__text">Major tech firms report stronger-than-expected earnings.
                                </p>
                                <a href="#" class="headline-block__link">[Read more]</a>
                            </article>

                            <article class="headline-block">
                                <h3 class="headline-block__title">Oil Prices Climb on Supply Concerns</h3>
                                <p class="headline-block__text">Global supply disruptions impact energy markets.</p>
                                <a href="#" class="headline-block__link">[Read more]</a>
                            </article>
                        </div>
                    </section>

                    <section class="market__column">
                        <div class="card card--market-blue mb-md">
                            <h2 class="market__subheading">Market Insights:</h2>
                            <div class="market__inner-card">
                                <h3 class="market__category-title">TODAY'S INSIGHTS:</h3>
                                <p class="section-text section-text--small">
                                    Markets are reacting positively to strong earnings reports, though macroeconomic
                                    uncertainty remains. Portfolio diversification remains key.
                                </p>
                            </div>
                        </div>

                        <div class="card card--market-blue">
                            <h2 class="market__subheading">Sector Highlights:</h2>
                            <div class="market__inner-card">
                                <div class="sector-item">
                                    <h4 class="sector-item__name">Banking</h4>
                                    <p class="section-text section-text--small">Interest rate changes affecting lending
                                        margins.</p>
                                </div>
                                <div class="sector-item">
                                    <h4 class="sector-item__name">Consumer Goods</h4>
                                    <p class="section-text section-text--small">Stable growth with moderate spending
                                        increases.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </main>

        <?= html_get_footer() ?>
    </div>
</body>

</html>