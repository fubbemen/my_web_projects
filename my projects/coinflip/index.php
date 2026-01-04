<!doctype html>
<head>
    <title>heads or harrysson</title>
    <link rel="stylesheet" href="style.css">
         <link href="https://fonts.googleapis.com/css?family=Cherry Bomb One" rel="stylesheet">

</head>
<body>
    

  <form method="POST"> 
    <div class="box">
        <div>
    <button type="submit" name = "submit">flip/submit bet</button>
    </div>
    <label>harrysson winning? yes or no</label>
    <div>
    <select class = "inputs "name="bet" size = 2>
        <option value="yes">yes</option>
        <option value="no">no</option>
    </select>
    </div>
<?php
$pred = $_POST["bet"];
if ($pred == "yes")
{
    $harrysson_pred = true;
} else
{
    $harrysson_pred = false;
}


$submit = $_POST["submit"];
if ($submit) 
    
$rand = 0;
$rand = rand(0,100);
if (($rand % 2) == 0) {
    $heads = true;
} else if ($rand%2 != 0)
{
    $heads = false;
}

    

if ($heads == true) {
    $harrysson = false;
    echo"<div class = "."text".">";
    echo "<img src=\"head.jpg\" alt=\"heads\">";
    echo"<p>Head!!!!!!!!</p>";
    echo"</div>";
    if ($harrysson == $harrysson_pred)
        {
            echo "<p>Congrats on winning</p>";
        } else 
        {
            echo "<p>Sorry for your loss you now owe us 3 riksdaler</p>";
        }
} else {
    $harrysson = true;
    echo"<div class = "."text".">";
    echo "<img src=\"harrysson.jpg\" alt=\"tails\">";
    
    echo "<p>Harrysson!!!!!!!!</p>";
    echo"</div>";
if ($harrysson == $harrysson_pred)
        {
            echo "<p>Congrats on winning</p>";
        } else 
        {
            echo "<p>Sorry for your loss</p>";
        }
}

?>
</div>
</form> 
</body>
