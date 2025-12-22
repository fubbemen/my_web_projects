<?php
// connect to MySQL database
$pdo = new PDO(
    "mysql:host=localhost;dbname=minstagram;charset=utf8mb4",
    "root",
    ""
);

// handle POST requests (add a comment)
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["input"])) {
    $stmt = $pdo->prepare("INSERT INTO comments (comment) VALUES (?)");
    $stmt->execute([$_POST["input"]]);
}

// fetch all comments (latest first)
$comments = $pdo->query("SELECT comment FROM comments ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>minstagrap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="POST">
        <input type="text" name="input" placeholder="comment to main chat">
        <button type="submit">=></button>
    </form>

    <div class="comments">
        <?php foreach ($comments as $row): ?>
            <p><?= htmlspecialchars($row["comment"]) ?></p>
        <?php endforeach; ?>
    </div>
</body>
</html>
