<!DOCTYPE html>
<head>
    <title>seb skatt kalkylator</title>
        <link rel="stylesheet" href="style.css">
         <link href="https://fonts.googleapis.com/css?family=Cherry Bomb One" rel="stylesheet">
</head>
<body>
    <div class = "box">
        <form method = "POST">
    <input type="text"  name = "wage"placeholder="skriv in din lön">
    <button type = "submit">visa lön efter skatt</button>
    <div>
        <?php
        $wage = 0.0;
        $wage = $_POST["wage"];
        echo "<p></p>";
      $tax = $wage * (0.10 + 0.00005 * $wage);
      echo "<p>din skatt är "."$tax"." sikrona</p>";
      $wage_after = $wage - $tax;
      echo "<p>din lön efter skatt är "."$wage_after"." sikrona</p>";
        ?>
    </div>    
</div>
</form>
</body>