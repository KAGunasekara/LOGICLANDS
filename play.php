<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
$userName = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puzzle Game</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="play.css">
</head>
<body>
    
    <div class="game-container">
        <header class="game-header">
            <h1 class="game-title">LOGIC LAND</h1>
            <button class="leave-button" onclick="confirmLeave()">Leave puzzle</button>
        </header>
        <main class="game-main">
            <div class="game-board">
                <div class="timer">
                    <span id="time-remaining">30 seconds</span>
                </div>
                <div id="level-indicator" class="level-indicator">Level: 1</div>
                <div id="question-indicator" class="question-indicator"></div>
                <div id="attempts-indicator" class="attempts-indicator">Attempts: 14</div>
                <div id="question-grid" class="question-grid"></div>
                <div id="correct-answer" style="display: block; max-width: 60px; margin-top: 10px; font-size: 14px; color: lightgray;">
                </div>
                <div class="answer-section">
                    <input type="text" id="answer-input" placeholder="Enter your answer">
                    <button id="submit-answer">Submit</button>
                </div>
            </div>
            <aside class="side-section">
                <div class="current-score">
                    <h2>Your Current Score</h2>
                    <span id="current-score">0</span>
                </div>
            </aside>
        </main>
    </div>

    <script>
        function confirmLeave() {
            const userConfirmed = confirm("Really champ!! Are you sure you want to leave?");
            if (userConfirmed) {
                window.location.href = "game.html";
                saveScore(score);
            }
        }
        function saveScore($score) {
            const curl = new XMLHttpRequest();
            curl.open('POST', 'http://localhost:5000/leaderboards/Logic%20Land');
            curl.setRequestHeader('Content-Type', 'application/json');

            curl.onload = function() {
                if (curl.status >= 200 && curl.status < 300) {
                    console.log(curl.responseText);
                } else {
                    console.error('Request failed with status:', curl.status, curl.statusText);
                }
            };

            curl.onerror = function($userName) {
                console.error('Network error occurred.');
            };

            const data = {
                "player": "<?php echo $_SESSION['username'];?>",
                "score": score
            };
            
            curl.send(JSON.stringify(data));
}
</script>

    <script src="play.js"></script>
</body>
</html>
