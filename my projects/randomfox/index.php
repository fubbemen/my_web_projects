<?php
$url = "https://randomfox.ca/floof/";
$response = file_get_contents($url);
$response = json_decode($response, true);
$response = $response["image"];
?>
<!doctype html>
<head>
    <title>slumpad räv generator</title>
    <link rel="stylesheet" href="style.css">
     <link href="https://fonts.googleapis.com/css?family=Cherry Bomb One" rel="stylesheet">
</head>
<body>
    <div class="box">
        <p style="title">Det här är en slumpad bild på en Räv/Rävar</p>
        <?php
        echo "<img src=\"$response\" alt=\"sample image\">"
        ?>
        </div>
</body>