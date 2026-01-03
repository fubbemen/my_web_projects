<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $button = $_POST["button"] ?? "";

    if ($button === "ompa lompa shakira") {
        header("Location: language.php");
        exit();
    } else {
        header("Location: weather_app.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>väder app</title>
    <link rel="shortcut icon" type="image/x-icon" href="img.png" />
    <link href="https://fonts.googleapis.com/css?family=Cherry Bomb One" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="POST">
        <p>snälla välj om du skall ha svenska eller ett annat språk</p>
        <div class="weather-box">
            <button type="submit" name="button" value="0">Ja</button>
            <div>
            <button type="submit" name="button" value="ompa lompa shakira">Nej</button>
            </div>
        </div>
    </form>
</body>
</html>
