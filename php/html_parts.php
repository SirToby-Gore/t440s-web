<?php

function html_get_header(): string
{

    $styles = '';
    $styles = str_replace(["\n", '  '], '', file_get_contents(__DIR__ . '/../css/styles.css'));

    return <<<HTML
        <!-- <link rel="stylesheet" href="/css/styles.css"> -->
        <style>{$styles}</style>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
            rel="stylesheet"
        >

        <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">

        <title>The One App</title>
    HTML;
}


function html_get_logo(): string
{
    return <<<HTML
        <div class="logo">
            <img class="logo__img" src="/favicon.png">
        </div>
    HTML;
}

function get_user_dashboard(): string
{
    global $account;

    return match ($account->user->role) {
        'user' => 'user',
        'finance' => 'finance-adviser',
        'manager' => 'managers',
    };
}

function html_get_navbar(): string
{
    global $account;

    $logo = html_get_logo();

    $user_section = <<<HTML
        <a href="/login" class="navbar__signin">
            SIGN IN
        </a>

        <a href="/signup" class="button button--primary button--small">
            SIGN UP
        </a>
    HTML;

    if ($account) {
        $user_dashboard = get_user_dashboard();

        $user_section = <<<HTML
            <a href="/{$user_dashboard}">
                Dashboard
            </a>

            <a href="/login?logout">
                Logout
            </a>
        HTML;
    }

    return <<<HTML
        <details class="navbar" open>

            <summary class="navbar__summary">
                {$logo}
            </summary>

            <div class="navbar__content">
                <nav class="navbar__nav">

                    <a href="/home/" class="navbar__link">
                        HOME
                    </a>

                    <a href="/home/about.php" class="navbar__link">
                        ABOUT
                    </a>

                    <a href="/home/features.php" class="navbar__link">
                        FEATURES
                    </a>

                    <a href="/home/support.php" class="navbar__link">
                        SUPPORT
                    </a>

                    <a href="/home/faq.php" class="navbar__link">
                        FAQ
                    </a>

                    <a href="/home/blog.php" class="navbar__link">
                        BLOG
                    </a>

                </nav>

                <div class="navbar__actions">

                    {$user_section}

                </div>
            </div>

            <script>
                const navbar = document.querySelector('.navbar');
                if (window.innerWidth <= 768) {
                    navbar.removeAttribute('open');
                } else {
                    navbar.setAttribute('open', '');
                }
            </script>

        </details>
    HTML;
}


function html_get_footer(): string
{
    $logo = html_get_logo();

    return <<<HTML
        <footer class="footer">

            <div class="footer__brand">

                {$logo}

                <p class="footer__quote">
                    Take control of your finances with The One App.
                </p>

                <div class="footer__socials">

                    <a href="twitter">
                        <span class="footer__social-icon">
                            <img src="" alt="">
                        </span>
                    </a>
                    <a href="linkedin">
                        <span class="footer__social-icon">
                            <img src="" alt="">
                        </span>
                    </a>
                    <a href="">
                        <span class="footer__social-icon">
                            <img src="" alt="">
                        </span>
                    </a>
                    <a href="">
                        <span class="footer__social-icon">
                            <img src="" alt="">
                        </span>
                    </a>

                </div>

            </div>

            <div class="footer__column">

                <h3 class="footer__heading">
                    CONTACT US
                </h3>

                <a class="footer__text" href="email:support@theoneapp.com">
                    support@theoneapp.com
                </a>    

                <a class="footer__text" href="tel:+44 0000 000000">
                    +44 0000 000000
                </a>

            </div>

            <div class="footer__column">

                <h3 class="footer__heading">
                    LINKS
                </h3>

                <a href="/home/" class="footer__link">HOME</a>
                <a href="/home/about.php" class="footer__link">ABOUT</a>
                <a href="/home/features.php" class="footer__link">FEATURES</a>
                <a href="/home/faq.php" class="footer__link">FAQ</a>
                <a href="/home/support.php" class="footer__link">SUPPORT</a>
                <a href="/home/blog.php" class="footer__link">BLOG</a>

            </div>

            <div class="footer__column">

                <h3 class="footer__heading">
                    LEGAL
                </h3>

                <a href="/legal/tos.php" class="footer__link">
                    TERMS OF SERVICE
                </a>

                <a href="/legal/privacy.php" class="footer__link">
                    PRIVACY POLICY
                </a>

                <a href="/legal/cookies.php" class="footer__link">
                    COOKIES
                </a>

                <a href="/legal/equality.php" class="footer__link">
                    EQUALITY AND DIVERSITY
                </a>

            </div>

        </footer>
    HTML;
}