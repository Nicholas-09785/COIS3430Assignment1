<!--
Pseudocode:
Query appropriate data under php
Access data with for loop inside tbody, where maximum size of loop must be 10
Use query, prepared unneccessary
-->

<?php
include "./includes/library.php";
$pdo = connectdb();

$queryEasy = "select username, result, word, score, play_date from cois3430_assn1_scores where difficulty = 'easy' order by play_date desc limit 10";
$queryMed = "select username, result, word, score, play_date from cois3430_assn1_scores where difficulty = 'medium' order by play_date desc limit 10";
$queryHard = "select username, result, word, score, play_date from cois3430_assn1_scores where difficulty = 'hard' order by play_date desc limit 10";

$queriedEasy = $pdo->query($queryEasy);
$queriedMed = $pdo->query($queryMed);
$queriedHard = $pdo->query($queryHard);

$restart = $_POST['tryAgain'] ?? 0;

if ($restart != 0) {
    header('Location: difficulty.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>COIS 3430 Assignment 1 Scores</title>
</head>

<body>
    <header>
        <h1>Score Table</h1>
    </header>
    <nav></nav>
    <main>
        <div id="easyMode">
            <p>Easy</p>
            <table>
                <thead>
                    <th>Username</th>
                    <th>Result</th>
                    <th>Word</th>
                    <th>Score</th>
                    <th>Date Played</th>
                </thead>
                <tbody>
                    <?php foreach ($queriedEasy as $easy) : ?>
                        <td><?= $easy['username'] ?></td>
                        <td><?= $easy['result'] ?></td>
                        <td><?= $easy['word'] ?></td>
                        <td><?= $easy['score'] ?></td>
                        <td><?= $easy['play_date'] ?></td>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div id="mediumMode">
            <p>Medium</p>
            <table>
                <thead>
                    <th>Username</th>
                    <th>Result</th>
                    <th>Word</th>
                    <th>Score</th>
                    <th>Date Played</th>
                </thead>
                <tbody>
                    <?php foreach ($queriedMed as $medium) : ?>
                        <td><?= $medium['username'] ?></td>
                        <td><?= $medium['result'] ?></td>
                        <td><?= $medium['word'] ?></td>
                        <td><?= $medium['score'] ?></td>
                        <td><?= $medium['play_date'] ?></td>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div id="hardMode">
            <p>Hard</p>
            <table>
                <thead>
                    <th>Username</th>
                    <th>Result</th>
                    <th>Word</th>
                    <th>Score</th>
                    <th>Date Played</th>
                </thead>
                <tbody>
                    <?php foreach ($queriedHard as $hard) : ?>
                        <td><?= $hard['username'] ?></td>
                        <td><?= $hard['result'] ?></td>
                        <td><?= $hard['word'] ?></td>
                        <td><?= $hard['score'] ?></td>
                        <td><?= $hard['play_date'] ?></td>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        <form method="post" novalidate>
            <button id="tryAgain" type="tryAgain" name="tryAgain">Try again</button>
        </form>
    </footer>
</body>

</html>