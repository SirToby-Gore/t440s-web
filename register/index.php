<!DOCTYPE html>
<html lang="en">
<?php
require_once '../src/php/requirements.php';
?>
<head>
	<?= render_header(title: 'register') ?>
</head>
<body>
	<?= render_navbar() ?>

	<main class="register">
		<section class="register">
			<h2 class="capitalise sub-heading">Register</h2>

			<form action="register.php" class="register-form" method="post">
				<!-- a div for the user's email, with cached value pre-loaded if possible -->
				<div class="email-input">
					<label for="email" class="label">email</label>
					<input type="email" name="email" id="email" required value="<?= fetch_cookie(name: 'tmp_email', default: '', unset_value: true) ?>" placeholder="<?= get_placeholder_email(); ?>">
				</div>
				
				<!-- a div for the user's name, with cached value pre-loaded if possible -->
				<div class="name-input">
					<label for="username" class="label">name</label>
					<input type="username" name="username" id="username" required value="<?= fetch_cookie(name: 'tmp_username', default: '', unset_value: true) ?>" placeholder="<?= get_placeholder_username() ?>">
				</div>
				
				<!-- a div for the user's password, with cached value pre-loaded if possible -->
			<div class="password-input">
				<label for="password" class="label">password</label>
				<input type="password" name="password" id="password" required value="<?= fetch_cookie(name: 'tmp_password', default: '', unset_value: true) ?>" placeholder="<?= get_placeholder_password() ?>">
			</div>
			
			<!-- a div for the user to confirm their password incase they made a typo, with cached value pre-loaded if possible -->
			<div class="password-conf-input">
				<label for="password-conf" class="label">confirm password</label>
				<input type="password" name="password-conf" id="password-conf" required value="<?= fetch_cookie(name: 'tmp_password-conf', default: '', unset_value: true) ?>" placeholder="<?= get_placeholder_password() ?>">
			</div>

			<button type="button" id="toggle-password-button" class="secondary button">toggle password</button>

			<!-- an input for the user to upload a profile picture -->
			<div class="img-url-input">
				<label for="img-url" class="label">profile picture</label>
				<input type="file" name="img-url" id="img-url" value="upload" placeholder="../assets/images/placeholder-profile.jpg">
			</div>
			
			
			<!-- displays an error message if one is applicable -->
			<?php
			// checks there is an error message to give
			if (isset($_COOKIE['tmp_error_message'])): ?>
				<p class="feedback-text small error"><?= fetch_cookie(name: 'tmp_error_message', unset_value: true) ?></p>
				<?php endif; ?>
				
				<!-- gives the options to the user to login if they have an account or to register with the information they have supplied above -->
				<div class="account-options">
					<input class="primary button" type="submit" value="Register">
					<button class="secondary button" onclick="window.location.href = '../login/ '">login</button>
				</div>
			</form>
		</section>
	</main>
    <?= render_footer() ?>

	<script src="../src/js/password_button.js"></script>
</body>
</html>