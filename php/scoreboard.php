<?php
include('php/db.php');

$sql = "SELECT users.username, scores.score FROM scores
        JOIN users ON users.id = scores.user_id
        ORDER BY scores.score DESC LIMIT 10";  // Fetch top 10 scores

$result = $conn->query($sql);

echo "<h1>Leaderboard</h1>";
echo "<table><tr><th>Rank</th><th>Player</th><th>Score</th></tr>";

$rank = 1;
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>$rank</td><td>{$row['username']}</td><td>{$row['score']}</td></tr>";
    $rank++;
}

echo "</table>";
?>