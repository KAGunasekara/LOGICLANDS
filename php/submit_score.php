<?php
session_start();
include('/database/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to submit a score!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $score = $_POST['score'];

    // Insert score into the database
    $sql = "INSERT INTO scores (user_id, score) VALUES ('$userId', '$score')";
    if ($conn->query($sql) === TRUE) {
        echo "Score submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

?>
<script>
    function submitScore(score) {
        fetch('submit_score.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `score=${score}`
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);  // Handle the response
        });
    }

    // Example of calling the submitScore function
    submitScore(500);  // Submit a score of 500 (you'd call this dynamically)
</script>
