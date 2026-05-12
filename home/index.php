<?php

require_once __DIR__ . '/../php/init.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $header ?>
</head>

<body>

    <div class="page">

        <!-- NAVBAR -->
        <header class="navbar">

            <div class="navbar__logo">
                LOGO
            </div>

            <nav class="navbar__nav">
                <a href="#" class="navbar__link">ABOUT</a>
                <a href="#" class="navbar__link">FEATURES</a>
                <a href="#" class="navbar__link">SUPPORT</a>
                <a href="#" class="navbar__link">FAQ</a>
                <a href="#" class="navbar__link">BLOG</a>
            </nav>

            <div class="navbar__actions">
                <a href="/login" class="navbar__signin">
                    SIGN IN
                </a>

                <a href="/signup" class="button button--small">
                    SIGN UP
                </a>
            </div>

        </header>

        <!-- HERO -->
        <main class="hero">

            <h1 class="hero__title">
                Take control of your finances
            </h1>

            <p class="hero__description">
                Manage your money, save more money and budget smartly today
                when you sign up with [INSERT]
            </p>

            <a href="/signup" class="button button--large">
                SIGN UP
            </a>

        </main>

        <!-- FOOTER -->
        <footer class="footer">

            <div class="footer__brand">
                <h2 class="footer__logo">LOGO</h2>

                <p class="footer__quote">
                    QUOTE
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

                <p class="footer__text">EMAIL</p>
                <p class="footer__text">PHONE NUMBER</p>
            </div>

            <div class="footer__column">
                <h3 class="footer__heading">
                    LINKS
                </h3>

                <a href="#" class="footer__link">HOME</a>
                <a href="#" class="footer__link">ABOUT</a>
                <a href="#" class="footer__link">FEATURES</a>
                <a href="#" class="footer__link">FAQ</a>
                <a href="#" class="footer__link">SUPPORT</a>
                <a href="#" class="footer__link">BLOG</a>
            </div>

            <div class="footer__column">
                <h3 class="footer__heading">
                    LEGAL
                </h3>

                <a href="#" class="footer__link">TERMS OF SERVICE</a>
                <a href="#" class="footer__link">PRIVACY POLICY</a>
                <a href="#" class="footer__link">COOKIES</a>
                <a href="#" class="footer__link">EQUALITY AND DIVERSITY</a>
            </div>

        </footer>

    </div>

</body>

</html>