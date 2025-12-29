<?php

$prompt = $_POST["prompt"];

if ($prompt == "")
    {
$url = "text.pollinations.ai/$prompt";
$response = file_get_contents($url);
   }
?>
<!DOCTYPE html>
<head>
    <title>this is advanced ai</title>
</head>
<body>
    <div class="box">
        <input type="text" name="prompt" placeholder="Please enter your promt!">
        <button type="submit">Submit</button>
        <div class="text">
            <?php
            echo "<p>$response</p>"
            ?>
        </div>
    </div>
</body>