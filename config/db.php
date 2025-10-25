<?php
// Database configuration
define('DB_HOST', 'your_database_host');
define('DB_USERNAME', 'your_database_username');
define('DB_PASSWORD', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Create a new mysqli connection
$conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the connection
if (!$conn) {
    // In a real application, you would log this error and show a user-friendly message.
    // For this example, we'll keep it simple.
    exit("Database connection failed. Please check your configuration.");
}
?>