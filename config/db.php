<?php
// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'b2b_fruits');

// Create a new mysqli connection
$conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the connection
if (!$conn) {
    // In a real application, you would log this error and show a user-friendly message.
    // For this example, we'll keep it simple.
    exit("Database connection failed. Please check your configuration.");
}
?>