<?php
// Database connection parameters
$host = "localhost";
$user = "root";
$password = "";
$database = "demo";

// Get user input from the forgot password form
$email = $_POST['email'];

// Create a database connection
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve user details based on the provided email
$query = "SELECT * FROM users WHERE username='$email'";
$result = $conn->query($query);

// Check if the query returned a row
if ($result->num_rows > 0) {
    // Generate a new password (you may want to use a more secure method)
    $newPassword = generateRandomPassword();

    // Update the user's password in the database
    $updateQuery = "UPDATE users SET password='$newPassword' WHERE username='$email'";
    $conn->query($updateQuery);

    // Send the new password to the user's email (you should implement this part)

    echo "Password reset successful. Check your email for the new password.";
} else {
    echo "Email not found. Please check your input.";
}

// Close the database connection
$conn->close();

function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>
