<!DOCTYPE html>
<html>
<head>
    <title>Buss</title>
    <link rel="stylesheet" href="style.css">
     <link href="https://fonts.googleapis.com/css?family=Cherry Bomb One" rel="stylesheet">
</head>
<body>
    <form action="" method="POST">
        <div class = "app_box">
            <div class="image">
        <img src="logo.png" alt="logo" style="width: 75%">
        </div>
        <div class="start">
        <label for="station_start">Select start station:</label>
        <select class = "inputs" id="station_start" name="station-start" size="4">
            <option value="falu-centrum">Falu Centrum</option>
            <option value="kupolen">Kupolen</option>
            <option value="borlänge-centrum">Borlänge Centrum</option>
            <option value="kornäs">Korsnäs</option>
        </select>

        <br><br>
    </div>
        <div class="dest">         
            <label for="station_desr">Select the destenation:</label>
        <select id="station_dest" class = "inputs" name="station-dest" size="4">
            <option value="falu-centrum">Falu Centrum</option>
            <option value="kupolen">Kupolen</option>
            <option value="borlänge-centrum">Borlänge Centrum</option>
            <option value="kornäs">Korsnäs</option>
        </select>
        <br><br>
        </div>
        <button type="submit">Submit</button>
        
    </form>

<?php
if (isset($_POST['station-start']) && isset($_POST['station-dest'])) {
    $selected = $_POST['station-start']; 
    $selected_dest = $_POST['station-dest'];
    if ($selected == $selected_dest) {
        echo "<p>Sorry, you can't have the same destination and start for your ticket.</p>";
    } else {
    echo "<p>you will now book a ticket from $selected to $selected_dest</p>";
    echo "<p>the price will be 3 riksdaler</p>"; 
    }
}
?>
</div>
</body>
</html>
