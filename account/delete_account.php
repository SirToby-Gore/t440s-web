<!DOCTYPE html>
<?php require_once '../src/php/requirements.php'; ?>
<html lang="en">
<head>
	<?= render_header(title: 'Account'); ?>
</head>
<?php

function main(): void {
	// checks if the user has already pressed to delete their account and wont be true when it is there first visit
	if (!isset($_GET['conf'])) {
		return;
	}

	// if the user is not logged in then there is no account to delete
	if (!is_user_logged_in()) {
		error_and_reroute(error_message: 'must be logged in to delete your account', path: '../login/ ');
	}

	$connection = get_database_connection();

	$user = fetch_cookie(name: 'user');

	// checks the user is who they say they are
	$query = "SELECT ID FROM users WHERE email=\"" . $user['email'] . "\" and password=\"" . $user['hashed_password'] . "\";";

	$user_id = $connection->query(query: $query)->fetch_assoc()['ID'];

	$query = "DELETE FROM users WHERE ID=\"" . $user_id . "\";";

	$connection->query($query);

	// send the user through logout
	header('location: logout.php');
}

main();

?>
<body>
	<?= render_navbar(); ?>
	
	<main class="account">
		<section class="delete-account">
			<h2 class="title">Deleting your account?</h2>
			<p class="sub-text">This is a permanent, your account can not be recovered</p>
		
			<button href="delete_account.php" class="delete button" onclick="window.location.href = 'delete_account.php?conf=1';">delete</button>
			<button type="button" class="secondary button" onclick="window.location.href = './';">cancel</button>
		</section>
	</main>
    <?= render_footer() ?>
</body>
</html>