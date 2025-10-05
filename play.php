<!--
Pseudocode:
System needs: 
Form for 2 user inputs, either letter or entire word guess
For entire word guess, whenever user hits check button, it will tell whether user got the word right or wrong
For letter, if letter right, display it above to let user know how close they are (when hit check button)
Has to account for both cases simultaneously

-->

<?php
session_start();
$guess = $_POST['guess'] ?? ""; // guess is used for the Full word text input below
$partGuess = $_POST['partGuess'] ?? ""; // partGuess is used for the Letter text input below

// ansButton and ansPartButton are used for the Full word and Letter buttons themselves, respectively
$ansButton = $_POST['ansButton'] ?? 0;
$ansPartButton = $_POST['ansPartButton'] ?? 0;

// restart holds the Try again button, while scores is used for the Scores button both below
$restart = $_POST['tryAgain'] ?? 0;
$scores = $_POST['scores'] ?? 0;

// word holds the session word object
$word = $_SESSION['word'] ?? "";
$letters = str_split($word['word']); // letters converts word into an array of each character

// win, lose, wrong, partWrong are used to determine if the player won the game, lost, was wrong on a full word
// guess, or wrong on a letter guess, respectively
$win = false;
$lose = false;
$wrong = false;
$partWrong = true;

// If user clicks on the word answer button, then their answer will be processed accordingly
if ($ansButton != 0 && strlen($guess) != 0) {
    if (strtolower($guess) == strtolower($word['word'])) { // if the users guess was right, them they will have won

        $win = true; // win becomes true

        // Update score to how well they did
        $_SESSION['score'] = (strlen($word['word']) * 5) + ($_SESSION['tries'] * 10) + 15;

        // For loop updates blanks
        for ($i = 0; $i < sizeof($letters); $i++) {
            $_SESSION['blanks'][$i] = $letters[$i];
            $partWrong = false;
        }
    } else { // Otherwise, remove 2 from their remaining number of trues and set wrong to true
        $_SESSION['tries'] -= 2;
        $wrong = true;
    }
} elseif ($ansPartButton != 0 && strlen($partGuess) > 0) { // If the user clicks on the letter guess button

    // For loop goes through letters and updates blanks only if user guessed a letter correctly
    for ($i = 0; $i < sizeof($letters); $i++) {
        if ($partGuess == $letters[$i]) {
            $_SESSION['blanks'][$i] = $partGuess;
            $partWrong = false;
            $_SESSION['count']++;
        }
    }

    // If the user didin't guess a letter right, remove 1 from their remaining number of trues and set wrong to true
    if ($partWrong) {
        $_SESSION['tries'] -= 1;
        $wrong = true;
    }
    
    $partWrong = true;

    // If there are no more blanks found in the blanks object of the session, set win to true and calculate score
    if (!in_array('_', $_SESSION['blanks'])) {
        $win = true;
        $_SESSION['score'] = ($_SESSION['count'] * 5) + ($_SESSION['tries'] * 10) + 15;
    }

    // Move the letter to the section that tells the user what letters they have already guessed
    array_push($_SESSION['guessed'], $partGuess);
}

// If the remaining number of tries is less than or equal to 0, set lose to true and calculate score accordingly
if ($_SESSION['tries'] <= 0) {
    $lose = true;
    $_SESSION['score'] = ($_SESSION['count'] * 5) + ($_SESSION['tries'] * 10);
}

// If the Try again button is hit, the user is taken back to the difficulty page
if ($restart != 0) {
    header('Location: difficulty.php');
    exit();
} elseif ($scores != 0) { // Otherwise, if the use hits the scores button, the user will be taken to the scores page
    header('Location: scores.php');
    exit();
}

// If a win or loss occurs in the game, perform a query to insert data into database cois3430_assn1_scores
if ($win || $lose) {
    require_once 'includes/library.php';
    $pdo = connectdb();
    
    $result = $win ? "win" : "lose"; // result stores whether or not win is true or not
    $query = "insert into cois3430_assn1_scores values (NULL, ?, ?, ?, ?, ?, NOW())"; // Query holds insert statement for appropriate data

    // stmt is variable that holds and executes prepared statement of previous query, with appropriate values as the passed data
    $stmt = $pdo->prepare($query)->execute([$_SESSION['username'], $_SESSION['difficulty'], $result, $word['word'], $_SESSION['score']]);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>COIS 3430 Assignment 1 Play</title>
    <link rel="stylesheet" href="./styles/main.css">
</head>

<body>
    <!-- Header holds following title of the page -->
    <header>
        <h1>Guess the Word</h1>
    </header>
    <nav>
        <!-- Form holds Try again and Scores buttons that only appear when the game is over
             They were put into the nav section because they are to take the user to different pages -->
        <div id="restartAndScores" <?= $win || $lose ? '' : 'hidden' ?>>
            <form method="post" novalidate>
                <button id="tryAgain" type="tryAgain" name="tryAgain">Try again</button>
                <button id="scores" type="scores" name="scores">Scores</button>
            </form>
        </div>
    </nav>
    <main>
        <p>Word: <?= $word['word'] ?> </p>

        <!-- Foreach loop showing all the blanks on the page -->
        <p>Word:
            <?php foreach ($_SESSION['blanks'] as $blank) : ?>
                <span class="blanks"> <?= $blank ?> </span>
            <?php endforeach ?>
        </p>

        <!-- Foreach loop showing guessed letters -->
        <p>Letters guesses:
            <?php foreach($_SESSION['guessed'] as $guess) : ?>
                <?=$guess?>
            <?php endforeach ?>
        </p>
        <p>Remaining guesses: <?= $_SESSION['tries'] ?></p>
        <p>Difficulty: <?= $_SESSION['difficulty'] ?> </p>
        <p>Username: <?= $_SESSION['username'] ?> </p>

        <!-- form used to show text inputs and buttons, with the top ones being for geussing the full word guess, and 
             the bottom ones being for the letter guesses --> 
        <form method="post" novalidate>
            <div id="fullWord" <?= $win || $lose ? 'hidden' : '' ?>>
                <label for="guess">Full word:</label>
                <input type="text" name="guess" id="guess" value="<?=$guess ?>" />

                <button id="ansButton" type="ansButton" name="ansButton">Full word</button>
            </div>
            <div id="partWord" <?= $win || $lose ? 'hidden' : '' ?>>
                <label for="partGuess">Part word:</label>
                <input type="text" name="partGuess" id="partGuess" value="<?=$partGuess ?>" maxlength=1 />

                <button id="ansPartButton" type="ansPartButton" name="ansPartButton">Letter</button>
            </div>
        </form>

        <!-- Following shows the message if user won or lost -->
        <div id="win" <?= $win ? '' : 'hidden' ?>>
            <p>You won!</p>
            <p>Here is the score: <?= $_SESSION['score'] ?></p>
        </div>
        <div id="lose" <?= $lose ? '' : 'hidden' ?>>
            <p>You lost</p>
        </div>
        <div id="wrong" <?= $wrong ? '' : 'hidden' ?>>
            <p>Wrong, number of tries remaining: <?=$_SESSION['tries']?></p>
        </div>

    </main>
    <footer>

    </footer>
</body>

</html>