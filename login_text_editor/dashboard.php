<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

echo "<p>Hello, " . htmlspecialchars($_SESSION['username']) . "!</p>";

$uploadsDir = "uploads/";
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Git functions
function gitAutoCommit($filePath, $filename) {
    $gitDir = dirname(__FILE__); // Get the root directory of the project
    
    // Add the file to git
    $addCommand = "cd " . escapeshellarg($gitDir) . " && git add " . escapeshellarg($filePath);
    exec($addCommand, $addOutput, $addReturn);
    
    // Commit with a message
    $commitMessage = "Auto-commit: Added " . $filename . " via upload at " . date('Y-m-d H:i:s');
    $commitCommand = "cd " . escapeshellarg($gitDir) . " && git commit -m " . escapeshellarg($commitMessage);
    exec($commitCommand, $commitOutput, $commitReturn);
    
    // Push to remote repository (optional - remove if you don't want automatic push)
    $pushCommand = "cd " . escapeshellarg($gitDir) . " && git push";
    exec($pushCommand, $pushOutput, $pushReturn);
    
    return [
        'added' => $addReturn === 0,
        'committed' => $commitReturn === 0,
        'pushed' => $pushReturn === 0,
        'add_output' => $addOutput,
        'commit_output' => $commitOutput,
        'push_output' => $pushOutput
    ];
}

// STEP 1: Handle file upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"])) {
    $uploadedFile = $_FILES["file"]["tmp_name"];
    $filename = basename($_FILES["file"]["name"]);
    $savePath = $uploadsDir . $filename;

    if (move_uploaded_file($uploadedFile, $savePath)) {
        echo "<p style='color:green;'>File uploaded: $filename</p>";
        
        // Auto-commit to git
        $gitResult = gitAutoCommit($savePath, $filename);
        
        if ($gitResult['committed']) {
            echo "<p style='color:green;'>✓ File auto-committed to Git repository</p>";
            
            // Show details if needed
            if (isset($_GET['debug'])) {
                echo "<pre>Git add output: " . print_r($gitResult['add_output'], true) . "</pre>";
                echo "<pre>Git commit output: " . print_r($gitResult['commit_output'], true) . "</pre>";
                if ($gitResult['pushed']) {
                    echo "<p style='color:green;'>✓ File pushed to remote repository</p>";
                    echo "<pre>Git push output: " . print_r($gitResult['push_output'], true) . "</pre>";
                }
            }
        } else {
            echo "<p style='color:orange;'>File uploaded but Git commit failed</p>";
            if (isset($_GET['debug'])) {
                echo "<pre>Git add output: " . print_r($gitResult['add_output'], true) . "</pre>";
                echo "<pre>Git commit output: " . print_r($gitResult['commit_output'], true) . "</pre>";
            }
        }
        
        // Optional: Show editable textarea (you can remove this if you don't want editing)
        $content = file_get_contents($savePath);
        echo '<form method="POST">';
        echo '<textarea name="new_content" rows="15" cols="70">' . htmlspecialchars($content) . '</textarea><br>';
        echo '<input type="hidden" name="file_path" value="' . htmlspecialchars($savePath) . '">';
        echo '<button type="submit">Save Changes</button>';
        echo '</form>';
    } else {
        echo "<p style='color:red;'>Failed to upload file!</p>";
    }
}

// STEP 2: Save edited file (optional - you can remove this if you don't want editing)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["new_content"], $_POST["file_path"])) {
    $filePath = $_POST["file_path"];
    $newContent = $_POST["new_content"];
    $filename = basename($filePath);

    if (file_put_contents($filePath, $newContent)) {
        echo "<p style='color:green;'>File saved successfully!</p>";
        
        // Auto-commit the edited file to git
        $gitResult = gitAutoCommit($filePath, $filename . " (edited)");
        
        if ($gitResult['committed']) {
            echo "<p style='color:green;'>✓ Changes auto-committed to Git repository</p>";
        }
    } else {
        echo "<p style='color:red;'>Failed to save file!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="shortcut icon" type="image/x-icon" href="php.png" />
</head>
<body>

<h1>Dashboard</h1>

<!-- Upload form -->
<h2>Upload a new text file</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file" accept=".txt" required>
    <button type="submit">Upload & Auto-Commit to Git</button>
</form>

<p><small>Files will be automatically committed to the Git repository upon upload.</small></p>

<!-- Logout button -->
<form method="POST" action="index.php" style="margin-top:20px;">
    <button type="submit" name="logout">Logout</button>
</form>

</body>
</html>