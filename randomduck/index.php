<?php
$url = "https://random-d.uk/api/random";
$response = file_get_contents($url);
$response = json_decode($response, true);
$response = $response["url"];
?>
<head>
    <title>random duck generator</title>
    <link rel="stylesheet" href="style.css">
     <link href="https://fonts.googleapis.com/css?family=Cherry Bomb One" rel="stylesheet">
    
</head>
<body>
    <div class="box">
        <p style="title">Det här är en slupad bild på en anka</p>
<?php
    echo "<img src=\"$response\" alt=\"Sample Image\"";
            ?>
            </div>
        </body>