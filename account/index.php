<!DOCTYPE html>
<html lang="en">
<?php
require_once '../src/php/requirements.php';
if (!is_user_logged_in()) {
	error_and_reroute('please login into an account to view accounts page', '../login/ ');
}
$user = fetch_cookie('user');
?>
<head>
	<?= render_header('account') ?>
</head>
<body>
	<?= render_navbar() ?>

	<main class="account">
		<section class="update-account">
			<h2 class="capitalise sub-heading">Account</h2>

			<form action="update_account_info.php" class="account-form" method="post">
				<div class="email-input">
					<!-- displays an email input with a cached value pre-loaded if available -->
					<label for="email" class="label">email</label>
					<input type="email" name="email" id="email" required value="<?= fetch_cookie(name: 'tmp_email', unset_value: true) ?? $user['email'] ?>">
				</div>
				
				<div class="name-input">
					<!-- displays a username input and pre-loads a value if one has been cached -->
					<label for="username" class="label">name</label>
					<input type="username" name="username" id="username" required value="<?= fetch_cookie(name: 'tmp_username', unset_value: true) ?? $user['username'] ?>">
				</div>
				
				<div class="password-input">
					<!-- shows an input area for the password and holds a cached value if one is available -->
					<label for="password" class="label">password</label>
					<input type="password" name="password" id="password" required value="<?= fetch_cookie(name: 'tmp_password', unset_value: true) ?? $user['password'] ?>">
				</div>
				
				<div class="password-conf-input">
					<!-- takes the user confirmation for their email in case they have a typo in their password -->
					<label for="password-conf" class="label">confirm password</label>
					<input type="password" name="password-conf" id="password-conf" required value="<?= fetch_cookie(name: 'tmp_password_conf', unset_value: true) ?? $user['password'] ?>">
				</div>
				
				<button type="button" class="secondary button" id="toggle-password-button">toggle password</button>
				
				<div class="img-url-input">
					<!-- takes an optional input for the user if they want a profile picture -->
					<label for="img-url">profile picture</label>
					<input type="file" name="img-url" id="img-url" value="upload" placeholder="../assets/images/placeholder-profile.jpg" value="<?= fetch_cookie(name: 'tmp_img_url', unset_value: true) ?? $user['img_url'] ?>">
				</div>

				<!-- displays an error message if one is applicable -->
				<?php
				// checks there is an error message to give
				if (isset($_COOKIE['tmp_error_message'])): ?>
					<p class="feedback-text small error"><?= fetch_cookie(name: 'tmp_error_message', unset_value: true) ?></p>
				<?php endif; ?>
				
				<!-- account options -->
				<div class="account-options">
					<input class="primary button" type="submit" value="Update">
					<button type="button" class="secondary button" onclick="window.location.href = 'logout.php';">logout</button>
					<button type="button" class="delete button" onclick="window.location.href = 'delete_account.php';">Delete account</button>
				</div>
			</form>
		</section>
	</main>
    <?= render_footer() ?>

	<script src="../src/js/password_button.js"></script>
</body>
</html>