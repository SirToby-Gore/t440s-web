<!DOCTYPE html>
<?php require_once '../src/php/requirements.php'; ?>
<html lang="en">
<head>
	<?= render_header(title: 'Delete booking'); ?>
</head>
<?php

function main(): void {
	// checks the user is logged in before it tries to delete bookings
	if(!is_user_logged_in()) {
		error_and_reroute(error_message: 'must be logged in to edit consolations', path: '../login/ ');
	}
	
	$user = fetch_cookie(name: 'user');
	
	// checks the user has confirmed to delete the booking
	// this will be false on their visit
	if (!isset($_GET['conf'])) {
		return;
	}

	$connection = get_database_connection();

	// makes a query to check the user's id
	$query = "SELECT * FROM users WHERE password=\"" . $connection->real_escape_string($user['hashed_password']) . "\" AND email=\"" . $connection->real_escape_string($user['email']) . "\";";

	$result = $connection->query(query: $query)->fetch_assoc();   

	// checks the user is logged in with their id
	if ($result['ID'] != $user) {
		$user['id'] = $result['ID'];
	}

	// makes a query to check that the target booking belongs to the user
	$query = "SELECT * FROM bookings WHERE ID=\"" . $connection->real_escape_string(string: $_GET['id']) . "\";";

	$result = $connection->query(query: $query)->fetch_assoc();    

	if ($result['user_id'] != $user['id']) {
		error_and_reroute(error_message: 'booking does not belong to you');
	}

	// makes a query to delete the target booking
	$query = "DELETE FROM bookings WHERE ID=\"" . $connection->real_escape_string(string: $_GET['id']) . "\";";

	
	// runs the query to delete the booking
	$connection->query(query: $query);

	// sends the user back to look at their bookings
	header('location: ./');
}

main();

?>
<!-- could not get this to work as a popup, asks here for user confirmation of deletion-->
<body>
	<?= render_navbar() ?>
	
	<main class="delete-booking">
		<h2 class="title capitalise" style="color: black">delete booking?</h2>
		<p class="sub-text" style="color: black">deleting a booking is permanent</p>
		<button type="button" onclick="window.location.href = 'delete_booking.php?id=<?= $_GET['id'] ?>&conf=1';" class="delete button">delete</button>
		<button type="button" onclick="window.location.href = './';" class="secondary button">cancel</button>
	</main>
    <?= render_footer() ?>
</body>
</html>
