<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Tropical Fruit Pulps'; ?> - Tropical Fruit Pulps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <header>
        <nav class="header-nav">
            <a href="/" class="logo">
                <svg width="50" height="50" viewBox="0 0 50 50">
                    <circle cx="25" cy="25" r="23" fill="#3e940f"/>
                    <text x="25" y="32" font-size="20" fill="white" text-anchor="middle" font-weight="bold">TFP</text>
                </svg>
            </a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About Us</a></li>
                <li class="nav-item"><a href="values.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'values.php' ? 'active' : ''; ?>">Values</a></li>
                <li class="nav-item"><a href="products.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">Products</a></li>
                <li class="nav-item"><a href="services.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">Services</a></li>
                <li class="nav-item"><a href="about.php#contact" class="nav-link">Contact Us</a></li>
            </ul>
            <div class="cta-buttons">
                <a href="#" class="btn btn-outline">Log In</a>
                <a href="#" class="btn btn-primary">Order Now</a>
            </div>
        </nav>
    </header>
