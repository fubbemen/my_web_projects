<?php
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"] ?? '';
    $password = $_POST["password"] ?? '';

    $names = json_decode(file_get_contents("users.json"), true) ?: [];
    $all_data = json_decode(file_get_contents("all_data.json"), true) ?: [];

    $nameExists = in_array($name, $names);
    $passExists = in_array($password, $all_data);

   

    if ($passExists) {
        foreach ($all_data as $user => $pass) {
            if ($pass == $password) {
                $msg .= "<p>oj då " . $user . " har redan lösenordet " . $pass . "</p>";
            }
        }
    }

    if (!$nameExists && !$passExists) {
        $names[] = $name;
        $all_data[$name] = $password;
        file_put_contents("users.json", json_encode($names));
        file_put_contents("all_data.json", json_encode($all_data));
        $msg = "<p style='color: lime;'>Account created for $name!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>create account</title>
    <link rel="shortcut icon" type="image/x-icon" href="img.png" />
    <link href="https://fonts.googleapis.com/css?family=Cherry Bomb One" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="POST">
        <div class="container"> 
            
            <div class="box">
                <input type="text" name="name" placeholder="skriv in namn/användarnamn" required>
                <div>
                    <input name="password" type="password" placeholder="skriv in ett lösenord" required>
                </div>
            </div>

            <div class="r-box">
                <button type="submit">skapa konto</button>
                <a href="index.php">gå tillbaka</a>
            </div>

            </div>

            <div class="text">
                <?php 
                    if ($msg != "") {
                        echo $msg;
                    } else {
                        echo "<p>Waiting for input...</p>";
                    }
                ?>
            </div>
        
    </form>
</body>
</html>