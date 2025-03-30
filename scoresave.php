<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized access. Please log in to save your score."]);
    exit();
}

// Include database connection
include 'php/db.php';

try {
    // Get the JSON input from the client
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Debugging: Check if data is received
    if (!$data) {
        echo json_encode(["error" => "No data received!"]);
        exit();
    }

    // Validate input
    if (!isset($data['scores']) || !is_numeric($data['scores']) || $data['scores'] < 0) {
        echo json_encode(["error" => "Invalid score. The score must be a non-negative number."]);
        exit();
    }

    // Get user ID and score
    $user_id = $_SESSION['user_id'];
    $score = intval($data['scores']);

    // Debugging: Check session data
    if (!$user_id) {
        echo json_encode(["error" => "User session missing!"]);
        exit();
    }

    // Prepare and execute the SQL statement to insert the score
    $stmt = $conn->prepare("INSERT INTO scores (user_id, score) VALUES (?, ?)");

    // Debugging: Check if the statement was prepared successfully
    if (!$stmt) {
        echo json_encode(["error" => "SQL preparation error: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("ii", $user_id, $score);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Score saved successfully."]);
    } else {
        echo json_encode(["error" => "SQL execution error: " . $stmt->error]);
    }
} catch (Exception $e) {
    // Log the error and send a generic message to the client
    error_log("Error saving score: " . $e->getMessage());
    echo json_encode(["error" => "An error occurred while saving the score. Please try again later."]);
} finally {
    // Close the statement and the database connection
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>
