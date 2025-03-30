<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
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
                <div id="attempts-indicator" class="attempts-indicator">Attempts: 5</div>
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
            }
        }
        document.addEventListener("DOMContentLoaded", () => {
            // Fetch scores initially and then every 2 seconds
            fetchScores();
        });

        function fetchScores() {
            // PHP endpoint to fetch scores
            const scoresEndpoint = "fetchscores2.php";

            fetch(scoresEndpoint)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Error fetching scores: " + response.status);
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.error) {
                        console.error(data.error);
                        return; // No alert to avoid interrupting every 2 seconds
                    }

                    // Populate the scoreboard table
                    populateScoreboard(data.slice(0, 5)); // Only take the top 5 scores
                })
                .catch((error) => {
                    console.error("Error fetching leaderboard:", error);
                });
        }
        
        function populateScoreboard(scores) {
            const tableBody = document.querySelector("#scoreboard-table tbody");
            tableBody.innerHTML = ""; // Clear existing rows

            scores.forEach((score, index) => {
                const row = document.createElement("tr");
                row.id = `score-board-table-row-${index + 1}`; // Unique ID for each row
                row.innerHTML = 
                    `<td>${score.username}</td>
                    <td>${score.score}</td>`;
                tableBody.appendChild(row);
            });
        }
    </script>

    <script src="play.js"></script>
</body>
</html>
