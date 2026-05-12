<?php

require_once '../src/php/requirements.php';

function main(): void {
    // if the user is not logged in then there is no user id
    if (!is_user_logged_in()) {
        error_and_reroute(error_message: 'must be logged in to save score', path: '../login');
    }

    $user = fetch_cookie(name: 'user');

    $connection = get_database_connection();

    // makes a query for inserting the user's score
    $query = "INSERT INTO carbon_footprints (tonnes, user_id) VALUES (\"" . $connection->real_escape_string(string: $_GET['score']) . "\", \"" . $connection->real_escape_string(string: $user['id']) . "\");";

    // execs the query
    $connection->query(query: $query);
    
    header('location: ../consultation/');
}

main();