<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" ){
    
    $city = $_POST["city"];
    $city = str_replace(" "," ",$city);  
    $geo_url = "https://geocoding-api.open-meteo.com/v1/search?name=" . urlencode($city) . "&count=1";  
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
            
            // TOMORROW'S FORECAST VARIABLES ADDED
            $url_forecast = "https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$long&daily=temperature_2m_max,temperature_2m_min,weathercode&forecast_days=2";
            $response_forecast = file_get_contents($url_forecast);
            $data_forecast = json_decode($response_forecast, true);
            $code_forecast = $data_forecast['daily']['weathercode'][1];
            $temp_max = $data_forecast['daily']['temperature_2m_max'][1];
            $temp_min = $data_forecast['daily']['temperature_2m_min'][1];
            $avg_temp = ($temp_max + $temp_min) / 2;
            
            if ($code_forecast == 0) {
                $weather_forecast = "klar himmel";
            } else if (in_array($code_forecast,[1,2,3])) {
                $weather_forecast = "molnigt";
            } else if (in_array($code_forecast,[45,48])) {
                $weather_forecast = "dimigt";
            } else if (in_array($code_forecast,[51,53,55,61,63,65])) {
                $weather_forecast = "regn";
            } else if (in_array($code_forecast,[80,81,82])){
                $weather_forecast = "regn skurar"; 
            } else if (in_array($code_forecast,[85,86,71,73,75,77])){
                $weather_forecast = "snöigt";
            } else if (in_array($code_forecast,[95,96,99])){
                $weather_forecast = "storm!";
            } else {
                $weather_forecast = "blandat väder";
            }
            // END OF ADDED VARIABLES
            
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
    <link rel="stylesheet" href="/my_web_projects/weater_app/style.css">
    <title>Weather Forecast</title>
    <link rel="shortcut icon" type="image/x-icon" href="img.png" />
    <link href="https://fonts.googleapis.com/css?family=Cherry Bomb One" rel="stylesheet">
</head>

<body>
    <div class="weather-box">
        <h1 class="weather-title">världens typ bästa väder app</h1>

        <form method="POST">
            <div class="weather-row">
                <input class="input" type="text" name="city" placeholder="ange namnet på staden du vill se vädret på">
            </div>

            <div class="weather-row">
                <button type="submit">sicka in för granskning av vädret i valda staden</button>
            </div>
        </form>

        <?php
        if (isset($_POST['city']) && !empty($_POST['city']) && isset($cityFound) && $cityFound === true){
            echo "<p>---------------------------------</p>";
            echo "<p>det nuvarande vädret är förljande:<p>";
            echo "<p>tempraturen i $city är "."$temp"."℃</p>";
            echo "<p>och det är $weather</p>";
            echo "<p>---------------------------------</p>";
            echo "<p>morgondagens väder är följande:</p>";
            echo "<p>medel tempraturen är"." $avg_temp" . "℃ </p>";
            echo "<p>och vädret kommer att vara $weather_forecast</p>";
            echo "<p>---------------------------------</p>";
        }
        ?>
    </div>
</body>
</html>