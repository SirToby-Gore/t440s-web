<?php

require_once '../src/php/requirements.php';

function main(): void {
        // sets temporary cookies to cache the user's inputs
        set_cookie('tmp_email', $_POST['email']);
        set_cookie('tmp_password', $_POST['password']);
        set_cookie('tmp_password_conf', $_POST['password-conf']);
        set_cookie('tmp_username', $_POST['username']);

        // checks the user has given an email
        if (!isset($_POST['email'])) {
                error_and_reroute('email not given');
        }
        
        // checks the user has given a name
        if (!isset($_POST['username'])) {
                error_and_reroute('name not given');
        }
        
        // checks the user has given a password
        if (!isset($_POST['password'])) {
                error_and_reroute('password not given');
        }

        // checks the passwords match
        if ($_POST['password'] != $_POST['password-conf']) {
                error_and_reroute('passwords do not match');
        }

        // if the user is not logged in then there is no account to update
        if (!is_user_logged_in()) {
                error_and_reroute('please log in', '../login/ ');
        }

        $connection = get_database_connection();

        $user_id = fetch_cookie('user')['id'];
        
        // updates the user with sql
        $query = "UPDATE users SET username=\"" . $connection->escape_string($_POST["username"]) ."\", email=\"". $connection->escape_string($_POST['email']) . "\", password=\"" . $connection->escape_string(hash_password(password: $_POST["password"])) . "\"";
        
        // if the user has given a new img_url then it is added to the query
        if (isset($_POST['img_url'])) {
                $query = $query . ", img_url=\"" . $connection->escape_string($_POST["img_url"]) . "\"";
        }

        $query = $query . "WHERE ID=\"" . $user_id . "\"";

        $connection->query($query); 

        // returns the user back to index
        header("location: ./");
}

main();
