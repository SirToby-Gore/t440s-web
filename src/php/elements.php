<?php

require_once 'requirements.php';

/*
 * returns an associative array with links to the different pages for the nav bar.
 * each key is the name and value if the href.
 **/
function get_nav_bar_links(): array {    
	return [
		'home' => [
			'name' => 'home',
			'href' => '../home/',
			'src' => '../src/assets/icons/home.svg'
		],
		'consultation' => [
			'name' => 'consultation',
			'href' => '../consultation/',
			'src' => '../src/assets/icons/consultation.svg'
		],
		'calculation' => [
			'name' => 'calculation',
			'href' => '../calculation/',
			'src' => '../src/assets/icons/calc.svg'
		],
	];
}

/*
 * returns a string of the navbar in HTML, to be put into a tag.
 * ```php
 * <?= render_navbar ?>
 * ```
 **/
function render_navbar(): string {
	// inits the lines for the nav bar
	$navbar_lines = [];

	// creates the parent tag of the navbar
	$navbar_lines[] = "<nav class=\"navbar\">";

	// adds the logo element to the tag
	$navbar_lines[] = "<span class=\"rolsa-logo\">Rolsa</span>";

	// creates the parent tag for the links
	$navbar_lines[] = "<ul class=\"links\">";

	// adds each link in-turn
	foreach ( get_nav_bar_links() as $link_name => $info) {
		$navbar_lines[] = "<a href=\"" . $info['href'] . "\" title=\"" . strtolower(string: $info['name']) . "\">" . "<img src=\"" . $info['src'] . "\">" . "</a>";
	}
	
	// closes the links tag
	$navbar_lines[] = "</ul>";

	$user = fetch_cookie(name: 'user');
	
	// adds the profile of the user if applicable
	if ($user != null) {
		// create the parent link for the user
		$navbar_lines[] = "<a class=\"account-icon\" href=\"../account/\" title=\"account settings\">";
		
		// makes a link adds the user's profile picture
		$navbar_lines[] = "<img src=\"" . ($user['img_url'] ?? '../src/assets/images/placeholder-profile.jpg') . "\" alt=\"profile picture\">";
		
		// adds their username
		$navbar_lines[] = "<p class=\"username capitalise\">" . $user['username'] . "</p>";

		// closes the parent tag for the user
		$navbar_lines[] = "</a>";
	}
	// if the user is not logged in then it shows the log in button
	else {
		// adds a button for the user to login with
		$navbar_lines[] = "<a class=\"login-button button capitalise\" href=\"../login/\" title=\"login\">login</a>";
	}

	// closes the parent tag for the nav bar
	$navbar_lines[] = "</nav>";

	// renders the navbar and joins it on `\n`
	return implode(separator: "\n", array: $navbar_lines);
}

/*
 * returns a rendered HTML string for the inside of the header tag
 * ```php
 * <head>
 *        <? render_header('page name') ?>
 * </head>
 **/
function render_header(string $title): string {
	$header_lines = [];

	// adds the encoding
	$header_lines[] = "<meta charset=\"UTF-8\">";

	// sets the start size of the website to fill the window
	$header_lines[] = "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";

	// adds the title for the page
	$header_lines[] = "<title>" . $title . "</title>";

	// links to the css
	$header_lines[] = "<link rel=\"stylesheet\" href=\"../src/css/styles.css\">";

	// links the local css to the page
	$header_lines[] = "<link rel=\"stylesheet\" href=\"styles.css\">";

	// links the favicon
	$header_lines[] = "<link rel=\"shortcut icon\" href=\"../src/assets/images/favicon.svg\" type=\"image/x-icon\">";

	// returns the header lines joined on `\n`
	return implode(separator:"\n", array: $header_lines);
}

/*
 * returns a rendered HTML string for the inside of the footer tag
 * ```php
 *        <?= render_footer() ?>
 * </body>
 **/
function render_footer(): string {
	$footer_lines = [];

	// opens the footer tag
	$footer_lines[] = "<footer class=\"footer\">";

	$footer_lines[] = "<div class=\"left\">";

	$footer_lines[] = "<div class=\"contact-info\">";
	
	// replace for actual email and phone number
	$footer_lines[] = "<a href=\"tel:01248 567800\" class=\"telephone\">01248 567800</a>";
	$footer_lines[] = "<a href=\"mailto:info@rolsa.co.uk\" class=\"email\">info@rolsa.co.uk</a>";

	$footer_lines[] = "</div>";

	$footer_lines[] = "<address class=\"address\">";

	// put actual address here
	// in the meantime use this temporary address
	$footer_lines[] = "<span class=\"address-line\">142</span>";
	$footer_lines[] = "<span class=\"address-line\">Duke Street</span>";
	$footer_lines[] = "<span class=\"address-line\">Norwich</span>";
	$footer_lines[] = "<span class=\"address-line\">NR3 1DL</span>";

	$footer_lines[] = "</address>";

	$footer_lines[] = "</div>";

	$footer_lines[] = "<div class=\"right\">";

	$footer_lines[] = "<h2 class=\"rolsa-logo\">ROLSA</h2>";

	$footer_lines[] = "<span class=\"copyright\">ROLSA&copy;2025</span>";

	$footer_lines[] = "<div id=\"accessibility-buttons-container\"></div>";

	$footer_lines[] = "</div>";

	$footer_lines[] = "<script src=\"../src/js/accessibility_buttons.js\"></script>";
	
	// closes the footer tag
	$footer_lines[] = "</footer>";

	return implode(separator: "\n", array: $footer_lines);
}

function get_placeholder_email(): string {
	return 'john.smith@example.com';
}

function get_placeholder_username(): string {
	return 'John Smith';
}

function get_placeholder_password(): string {
	return 'password 1234';
}