<!--
// Pseudocode:
// If user hits easy button, get 3-5 letter long words
// medium hard etc. etc.
// Create logic to query words of required length, probably with something like:
 'if word between this and that length, store for use in game'
// Randomly select one of the words queried with the proper length and use it for the game
// Must store this word within a post or session for use between pages

// Also, do:
// If table doesn't exist already, create it with columns and insert words per difficulty
// ID starts from 1, goes up for every time player starts
-->

<?php
    include "./includes/library.php";
    $pdo = connectdb();

    $easy = $_POST['easy'] ?? 0;
    $medium = $_POST['medium'] ?? 0;
    $hard = $_POST['hard'] ?? 0;
    $difficulty = "";

    $words = array();
    
    if ($easy != 0) {
        $difficulty = "easy";

        $query = "select word from cois3430_assn1_words where difficulty = 'easy'";
        $queried = $pdo->query($query);

        $words = $queried->fetchAll();

        shuffle($words);
        $word = $words[0];
    } elseif ($medium != 0) {
        $difficulty = "medium";

        $query = "select word from cois3430_assn1_words where difficulty = 'medium'";
        $queried = $pdo->query($query);

        $words = $queried->fetchAll();
        
        shuffle($words);
        $word = $words[0];
    } elseif ($hard != 0) {
        $difficulty = "hard";

        $query = "select word from cois3430_assn1_words where difficulty = 'hard'";
        $queried = $pdo->query($query);

        $words = $queried->fetchAll();
        
        shuffle($words);
        $word = $words[0];
    }

    if (isset($word) && !isset($_SESSION['word'])) {
        session_start();
        $_SESSION['word'] = $word;
        $_SESSION['blanks'] = str_split($word['word']);
        for ($i = 0; $i < sizeof($_SESSION['blanks']); $i++) {
            $_SESSION['blanks'][$i] = '_';
        }
        $_SESSION['difficulty'] = $difficulty;
        $_SESSION['tries'] = 6;
        $_SESSION['guessed'] = array();
        $_SESSION['count'] = 0;
        $_SESSION['score'] = 0;
        header('Location: play.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>COIS 3430 Assignment 1 Difficulty</title>
    <link rel="stylesheet" href="./styles/main.css">
</head>

<body>
    <header>
        <h2>Please select difficulty</h2>
    </header>
    <nav>
        
    </nav>
    <main>
        <div id="difficultySelection">
            <form method="post" novalidate>
                <button id="easy" type="easy" name="easy">Easy</button>
                <button id="medium" type="medium" name="medium">Medium</button>
                <button id="hard" type="hard" name="hard">Hard</button>
            </form>
        </div>
    </main>
    <footer>

    </footer>
</body>

</html>