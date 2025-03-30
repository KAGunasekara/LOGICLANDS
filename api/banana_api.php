<?php
// Allow CORS for cross-origin access 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Banana API URL
$api_url = "http://marcconrad.com/uob/banana/api.php";

// Fetch data from the Banana API
$response = @file_get_contents($api_url);

// Check for errors or invalid responses
if ($response === FALSE) {
    // Log error for debugging
    error_log("Error: Unable to fetch data from Banana API");
    echo json_encode(["error" => "Failed to retrieve data from the Banana API"]);
    exit;
}

// Validate if the response is a valid JSON
$json_data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("Error: Invalid JSON received from Banana API");
    echo json_encode(["error" => "Invalid JSON received from the Banana API"]);
    exit;
}

// Return valid JSON response to the client
echo $response;
?>
