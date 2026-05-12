<?php

require_once '../src/php/requirements.php';

function main(): void {
    // checks the user is logged in to able make a booking in their name
    if (!is_user_logged_in()) {
        error_and_reroute(error_message: 'please log in to be able to create bookings', path: '../login');
    }
    
    // gets all of the users inputs
    $date = $_POST['date'];
    $email = $_POST['email'];
    
    $address_line_1 = $_POST['address-line-1'];
    $address_line_2 = $_POST['address-line-2'];
    $address_line_county = $_POST['address-line-county'];
    $post_code = strtolower($_POST['post-code']);
    
    // sets the values to true or false depending if they were provided
    $solar_panel = ['on' => true, 'off' => false][$_POST['solar-panel'] ?? 'off'];
    $ev_charging = ['' => true, 'off' => false][$_POST['ev-charging'] ?? 'off'];
    $smart_home = ['' => true, 'off' => false][$_POST['smart-home'] ?? 'off'];
    
    // caches all of the users inputs into cookies
    set_cookie('tmp_date', $date);
    set_cookie('tmp_email', $email);

    set_cookie('tmp_address-line-1', $address_line_1);
    set_cookie('tmp_address-line-2', $address_line_2);
    set_cookie('tmp_address-line-county', $address_line_county);
    set_cookie('tmp_post-code', $post_code);

    set_cookie('tmp_solar-panel', $solar_panel);
    set_cookie('tmp_ev-charging', $ev_charging);
    set_cookie('tmp_smart-home', $smart_home);

    // if the user has not selected a valid at least one of the checkboxes
    if (!$solar_panel && !$ev_charging && !$smart_home) {
        error_and_reroute('Please select at least one service');
    }

    // regex from gov.uk post codes
    if (!preg_match(pattern: '/([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9][A-Za-z]?))))\s?[0-9][A-Za-z]{2})/', subject: $post_code)) {
        error_and_reroute(error_message: 'invalid post code');
    }

    $user = fetch_cookie('user');   
    
    $connection = get_database_connection();
    
    // creates a query to add a booking in the user's name
    $query = "INSERT INTO bookings (user_id, date, solar, ev_charger, smart_home, address) VALUES (\"" . $connection->escape_string($user['id']) . "\", \"" . $connection->escape_string($date) . "\", ";

    // if they selected solar, then add it, otherwise set it to false
    if ($solar_panel) {
        $query .= "true, ";
    } else {
        $query .= "false, ";
    }

    // if the user selected to have EV charging set it to true, otherwise false
    if ($ev_charging) {
        $query .= "true, ";
    } else {
        $query .= "false, ";
    }

    // if the user selected to have smart home set it to true, otherwise false
    if ($smart_home) {
        $query .= "true, ";
    } else {
        $query .= "false, ";
    }

    // starts the address of the user
    $query .= "\"" . $connection->escape_string($address_line_1);

    // if the user supplied a second address line add it to the address
    if ($address_line_2) {
        $query .= ", " . $connection->escape_string($address_line_2);
    }

    // if the user supplied a county add it to the address
    if ($address_line_county) {
        $query .= ", ". $connection->escape_string($address_line_county);
    }

    // add the post code
    $query .= ", " . $connection->escape_string($post_code) . "\""; 

    // close the query
    $query .= ");";

    // add the booking
    $connection->query($query);

    // send the user to be able to see their bookings
    header("location: ../consultation");
}

main();
