<?php
session_start();
include 'php/db.php'; 

ob_clean();  // Clean the output buffer to ensure no extra whitespace is sent
error_reporting(0); // Turn off error reporting for this response

// Check if the user is logged in 
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in."]);
    exit();
}

$query = "
SELECT u.username, s.score, RANK() OVER (ORDER BY s.score DESC) AS rank
FROM scores s
JOIN users u ON s.user_id = u.id
ORDER BY s.score DESC
LIMIT 5"; // Fetch top 5 scores

$result = $conn->query($query);

$scores = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $scores[] = $row;  // Adding each player's score and rank to the array
    }

    // Ensure we send a valid JSON response
    header('Content-Type: application/json');  // Set the response type into JSON
    echo json_encode($scores);  // Returning the scores as JSON
} else {
    // In case no scores are found, return an empty array or a message as JSON
    header('Content-Type: application/json');
    echo json_encode(["error" => "No scores found"]);
}

$conn->close();
?>
