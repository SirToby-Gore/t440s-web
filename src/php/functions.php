<?php

function hash_password($password): string {
	return hash(algo: 'sha256', data: $password);
}

function get_database_connection(): mysqli | bool {
	$ini_data = parse_ini_file(filename: 'config.ini');
	
	if(!$ini_data) {
		$ini_data = parse_ini_file(filename: '../php/config.ini');
	}

	if (!$ini_data) {
		$ini_data = parse_ini_file(filename: '../src/php/config.ini');
	}

	if (!$ini_data) {
		die('<br>could not read INI file<br>');
	}
	
	return mysqli_connect(
		hostname: $ini_data['hostname'],
		username: $ini_data['username'],
		password: $ini_data['password'],
		database: $ini_data['database'],
	);
}

function error_and_reroute(string $error_message, string $path = './', bool $debug = false): void {
	if($debug) {
		echo $error_message . '<br>';
		return;
	} 
	set_cookie(name: 'tmp_error_message', value: $error_message);
	header(header: 'location: ' . $path);
	exit;
}

function fetch_cookie(string $name, bool $unset_value = false, mixed $default = null): mixed {
	$tmp = $_COOKIE[$name] ?? json_encode(value: $default);

	if ($unset_value == true) {
	     set_cookie(name: $name, value: $tmp, expire: time() - 1, path: '/');
	}

	return json_decode(json: $tmp, associative: true);
}

function set_cookie(string $name, mixed $value, int $expire = 0, string $path = '/'): void {
	setcookie(
		$name,
		json_encode(value: $value),
		$expire,
		$path,
	);
}

function delete_cookie(string $name): void {
	set_cookie(name: $name, value: null, expire: time() -1);
}

function is_user_logged_in(): bool {
	$user = fetch_cookie(name: 'user');
	if (!$user) {
		return false;
	}

	$connection = get_database_connection();

	$query = "SELECT * from users WHERE email=\"" . $connection->escape_string($user['email']) ."\" and password=\"" . $connection->escape_string($user['hashed_password']) . "\";";

	return $connection->query($query)->num_rows == 1;
}

