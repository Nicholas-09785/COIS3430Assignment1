<?php

// Declared and initialized userName and confirm as the user name and confirmation button
$userName = $_POST['userName'] ?? "";
$confirm = $_POST['confirm'] ?? 0;

// If the user has entered a user name and hits confirm, then start session and move to next page
if (strlen($userName) > 0 and $confirm != 0) {
    session_start(); // start session
 
    // If session object username isn't set, declare and initialize it as userName variable
    if (!isset($_SESSION['username'])) {
        $_SESSION['username'] = $userName;
    }

    // Move to page difficulty.php
    header('Location: difficulty.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>COIS 3430 Assignment 1 Start</title>
    <link rel="stylesheet" type="text/css" href="./styles/main.css">
</head>


<body>
    <!-- Used semantic elements to organize title and body appropriately -->
    <header>
        <div id="startTitle">
            <h1>Hangman Game</h1>
        </div>
    </header>
    <main>
        <div id="startForm">
            <form method="post" novalidate>
                <label for="userName">Enter a user name:</label>
                <input type="text" name="userName" id="userName" value="<?=$userName ?>" />
                <button id="confirm" type="confirm" name="confirm">Confirm</button>
            </form>
        </div>
    </main>
</body>

</html>