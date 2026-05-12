<!DOCTYPE html>
<html lang="en">
<?php

require_once '../src/php/requirements.php';

?>
<head>
	<?= render_header(title: 'login') ?>
</head>
<body>
	<?= render_navbar() ?>

	<main class="login">
		<section class="login">
			<h1 class="sub-heading capitalise">login</h1>
			
			<form action="login.php" method="post" class="login-form">
				<!-- email div to hold the user's email -->
				<div class="email">
					<label for="email" class="label">email</label>
					<!-- using cookies to hold the user's email if it is invalid and where sent back from the login -->
					<input type="email" name="email" placeholder="<?= get_placeholder_email(); ?>" required value="<?= fetch_cookie(name: 'tmp_email', default: '', unset_value: true) ?>">
				</div>
				
				<!-- password element to hold the user's password input and the toggle password button -->
				<div class="password">
					<label for="password" class="label">password</label>
					<!-- using cookies to store the user's password if they where sent back from the login -->
					<input id="password-input" type="password" name="password" placeholder="<?= get_placeholder_password(); ?>" required minlength="8" value="<?= fetch_cookie(name: 'tmp_password', default: '', unset_value: true) ?>">
					<button type="button" id="toggle-password-button" class="secondary button">toggle password</button>
				</div>
				
				<!-- displays an error message -->
				<p class="feedback-text small error"><?= fetch_cookie(name: 'tmp_error_message', unset_value: true) ?? '' ?></p>
				
				<div class="account-options">
					<input type="submit" value="Login" class="primary button">
					<button class="secondary button" onclick="window.location.href = '../register/'">register</button>
				</div>
			</form>
		</section>
	</main>
    <?= render_footer() ?>

	<script src="../src/js/password_button.js"></script>
</body>
</html>