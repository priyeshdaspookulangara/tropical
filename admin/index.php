<?php
session_start();

if (!isset($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_user']); ?>!</h2>
        <p>This is the admin dashboard. You can manage the website content from here.</p>

        <div class="list-group">
            <a href="categories.php" class="list-group-item list-group-item-action">Manage Categories</a>
            <a href="selling_types.php" class="list-group-item list-group-item-action">Manage Selling Types</a>
            <a href="fruits.php" class="list-group-item list-group-item-action">Manage Fruits</a>
            <a href="inquiries.php" class="list-group-item list-group-item-action">View Inquiries</a>
            <a href="pages.php" class="list-group-item list-group-item-action">Manage Static Pages</a>
        </div>
    </div>
</body>
</html>