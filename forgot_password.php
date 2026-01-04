<html>
    <head>
         <title>create account</title>
    <link rel="shortcut icon" type="image/x-icon" href="img.png" />
    <link href="https://fonts.googleapis.com/css?family=Cherry Bomb One" rel="stylesheet">
    <link rel="stylesheet" href="style_forgotpassword.css">
    </head>
<body>
    
<div class = "box">
<?php
$all_data = file_get_contents("all_data.json");
$all_data = json_decode($all_data, true);
 echo '<p>'; print_r($all_data); echo '</p>';
?>
    <div>
    <a type= "button"href="index.php">gå tillbaka</a>
</div>

</div>
</body>
</html>