<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $required_fields = ['fruit_id', 'name', 'company_name', 'email', 'phone', 'selling_type_id', 'quantity', 'message'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            die("Please fill in all required fields.");
        }
    }

    // Sanitize all inputs
    $fruit_id = (int)$_POST['fruit_id'];
    $name = sanitize_input($conn, $_POST['name']);
    $company_name = sanitize_input($conn, $_POST['company_name']);
    $email = sanitize_input($conn, $_POST['email']);
    $phone = sanitize_input($conn, $_POST['phone']);
    $selling_type_id = (int)$_POST['selling_type_id'];
    $quantity = sanitize_input($conn, $_POST['quantity']);
    $message = sanitize_input($conn, $_POST['message']);

    // Insert into the database
    $sql = "
        INSERT INTO inquiries (fruit_id, selling_type_id, name, company_name, email, phone, quantity, message)
        VALUES ($fruit_id, $selling_type_id, '$name', '$company_name', '$email', '$phone', '$quantity', '$message')
    ";

    if (mysqli_query($conn, $sql)) {
        // Redirect back to the product page with a success message
        header("Location: product.php?id=$fruit_id&success=1");
        exit;
    } else {
        // Handle error
        die("Error: " . mysqli_error($conn));
    }
} else {
    // Redirect if not a POST request
    header('Location: index.php');
    exit;
}
?>