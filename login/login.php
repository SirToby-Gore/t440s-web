<?php

require_once '../src/php/requirements.php';

function main(): void {
	// sets the email and password to temp cookies
	set_cookie(name: 'tmp_email', value: $_POST['email']);
	set_cookie(name: 'tmp_password', value: $_POST['password']);

	// checks an email has been given
	if (!isset($_POST['email'])) {
		error_and_reroute(
			error_message: 'no email provided',
		);
	}

	// checks if a password has been given
	if (!isset($_POST['password'])) {
		error_and_reroute(
			error_message: 'no password provided',
		);
	}

	$connection = get_database_connection();

	$query = "SELECT * FROM users WHERE email=\"" . $connection->escape_string($_POST['email']) ."\" and password=\"". $connection->escape_string(hash_password(password: $_POST['password'])) . "\";";

	$result = $connection->query($query);

	// if details are incorrect
	if ($result->num_rows == 0) {
		$query = "SELECT * FROM users WHERE email=\"" . $connection->escape_string($_POST["email"]) ."\";";
		$result = $connection->query(query: $query);

		// if the email is correct but not password
		if ($result->num_rows == 0) {
			error_and_reroute(
			     error_message: 'email not in use'
			);
		}
		// else
		error_and_reroute(
			error_message: 'incorrect password'
		);
	}

	$result = $result->fetch_assoc();

	// inits the user assoc
	$user = [];

	$user['id'] = $result['ID'];
	$user['email'] = $result['email'];
	$user['username'] = $result['username'];
	$user['password'] = $_POST['password'];
	$user['hashed_password'] = $result['password'];
	$user['img_url'] = $result['img_url'];
	$user['created_at'] = $result['created_at'];
	
	// sets the user to a cookie
	set_cookie(name: 'user', value: $user);

	// sends the user to home
	header('location: ../home/');
}
main();
