<?php
require_once __DIR__ . '/../php/init.php';

if (!$account || $account->user->role !== 'finance') {
    header('Location: /login');
    exit;
}

$activePage = 'templates';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= html_get_header() ?>
    <title>Financial Report Templates | The One App</title>
</head>

<body>
    <div class="page">
        <?= html_get_navbar() ?>

        <main class="templates section">
            <div class="container">
                <header class="section__header" style="text-align: left; margin-bottom: 3rem;">
                    <h1 class="section-title section-title--report">Financial Report Templates:</h1>
                </header>

                <div class="templates__grid">
                    <article class="template-card">
                        <div class="template-card__header">
                            <span class="template-card__icon">📊</span>
                            <h2 class="template-card__title">Portfolio Summary</h2>
                        </div>
                        <p class="template-card__description">Provides an overview of a client's investment, asset
                            allocation and performance.</p>
                        <div class="template-card__includes">
                            <strong>INCLUDES:</strong>
                            <ul class="template-card__list">
                                <li>Client info</li>
                                <li>Portfolio allocation</li>
                                <li>Key recommendation</li>
                                <li>Investments</li>
                            </ul>
                        </div>
                        <button class="button button--primary button--full">USE TEMPLATE</button>
                    </article>

                    <article class="template-card">
                        <div class="template-card__header">
                            <span class="template-card__icon">👤</span>
                            <h2 class="template-card__title">Client Overview</h2>
                        </div>
                        <p class="template-card__description">Provide full summary of the client's financial overview.
                        </p>
                        <div class="template-card__includes">
                            <strong>INCLUDES:</strong>
                            <ul class="template-card__list">
                                <li>Income sources</li>
                                <li>Expenses</li>
                                <li>Assets</li>
                                <li>Liabilities</li>
                            </ul>
                        </div>
                        <button class="button button--primary button--full">USE TEMPLATE</button>
                    </article>

                    <article class="template-card">
                        <div class="template-card__header">
                            <span class="template-card__icon">🛡️</span>
                            <h2 class="template-card__title">Risk Assessment</h2>
                        </div>
                        <p class="template-card__description">Evaluates a client's financial risk tolerance and
                            investment capacity.</p>
                        <div class="template-card__includes">
                            <strong>INCLUDES:</strong>
                            <ul class="template-card__list">
                                <li>Risk profile</li>
                                <li>Financial stability</li>
                                <li>Adviser notes</li>
                                <li>Risk level summary</li>
                            </ul>
                        </div>
                        <button class="button button--primary button--full">USE TEMPLATE</button>
                    </article>

                    <article class="template-card">
                        <div class="template-card__header">
                            <span class="template-card__icon">📈</span>
                            <h2 class="template-card__title">Invest Recommendation</h2>
                        </div>
                        <p class="template-card__description">To present suggested investments based on client goals and
                            risk tolerance.</p>
                        <div class="template-card__includes">
                            <strong>INCLUDES:</strong>
                            <ul class="template-card__list">
                                <li>Client goals</li>
                                <li>Risk profile</li>
                                <li>Recommended assets</li>
                                <li>Expected outcomes</li>
                            </ul>
                        </div>
                        <button class="button button--primary button--full">USE TEMPLATE</button>
                    </article>

                    <article class="template-card">
                        <div class="template-card__header">
                            <span class="template-card__icon">📉</span>
                            <h2 class="template-card__title">Spending Analysis</h2>
                        </div>
                        <p class="template-card__description">Breakdown of client's spending patterns and budgeting
                            insights.</p>
                        <div class="template-card__includes">
                            <strong>INCLUDES:</strong>
                            <ul class="template-card__list">
                                <li>Spending categories</li>
                                <li>Monthly trends</li>
                                <li>Budget comparison</li>
                                <li>Saving opportunities</li>
                            </ul>
                        </div>
                        <button class="button button--primary button--full">USE TEMPLATE</button>
                    </article>

                    <article class="template-card">
                        <div class="template-card__header">
                            <span class="template-card__icon">🌴</span>
                            <h2 class="template-card__title">Retirement Planning</h2>
                        </div>
                        <p class="template-card__description">Helps outline retirement saving strategies.</p>
                        <div class="template-card__includes">
                            <strong>INCLUDES:</strong>
                            <ul class="template-card__list">
                                <li>Current savings</li>
                                <li>Retirement goals</li>
                                <li>Investment Strategies</li>
                                <li>Long term projections</li>
                            </ul>
                        </div>
                        <button class="button button--primary button--full">USE TEMPLATE</button>
                    </article>
                </div>
            </div>
        </main>

        <?= html_get_footer() ?>
    </div>
</body>

</html>