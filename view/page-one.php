<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page One</title>
</head>

<body>
    <h1>Page One</h1>
    <?php
    session_start();
    echo "data:" . var_dump($_SESSION['data']);
    session_destroy();
    ?>
</body>

</html>