<?php

$header = <<<HTML
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="/css/styles.css">
    <link rel="shortcut icon" href="/favicon.jpg" type="image/x-icon">

    <title>The One App</title>
HTML;


$logo = <<<HTML
    <a href="/" class="logo">
        <img class="logo__img" src="/favicon.jpg">
    </a>
HTML;


$navbar = <<<HTML
    <header class="navbar">

        {$logo}

        <nav class="navbar__nav">

            <a href="/about" class="navbar__link">
                ABOUT
            </a>

            <a href="/features" class="navbar__link">
                FEATURES
            </a>

            <a href="/support" class="navbar__link">
                SUPPORT
            </a>

            <a href="/faq" class="navbar__link">
                FAQ
            </a>

            <a href="/blog" class="navbar__link">
                BLOG
            </a>

        </nav>

        <div class="navbar__actions">

            <a href="/login" class="navbar__signin">
                SIGN IN
            </a>

            <a href="/signup" class="button button--primary button--small">
                SIGN UP
            </a>

        </div>

    </header>
HTML;


$footer = <<<HTML
    <footer class="footer">

        <div class="footer__brand">

            <h2 class="footer__logo">
                LOGO
            </h2>

            <p class="footer__quote">
                Take control of your finances with The One App.
            </p>

            <div class="footer__socials">

                <span class="footer__social-icon"></span>
                <span class="footer__social-icon"></span>
                <span class="footer__social-icon"></span>
                <span class="footer__social-icon"></span>

            </div>

        </div>

        <div class="footer__column">

            <h3 class="footer__heading">
                CONTACT US
            </h3>

            <p class="footer__text">
                support@theoneapp.com
            </p>

            <p class="footer__text">
                +44 0000 000000
            </p>

        </div>

        <div class="footer__column">

            <h3 class="footer__heading">
                LINKS
            </h3>

            <a href="/" class="footer__link">HOME</a>
            <a href="/about" class="footer__link">ABOUT</a>
            <a href="/features" class="footer__link">FEATURES</a>
            <a href="/faq" class="footer__link">FAQ</a>
            <a href="/support" class="footer__link">SUPPORT</a>
            <a href="/blog" class="footer__link">BLOG</a>

        </div>

        <div class="footer__column">

            <h3 class="footer__heading">
                LEGAL
            </h3>

            <a href="#" class="footer__link">
                TERMS OF SERVICE
            </a>

            <a href="#" class="footer__link">
                PRIVACY POLICY
            </a>

            <a href="#" class="footer__link">
                COOKIES
            </a>

            <a href="#" class="footer__link">
                EQUALITY AND DIVERSITY
            </a>

        </div>

    </footer>
HTML;