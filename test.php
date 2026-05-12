<?php

$name = "David";

setcookie('name', $name);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test</title>
</head>
<body>
    <h1>PHP: <?= $_COOKIE['name'] ?></h1>
    <h1 id="js"></h1>
    <input type="text" id="input">

    <script>
        const jsH1 = document.getElementById('js');
        jsH1.innerText = 'JS: '.concat(
            decodeURI(
                document.cookie.match(
                    new RegExp(
                        'name=([^;]+)'
                    )
                )[1]
            )
        );

        const input = document.getElementById('input');
    </script>
</body>
</html>