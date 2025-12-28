<?php
$url = "https://coffee.alexflipnote.dev/random.json";
$response = file_get_contents($url);
$response = json_decode($response, true);
$response = $response["file"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>slumpad kafe generator</title>
</head>
<body>
    <div class = "box">
        <p>Det här är en slumpad bild på kaffe</p>
    <?php    
    echo "<img src=\"$response\" alt=\"Sample Image\">";
    ?>
    </div>
</body>
</html>