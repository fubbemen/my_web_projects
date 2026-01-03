<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" ){
    
    $city = $_POST["city"];
    $country = $_POST["country"];
    $country = str_replace(" ","", $country);
    $city = str_replace(" "," ",$city);  
    $geo_url = "https://geocoding-api.open-meteo.com/v1/search?name=" . urlencode($city) . "&format=json&countryCode=" . urlencode($country);
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
            echo "<p>Staden hittades ej, stavade du rätt?</p>";
            $cityFound = false;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Weather Forecast</title>
    <link rel="shortcut icon" type="image/x-icon" href="img.png" />
    <link href="https://fonts.googleapis.com/css?family=Cherry Bomb One" rel="stylesheet">
</head>

<body>
    <div class="weather-box">
        <h1 class="weather-title">världens typ bästa väder app</h1>

        <form method="POST">
            <div class="weather-row">
                <input type="text" name="country" placeholder="Landskod (ex. CN):" list="countryCodes">
<datalist id="countryCodes">
    <option value="AF">Afghanistan</option>
    <option value="AX">Åland</option>
    <option value="AL">Albanien</option>
    <option value="DZ">Algeriet</option>
    <option value="AS">Amerikanska Samoa</option>
    <option value="VI">Amerikanska Jungfruöarna</option>
    <option value="AD">Andorra</option>
    <option value="AO">Angola</option>
    <option value="AI">Anguilla</option>
    <option value="AQ">Antarktis</option>
    <option value="AG">Antigua och Barbuda</option>
    <option value="AR">Argentina</option>
    <option value="AM">Armenien</option>
    <option value="AW">Aruba</option>
    <option value="AU">Australien</option>
    <option value="AZ">Azerbajdzjan</option>
    <option value="BS">Bahamas</option>
    <option value="BH">Bahrain</option>
    <option value="BD">Bangladesh</option>
    <option value="BB">Barbados</option>
    <option value="BE">Belgien</option>
    <option value="BZ">Belize</option>
    <option value="BJ">Benin</option>
    <option value="BM">Bermuda</option>
    <option value="BT">Bhutan</option>
    <option value="BO">Bolivia</option>
    <option value="BQ">Bonaire, Sint Eustatius och Saba</option>
    <option value="BA">Bosnien och Hercegovina</option>
    <option value="BW">Botswana</option>
    <option value="BV">Bouvetön</option>
    <option value="BR">Brasilien</option>
    <option value="IO">Brittiska Indiska oceanöarna</option>
    <option value="VG">Brittiska Jungfruöarna</option>
    <option value="BN">Brunei</option>
    <option value="BG">Bulgarien</option>
    <option value="BF">Burkina Faso</option>
    <option value="BI">Burundi</option>
    <option value="KY">Caymanöarna</option>
    <option value="CF">Centralafrikanska republiken</option>
    <option value="CL">Chile</option>
    <option value="CO">Colombia</option>
    <option value="CK">Cooköarna</option>
    <option value="CR">Costa Rica</option>
    <option value="CW">Curaçao</option>
    <option value="CY">Cypern</option>
    <option value="DK">Danmark</option>
    <option value="DJ">Djibouti</option>
    <option value="DM">Dominica</option>
    <option value="DO">Dominikanska republiken</option>
    <option value="EC">Ecuador</option>
    <option value="EG">Egypten</option>
    <option value="GQ">Ekvatorialguinea</option>
    <option value="SV">El Salvador</option>
    <option value="CI">Elfenbenskusten</option>
    <option value="ER">Eritrea</option>
    <option value="EE">Estland</option>
    <option value="ET">Etiopien</option>
    <option value="FK">Falklandsöarna</option>
    <option value="FJ">Fiji</option>
    <option value="PH">Filippinerna</option>
    <option value="FI">Finland</option>
    <option value="FR">Frankrike</option>
    <option value="GF">Franska Guyana</option>
    <option value="PF">Franska Polynesien</option>
    <option value="TF">Franska sydterritorierna</option>
    <option value="FO">Färöarna</option>
    <option value="AE">Förenade arabemiraten</option>
    <option value="GA">Gabon</option>
    <option value="GM">Gambia</option>
    <option value="GE">Georgien</option>
    <option value="GH">Ghana</option>
    <option value="GI">Gibraltar</option>
    <option value="GR">Grekland</option>
    <option value="GD">Grenada</option>
    <option value="GL">Grönland</option>
    <option value="GP">Guadeloupe</option>
    <option value="GU">Guam</option>
    <option value="GT">Guatemala</option>
    <option value="GG">Guernsey</option>
    <option value="GN">Guinea</option>
    <option value="GW">Guinea-Bissau</option>
    <option value="GY">Guyana</option>
    <option value="HT">Haiti</option>
    <option value="HM">Heard- och McDonaldöarna</option>
    <option value="HN">Honduras</option>
    <option value="HK">Hongkong</option>
    <option value="IN">Indien</option>
    <option value="ID">Indonesien</option>
    <option value="IQ">Irak</option>
    <option value="IR">Iran</option>
    <option value="IE">Irland</option>
    <option value="IS">Island</option>
    <option value="IM">Isle of Man</option>
    <option value="IL">Israel</option>
    <option value="IT">Italien</option>
    <option value="JM">Jamaica</option>
    <option value="JP">Japan</option>
    <option value="YE">Jemen</option>
    <option value="JE">Jersey</option>
    <option value="JO">Jordanien</option>
    <option value="CX">Julön</option>
    <option value="KH">Kambodja</option>
    <option value="CM">Kamerun</option>
    <option value="CA">Kanada</option>
    <option value="CV">Kap Verde</option>
    <option value="KZ">Kazakstan</option>
    <option value="KE">Kenya</option>
    <option value="CN">Kina</option>
    <option value="KG">Kirgizistan</option>
    <option value="KI">Kiribati</option>
    <option value="CC">Kokosöarna</option>
    <option value="KM">Komorerna</option>
    <option value="CG">Kongo-Brazzaville</option>
    <option value="CD">Kongo-Kinshasa</option>
    <option value="HR">Kroatien</option>
    <option value="CU">Kuba</option>
    <option value="KW">Kuwait</option>
    <option value="LA">Laos</option>
    <option value="LS">Lesotho</option>
    <option value="LV">Lettland</option>
    <option value="LB">Libanon</option>
    <option value="LR">Liberia</option>
    <option value="LY">Libyen</option>
    <option value="LI">Liechtenstein</option>
    <option value="LT">Litauen</option>
    <option value="LU">Luxemburg</option>
    <option value="MG">Madagaskar</option>
    <option value="MK">Makedonien</option>
    <option value="MW">Malawi</option>
    <option value="MV">Maldiverna</option>
    <option value="MY">Malaysia</option>
    <option value="ML">Mali</option>
    <option value="MT">Malta</option>
    <option value="MA">Marocko</option>
    <option value="MH">Marshallöarna</option>
    <option value="MQ">Martinique</option>
    <option value="MR">Mauretanien</option>
    <option value="MU">Mauritius</option>
    <option value="YT">Mayotte</option>
    <option value="MX">Mexiko</option>
    <option value="FM">Mikronesien</option>
    <option value="MZ">Moçambique</option>
    <option value="MD">Moldavien</option>
    <option value="MC">Monaco</option>
    <option value="MN">Mongoliet</option>
    <option value="ME">Montenegro</option>
    <option value="MS">Montserrat</option>
    <option value="MM">Myanmar</option>
    <option value="NA">Namibia</option>
    <option value="NR">Nauru</option>
    <option value="NL">Nederländerna</option>
    <option value="NP">Nepal</option>
    <option value="NI">Nicaragua</option>
    <option value="NE">Niger</option>
    <option value="NG">Nigeria</option>
    <option value="NU">Niue</option>
    <option value="KP">Nordkorea</option>
    <option value="MP">Nordmarianerna</option>
    <option value="NF">Norfolkön</option>
    <option value="NO">Norge</option>
    <option value="NC">Nya Kaledonien</option>
    <option value="NZ">Nya Zeeland</option>
    <option value="OM">Oman</option>
    <option value="PK">Pakistan</option>
    <option value="PW">Palau</option>
    <option value="PS">Palestina</option>
    <option value="PA">Panama</option>
    <option value="PG">Papua Nya Guinea</option>
    <option value="PY">Paraguay</option>
    <option value="PE">Peru</option>
    <option value="PN">Pitcairnöarna</option>
    <option value="PL">Polen</option>
    <option value="PT">Portugal</option>
    <option value="PR">Puerto Rico</option>
    <option value="QA">Qatar</option>
    <option value="RE">Réunion</option>
    <option value="RO">Rumänien</option>
    <option value="RW">Rwanda</option>
    <option value="RU">Ryssland</option>
    <option value="BL">Saint-Barthélemy</option>
    <option value="SH">Saint Helena</option>
    <option value="KN">Saint Kitts och Nevis</option>
    <option value="LC">Saint Lucia</option>
    <option value="MF">Saint-Martin</option>
    <option value="PM">Saint-Pierre och Miquelon</option>
    <option value="VC">Saint Vincent och Grenadinerna</option>
    <option value="SB">Salomonöarna</option>
    <option value="WS">Samoa</option>
    <option value="SM">San Marino</option>
    <option value="ST">São Tomé och Príncipe</option>
    <option value="SA">Saudiarabien</option>
    <option value="CH">Schweiz</option>
    <option value="SN">Senegal</option>
    <option value="RS">Serbien</option>
    <option value="SC">Seychellerna</option>
    <option value="SL">Sierra Leone</option>
    <option value="SG">Singapore</option>
    <option value="SX">Sint Maarten</option>
    <option value="SK">Slovakien</option>
    <option value="SI">Slovenien</option>
    <option value="SO">Somalia</option>
    <option value="ES">Spanien</option>
    <option value="LK">Sri Lanka</option>
    <option value="GB">Storbritannien</option>
    <option value="SD">Sudan</option>
    <option value="SR">Surinam</option>
    <option value="SJ">Svalbard och Jan Mayen</option>
    <option value="SE">Sverige</option>
    <option value="SZ">Swaziland</option>
    <option value="ZA">Sydafrika</option>
    <option value="GS">Sydgeorgien och Sydsandwichöarna</option>
    <option value="KR">Sydkorea</option>
    <option value="SS">Sydsudan</option>
    <option value="SY">Syrien</option>
    <option value="TJ">Tadzjikistan</option>
    <option value="TW">Taiwan</option>
    <option value="TZ">Tanzania</option>
    <option value="TD">Tchad</option>
    <option value="TH">Thailand</option>
    <option value="CZ">Tjeckien</option>
    <option value="TG">Togo</option>
    <option value="TK">Tokelau</option>
    <option value="TO">Tonga</option>
    <option value="TT">Trinidad och Tobago</option>
    <option value="TN">Tunisien</option>
    <option value="TR">Turkiet</option>
    <option value="TM">Turkmenistan</option>
    <option value="TC">Turks- och Caicosöarna</option>
    <option value="TV">Tuvalu</option>
    <option value="DE">Tyskland</option>
    <option value="UG">Uganda</option>
    <option value="UA">Ukraina</option>
    <option value="HU">Ungern</option>
    <option value="UY">Uruguay</option>
    <option value="US">USA</option>
    <option value="UM">USA:s yttre öar</option>
    <option value="UZ">Uzbekistan</option>
    <option value="VU">Vanuatu</option>
    <option value="VA">Vatikanstaten</option>
    <option value="VE">Venezuela</option>
    <option value="VN">Vietnam</option>
    <option value="BY">Vitryssland</option>
    <option value="EH">Västsahara</option>
    <option value="WF">Wallis- och Futunaöarna</option>
    <option value="ZM">Zambia</option>
    <option value="ZW">Zimbabwe</option>
    <option value="TL">Östtimor</option>
    <option value="AT">Österrike</option>
</datalist>
                
                <input type="text" name="city" placeholder="Stadsnamn:">
            </div>
            

            <div class="weather-row">
                <button type="submit">Sicka in för granskning, av vädret i valda staden</button>
            </div>
        </form>

        <?php
        if (isset($_POST['city']) && !empty($_POST['city']) && isset($cityFound) && $cityFound === true){
            echo "<p>---------------------------------</p>";
            echo "<p>Det nuvarande vädret är förljande:<p>";
            echo "<p>Tempraturen i $city är "."$temp"."℃</p>";
            echo "<p>Och det är $weather</p>";
            echo "<p>---------------------------------</p>";
            echo "<p>Morgondagens väder är följande:</p>";
            echo "<p>Medel tempraturen är"." $avg_temp" . "℃ </p>";
            echo "<p>Och vädret kommer att vara $weather_forecast</p>";
            echo "<p>---------------------------------</p>";
        }
        ?>
    </div>
</body>
</html>