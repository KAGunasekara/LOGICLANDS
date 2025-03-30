<?php
// Example: banana_api.php (assuming it returns JSON)
header('Content-Type: application/json');

// Generate a random question and solution (replace with your actual logic)
$questions = [
    ["question" => "2 + 2 = ?", "solution" => "4"],
    ["question" => "What is the capital of France?", "solution" => "paris"],
    ["question" => "What is 10 * 5?", "solution" => "50"],
];

$randomIndex = array_rand($questions);
echo json_encode($questions[$randomIndex]);

?>