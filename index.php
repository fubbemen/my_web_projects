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
                    <input name="password" type="password" placeholder="skriv in ditt lösenord" required>
                </div>
                <button type="submit">logga in</button>
    </form>
    <a href="forgot_password.php">glömt lösenord?</a>
    <a href="create_account.php">skapa konto?</a>
</body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = $_POST["name"];
    $password = $_POST["password"];
    $all_data = file_get_contents("all_data.json");
    $all_data = json_decode( $all_data, true );
    if (!array_key_exists($name, $all_data))
    {
        echo"<p>detta användarnamn finns ej i vårt system vill du skapa ett konto</p>";
    } else
    {
        if ($all_data[$name] != $password)
            {
                echo "<p>fel lösenord</p>";
            } else
            {
                echo "<p>du är nu inloggad</p>";
                 header("Location: my projects");
            } 
    }
    $name = "";
    $password = "";
}
?>