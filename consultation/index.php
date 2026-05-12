<!DOCTYPE html>
<html lang="en">
<?php
require '../src/php/requirements.php';

// checks the user is logged in
if (!is_user_logged_in()) {
    error_and_reroute(error_message: 'please log in to see and make consultations', path: '../login/ ');
}

$user = fetch_cookie(name: 'user');

/*
 * gets all the bookings the user has from the bookings table
 **/
function get_users_bookings(int $user_id): mixed {
    $connection = get_database_connection();

    $query = "SELECT * FROM bookings WHERE user_id=\"" . $user_id . "\";";

    return $connection->query(query: $query)->getIterator();
}
?>
<head>
    <?= render_header(title: 'Your Consolations') ?>
</head>
<body>
    <?= render_navbar() ?>

    <main class="consolations">
        <h2 class="capitalise sub-heading">consolations</h2>
        
        <section class="bookings">
            <?php
            $bookings = get_users_bookings(user_id: $user['id']);

            if (!$bookings): // checks the user has any bookings ?>
                <p class="text">No bookings found</p>
            <?php else: ?>
                <?php foreach ($bookings as $booking): // loops over the users bookings ?>
                    <div class="booking">
                        <div class="options">
                            <!-- for each sub-element gets added if the option is selected in the booking -->
                            <?php if ($booking['solar']): ?>
                                <span>solar</span>
                            <?php endif; if ($booking['smart_home']): ?>
                                <span>smart home</span>
                            <?php endif; if ($booking['ev_charger']): ?>
                                <span>ev charger</span>
                            <?php endif; ?> 
                        </div>

                        <!-- shows the date in standard formate -->
                        <p class="date"><?= implode(separator: ' / ', array: array_reverse(array: explode(separator: '-', string: $booking['date']))) ?></p>

                        <p class="location"><?= $booking['address'] ?></p>

                        <!-- makes a delete button for the booking specific to the booking -->
                        <button class="delete button" onclick="window.location.href = 'delete_booking.php?id=<?= $booking['ID'] ?>';"><img src="../src/assets/icons/delete.svg" alt="delete booking" title="delete booking"></button>
                    </div> 
                <?php endforeach; ?>
            <?php endif ?>

            <!-- makes the book a new button -->
            <button class="primary button" style="width: 20rem" onclick="window.location.href = '../book/';">Book A Consultation</button>
        </section>
    </main>
    <?= render_footer() ?>
</body>
</html> 