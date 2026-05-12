<?php

require_once '../src/php/requirements.php';

function main(): void {
	// caches the user's inputs
	set_cookie(name: 'tmp_email', value: $_POST['email']);
	set_cookie(name: 'tmp_username', value: $_POST['username']);
	set_cookie(name: 'tmp_password', value: $_POST['password']);
	set_cookie(name: 'tmp_password-conf', value: $_POST['password-conf']);

	// checks the user supplied an email
	if (!isset($_POST['email'])) {
		error_and_reroute(error_message: 'no email provided');
	}

	// checks the user has supplied a username
	if (!isset($_POST['username'])) {
		error_and_reroute(error_message: 'no username provided');
	}

	// checks the user has supplied a password
	if (!isset($_POST['password'])) {
		error_and_reroute(error_message: 'no password provided');
	}

	// checks the user has supplied a password confirmation
	if (!isset($_POST['password-conf'])) {
		error_and_reroute(error_message: 'no password confirmation provided');
	}

	// checks the user's password match
	if ($_POST['password'] != $_POST['password-conf']) {
		error_and_reroute('password do not match');
	}
	
	$connection = get_database_connection();

	if ($connection == false) {
		error_and_reroute(error_message: 'could not connect to database');
	}

	// makes a query check if the email has been taken
	$query = "SELECT * FROM users where email=\"" . $connection->escape_string($_POST["email"]) ."\";";

	// if the email is already taken
	if ($connection->query(query: $query)->num_rows > 0) {
		error_and_reroute(error_message: 'email \'' . $_POST['email'] . '\' already in use');
	}

	// checks if the user has or hasn't uploads a profile picture and makes a query based on that
	if ($_POST['img-url']) {
		$query = "INSERT INTO users (email, username, password, img_url) VALUES (\"" . $connection->escape_string($_POST["email"]) ."\", \"". $connection->escape_string($_POST["username"]) ."\", \"". $connection->escape_string(hash_password($_POST['password'])) . "\", \"" . $_POST['img-url'] . "\");";
	}
	else {
		$query = "INSERT INTO users (email, username, password) VALUES (\"" . $connection->escape_string($_POST["email"]) ."\", \"". $connection->escape_string($_POST["username"]) ."\", \"". $connection->escape_string(hash_password($_POST['password'])) . "\");";
	}

	// execs the SQL to make the user
	$connection->query(query: $query);

	// sends the user to go and login to their account
	header('location: ../login/ ');
}

main();