<?php
header('Content-Type: application/json');

// Basic security check - in a real app, you'd check for admin login session
session_start();
if (!isset($_SESSION['admin_user'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if (isset($_FILES['image'])) {
    $target_dir = "uploads/";
    // Create a unique file name to prevent overwriting existing files
    $file_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $file_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo json_encode(['success' => false, 'error' => 'File is not an image.']);
        exit;
    }

    // Check file size (e.g., 5MB limit)
    if ($_FILES["image"]["size"] > 5000000) {
        echo json_encode(['success' => false, 'error' => 'Sorry, your file is too large.']);
        exit;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo json_encode(['success' => false, 'error' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.']);
        exit;
    }

    // Try to upload file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Get the base URL dynamically
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $base_url = $protocol . $host;

        echo json_encode(['success' => true, 'url' => $base_url . '/' . $target_file]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Sorry, there was an error uploading your file.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No file was uploaded.']);
}
?>