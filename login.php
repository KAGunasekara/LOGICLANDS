<?php
session_start(); 
include('php/db.php'); // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if form fields are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // Prepare SQL query to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password hash
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                // Redirect to game page
                header("Location: game.html");
                exit();
            } else {
                echo "<script>alert('Incorrect password!'); window.location.href='login.html';</script>";
            }
        } else {
            echo "<script>alert('User not found!'); window.location.href='login.html';</script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all fields!'); window.location.href='login.html';</script>";
    }
}

// Close the database connection
$conn->close();
?>
