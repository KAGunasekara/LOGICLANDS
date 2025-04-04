<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('php/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request (update)
    $data = json_decode(file_get_contents('php://input'), true); // Get JSON data from request body

    if (isset($data['player']) && isset($data['score']) && isset($data['rank'])) {
        $player = $data['player'];
        $score = $data['score'];
        $rank = $data['rank'];

        $sql = "UPDATE leaderboard SET score = ?, `rank` = ? WHERE player = ?"; // Use backticks for 'rank'
        //$sql = "INSERT INTO leaderboard ("rank", )"; // Use backticks for 'rank'
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("iis", $score, $rank, $player);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Score updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error updating score: ' . $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error preparing statement: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing player, score, or rank data']);
    }
} else {
    // Handle GET request (retrieve)
    $sql = "SELECT rank, player, score FROM leaderboard ORDER BY score DESC";
    $result = $conn->query($sql);

    $leaderboardData = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $leaderboardData[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($leaderboardData);
}

$conn->close();
?>