

<?php

$userName = $_POST['userName'] ?? "";
$confirm = $_POST['confirm'] ?? 0;

if (strlen($userName) > 0 and $confirm != 0) {
    session_start();
 
    // how you will declare username into a session:
    if (!isset($_SESSION['username'])) {
        $_SESSION['username'] = $userName;
    }

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
    <header>
        <div id="startTitle">
            <h1>Hangman Game</h1>
        </div>
    </header>
    <nav></nav>
    <main>
        <div id="startForm">
            <form method="post" novalidate>
                <label for="userName">Enter a user name:</label>
                <input type="text" name="userName" id="userName" value="<?=$userName ?>" />
                <button id="confirm" type="confirm" name="confirm">Confirm</button>
            </form>
        </div>
    </main>
    <footer>

    </footer>
</body>

</html>