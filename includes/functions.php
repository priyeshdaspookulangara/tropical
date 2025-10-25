<?php
// Include the database connection file
require_once __DIR__ . '/../config/db.php';

/**
 * Sanitize user input to prevent SQL injection.
 *
 * @param mysqli $conn The database connection.
 * @param string $data The data to be sanitized.
 * @return string The sanitized data.
 */
function sanitize_input($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}
?>