<?php
require_once __DIR__ . '/../php/init.php';

if (!$account || $account->user->role != 'finance') {
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
                <header class="market__header">
                    <h1 class="market__title">Latest market news:</h1>
                </header>

                <div class="market__grid">
                    <section class="market-col">
                        <div class="market-col__header">
                            <h2>Stock Market updates:</h2>
                        </div>
                        <div class="market-col__content">
                            <div class="market-data">
                                <h3>MAJOR INDICES:</h3>
                                <ul class="market-list">
                                    <li>FTSE 100 — 7,642 <span class="trend trend--up">▲ +0.8%</span></li>
                                    <li>S&P 500 — 4,910 <span class="trend trend--up">▲ +0.5%</span></li>
                                    <li>NASDAQ — 15,230 <span class="trend trend--down">▼ -0.3%</span></li>
                                    <li>Dow Jones — 38,102 <span class="trend trend--up">▲ +0.2%</span></li>
                                </ul>

                                <h3 class="margin-top">CRYPTO:</h3>
                                <ul class="market-list">
                                    <li>Bitcoin — $61,200 <span class="trend trend--up">▲ +1.1%</span></li>
                                    <li>Ethereum — $3,250 <span class="trend trend--up">▲ +0.7%</span></li>
                                </ul>

                                <h3 class="margin-top">COMMODITIES:</h3>
                                <ul class="market-list">
                                    <li>Gold — $2,015 <span class="trend trend--up">▲ +0.4%</span></li>
                                    <li>Oil (Brent) — $83.10 <span class="trend trend--down">▼ -0.6%</span></li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <section class="market-col">
                        <div class="market-col__header market-col__header--alt">
                            <h2>Key financial headlines:</h2>
                        </div>
                        <div class="market-col__content">
                            <article class="news-card">
                                <h3>Interest Rates Expected to Rise Again</h3>
                                <p>Central banks signal further tightening amid inflation concerns.</p>
                                <a href="#" class="news-card__link">[Read more]</a>
                            </article>
                            <article class="news-card">
                                <h3>Tech Stocks Rebound After Market Dip</h3>
                                <p>Major tech firms report stronger-than-expected earnings.</p>
                                <a href="#" class="news-card__link">[Read more]</a>
                            </article>
                            <article class="news-card">
                                <h3>Oil Prices Climb on Supply Concerns</h3>
                                <p>Global supply disruptions impact energy markets.</p>
                                <a href="#" class="news-card__link">[Read more]</a>
                            </article>
                        </div>
                    </section>

                    <section class="market-col">
                        <div class="market-col__header">
                            <h2>Market Insights:</h2>
                        </div>
                        <div class="market-col__content">
                            <div class="insight-box">
                                <h3>TODAY'S INSIGHTS:</h3>
                                <p>Markets are reacting positively to strong earnings reports, though macroeconomic
                                    uncertainty remains. Portfolio diversification remains key.</p>
                            </div>

                            <div class="market-col__header market-col__header--sub">
                                <h2>Sector Highlights:</h2>
                            </div>

                            <div class="insight-box">
                                <a href="#" class="insight-box__link">Banking</a>
                                <p>Interest rate changes affecting lending margins.</p>
                                <a href="#" class="insight-box__link">Consumer Goods</a>
                                <p>Stable growth with moderate spending increases.</p>
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