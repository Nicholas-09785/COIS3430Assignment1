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

// Declare and initialize easy, medium, and hard as their respective buttons
$easy = $_POST['easy'] ?? 0;
$medium = $_POST['medium'] ?? 0;
$hard = $_POST['hard'] ?? 0;
$difficulty = ""; // Declare and initialize difficulty as empty string for use later

$words = array(); // Declare and initialize words as empty array for use later

// If user presses easy button
if ($easy != 0) {
    $difficulty = "easy"; // Difficulty is easy

    // word holds return value of function QueryData, where difficulty is passed for it 
    $word = QueryData($difficulty);

    // medium and hard buttons perform similarly, but use medium and hard values for the variable difficulty
} elseif ($medium != 0) {
    $difficulty = "medium";
    $word = QueryData($difficulty);    
} elseif ($hard != 0) {
    $difficulty = "hard";
    $words = QueryData($difficulty);
}

// If word is not null and session objects word, blanks, difficulty, tries, guessed, count, and store don't exists, 
// start session and create them
if (isset($word) && !(isset($_SESSION['word']) && isset($_SESSION['blanks']) && isset($_SESSION['difficulty']) && isset($_SESSION['tries']) && isset($_SESSION['guessed']) && isset($_SESSION['count']) && isset($_SESSION['store']))) {
    session_start();
    $_SESSION['word'] = $word; // word object holds the word variable
    $_SESSION['blanks'] = str_split($word['word']); // Declare and initialize blanks object as the string split of the word
    
    // For loop goes through blanks and initializes each index as _ to be used in hangman game
    for ($i = 0; $i < sizeof($_SESSION['blanks']); $i++) {
        $_SESSION['blanks'][$i] = '_';
    }
    $_SESSION['difficulty'] = $difficulty; // difficulty object holds difficulty variable
    $_SESSION['tries'] = 6; // tries object as 6 indicates that for any word, the user only has 6 tries
    $_SESSION['guessed'] = array(); // guessed holds empty array, to be used in next page

    // count, store hold 0 and are to be incremented later
    $_SESSION['count'] = 0;
    $_SESSION['score'] = 0;

    // Switch pages
    header('Location: play.php');
    exit();
}

// Function QueryData created for use when user hits difficulty buttons
function QueryData($dif) {
    include "./includes/library.php"; // Include database
    $pdo = connectdb(); // Declare and initialize pdo as value from database function
    
    // Create query variable to store query to be used for retrieving words from sql where difficulty is parameter dif
    $query = "select word from cois3430_assn1_words where difficulty = '" . $dif . "'";
    $queried = $pdo->query($query); // queried is return value of the previous query

    $words = $queried->fetchAll(); // words now holds the entire fetch from queried

    shuffle($words); // Randomly shuffle words array
    $word = $words[0]; // Choose the random word of words[0] and store it inside $word
    
    return $word; // return word
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
    <main>
        <div id="difficultySelection">
            <!-- Form used to select difficulties -->
            <form method="post" novalidate>
                <button id="easy" type="easy" name="easy">Easy</button>
                <button id="medium" type="medium" name="medium">Medium</button>
                <button id="hard" type="hard" name="hard">Hard</button>
            </form>
        </div>
    </main>
</body>

</html>