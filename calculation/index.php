<!DOCTYPE html>
<?php require_once '../src/php/requirements.php'; ?>
<html lang="en">
<head>
    <?= render_header('calculator'); ?>
</head>
<body>
    <?= render_navbar(); ?>

    <?php

    $connection = get_database_connection();

    // gets the average score of tonnes from the carbon footprints table
    $query = "SELECT AVG(tonnes) FROM carbon_footprints";

    // rounds the result to an accuracy of 2 decimal places
    // if the key is invalid or the there are no entries in the table the default of 8.2 is used (the uk average)
    $result = round(num: $connection->query(query: $query)->fetch_assoc()['AVG(tonnes)'] ?? 8.2, precision: 2);

    ?>
    <!-- sets the average as the inner text of this element as there have been issues with cookies -->
    <p id="avg-cbn-ftp" hidden><?= $result ?></p>

    <main class="calculation">
        <section class="calculation">
            <h2 class="capitalise sub-heading">calculator</h2>
            <!-- this where the calculator sits -->
        </section>
    </main>
    <?= render_footer() ?>

    <script src="../src/js/calculator.js"></script>
</body>
</html>