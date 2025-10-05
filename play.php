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
$guess = $_POST['guess'] ?? "";
$partGuess = $_POST['partGuess'] ?? "";
$ansButton = $_POST['ansButton'] ?? 0;
$ansPartButton = $_POST['ansPartButton'] ?? 0;
$restart = $_POST['tryAgain'] ?? 0;
$scores = $_POST['scores'] ?? 0;
$word = $_SESSION['word'] ?? "";
$letters = str_split($word['word']);
$win = false;
$lose = false;
$wrong = false;
$partWrong = true;
$changeTry = false;
$numTries = $_SESSION['tries'];

if ($ansButton != 0 && strlen($guess) != 0) {
    if (strtolower($guess) == strtolower($word['word'])) {
        $win = true;
        $_SESSION['score'] = (strlen($word['word']) * 5) + ($_SESSION['tries'] * 10) + 15;
        for ($i = 0; $i < sizeof($letters); $i++) {
            $_SESSION['blanks'][$i] = $letters[$i];
            $partWrong = false;
        }
    } else {
        $_SESSION['tries'] -= 2;
        $wrong = true;
    }
} elseif ($ansPartButton != 0 && strlen($partGuess) > 0) {
    for ($i = 0; $i < sizeof($letters); $i++) {
        if ($partGuess == $letters[$i]) {
            $_SESSION['blanks'][$i] = $partGuess;
            $partWrong = false;
            $_SESSION['count']++;
        }
    }

    if ($partWrong) {
        $_SESSION['tries'] -= 1;
        $wrong = true;
    }
    
    $partWrong = true;

    if (!in_array('_', $_SESSION['blanks'])) {
        $win = true;
        $_SESSION['score'] = ($_SESSION['count'] * 5) + ($_SESSION['tries'] * 10) + 15;
    }

    array_push($_SESSION['guessed'], $partGuess);
} elseif ($_SESSION['tries'] <= 0) {
    $lose = true;
    $_SESSION['score'] = ($_SESSION['count'] * 5) + ($_SESSION['tries'] * 10);
}

if ($restart != 0) {
    header('Location: difficulty.php');
    exit();
} elseif ($scores != 0) {
    header('Location: scores.php');
    exit();
}

if ($win || $lose) {
    require_once 'includes/library.php';
    $pdo = connectdb();
    
    $result = $win ? "win" : "lose";
    $query = "insert into cois3430_assn1_scores values (NULL, ?, ?, ?, ?, ?, NOW())";

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
    <header>
        <h1>Guess the Word</h1>
    </header>
    <nav>
        <form method="post" novalidate>
            <div id="restartAndScores">
                <button id="tryAgain" type="tryAgain" name="tryAgain" <?= $win || $lose ? '' : 'hidden' ?>>Try again</button>
                <button id="scores" type="scores" name="scores" <?= $win || $lose ? '' : 'hidden' ?>>Scores</button>
            </div>
        </form>
    </nav>
    <main>
        <p>Word: <?= $word['word'] ?> </p>
        <p>Word:
            <?php foreach ($_SESSION['blanks'] as $letter) : ?>
                <span class="letters"> <?= $letter ?> </span>
            <?php endforeach ?>
        </p>
        <p>Letters guesses:
            <?php foreach($_SESSION['guessed'] as $guess) : ?>
                <?=$guess?>
            <?php endforeach ?>
        </p>
        <p>Remaining guesses: <?= $_SESSION['tries'] ?></p>
        <p>Difficulty: <?= $_SESSION['difficulty'] ?> </p>
        <p>Username: <?= $_SESSION['username'] ?> </p>
        <form method="post" novalidate>
            <div id="fullWord" <?= $win || $lose ? 'hidden' : '' ?>>
                <label for="guess">Full word:</label>
                <input type="text" name="guess" id="guess" value="<?=$guess ?>" />

                <button id="ansButton" type="ansButton" name="ansButton">Answer</button>
            </div>
            <br>
            <div id="partWord" <?= $win || $lose ? 'hidden' : '' ?>>
                <label for="partGuess">Part word:</label>
                <input type="text" name="partGuess" id="partGuess" value="<?=$partGuess ?>" maxlength=1 />

                <button id="ansPartButton" type="ansPartButton" name="ansPartButton">Answer</button>
            </div>
        </form>
        <div id="win" <?= $win ? '' : 'hidden' ?>>
            <p>You won!</p>
            <p>Here is the score: <?= $_SESSION['score'] ?></p>
        </div>
        <div id="lose" <?= $lose ? '' : 'hidden' ?>>
            <p>You lost...</p>
        </div>
        <div id="wrong" <?= $wrong ? '' : 'hidden' ?>>
            <p>Wrong, number of tries remaining: <?=$_SESSION['tries']?></p>
        </div>

    </main>
    <footer>

    </footer>
</body>

</html>