<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" ){
    
    $city = $_POST["city"];
    $city = str_replace(" "," ",$city);  
    $geo_url = "https://geocoding-api.open-meteo.com/v1/search?name=" . urlencode($city) . "&count=1";  // Added urlencode
    $geo_response = file_get_contents( $geo_url );
    $geo_data = json_decode( $geo_response, true );

    if ($city != ""){
        if (isset($geo_data['results'][0])) {
            $lat = $geo_data["results"][0]["latitude"];
            $long = $geo_data["results"][0]["longitude"];
            
            
            $url = "https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$long&current=temperature_2m,weather_code";

            
            $response = file_get_contents($url);

            
            $data = json_decode($response, true);

            
            $temp = $data['current']['temperature_2m'];
            $code = $data['current']['weather_code'];
            $weather = "";
            if ($code == 0) {
               $weather = "klar himmel";
            } else if (in_array($code,[1,2,3] ) ) {
               $weather = "molnigt";
            } else if (in_array($code,[45,48])) {
                $weather = "dimigt";
            }
            else if (in_array($code,[51,53,55,61,63,65])) {
                $weather = "regn";
            } else if (in_array($code,[80,81,82])){
                $weather = "regn skurar"; 
            } else if (in_array($code,[85,86,71,73,75,77])){
                $weather = "snöigt";
            } else if (in_array($code,[95,96,99])){
                $weather = "storm!";
            }
            
            $cityFound = true;
        } else {
            if ($city != ""){  
            echo "<p>staden hittades ej</p>";
            $cityFound = false;
              }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Weather Forecast</title>
    <link rel="shortcut icon" type="image/x-icon" href="img.png" />
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Cherry Bomb One" rel="stylesheet">
    
</head>

<body>
    <div class="weather-box">
        <h1 class="weather-title">Weather Forecast</h1>

        <form method="POST">
            <div class="weather-row">
                <input type="text" name="city" placeholder="skriv in staden du will veta vädret i">
            </div>

            <div class="weather-row">
                <button type="submit">sicka in för granskning av väder i valda staden</button>
            </div>
        </form>

        <?php
        if (isset($_POST['city']) && !empty($_POST['city']) && isset($cityFound) && $cityFound === true){
            echo "<p>tempraturen i $city är "."$temp"."℃</p>";
            echo "<p>och vädret är $weather</p>";
        }
        ?>
    </div>
</body>
</html>