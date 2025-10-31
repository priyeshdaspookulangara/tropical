<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}

// Determine the active page to highlight the link in the sidebar
$active_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="assets/css/admin_style.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-white">Fruit B2B Admin</div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action bg-dark text-white <?php echo ($active_page == 'index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="categories.php" class="list-group-item list-group-item-action bg-dark text-white <?php echo ($active_page == 'categories.php') ? 'active' : ''; ?>">
                    <i class="fas fa-folder me-2"></i>Categories
                </a>
                <a href="selling_types.php" class="list-group-item list-group-item-action bg-dark text-white <?php echo ($active_page == 'selling_types.php') ? 'active' : ''; ?>">
                    <i class="fas fa-box me-2"></i>Selling Types
                </a>
                <a href="fruits.php" class="list-group-item list-group-item-action bg-dark text-white <?php echo ($active_page == 'fruits.php') ? 'active' : ''; ?>">
                    <i class="fas fa-apple-alt me-2"></i>Fruits
                </a>
                <a href="manage_fruit_advanced.php" class="list-group-item list-group-item-action bg-dark text-white <?php echo ($active_page == 'manage_fruit_advanced.php') ? 'active' : ''; ?>">
                    <i class="fas fa-apple-alt me-2"></i>Fruits Advanced
                </a>
                <a href="manage_flavours.php" class="list-group-item list-group-item-action bg-dark text-white <?php echo ($active_page == 'manage_flavours.php') ? 'active' : ''; ?>">
                    <i class="fas fa-seedling me-2"></i>Flavours
                </a>
                <a href="manage_colors.php" class="list-group-item list-group-item-action bg-dark text-white <?php echo ($active_page == 'manage_colors.php') ? 'active' : ''; ?>">
                    <i class="fas fa-palette me-2"></i>Colors
                </a>
                <a href="manage_suppliers.php" class="list-group-item list-group-item-action bg-dark text-white <?php echo ($active_page == 'manage_suppliers.php') ? 'active' : ''; ?>">
                    <i class="fas fa-truck me-2"></i>Suppliers
                </a>
                <a href="inquiries.php" class="list-group-item list-group-item-action bg-dark text-white <?php echo ($active_page == 'inquiries.php') ? 'active' : ''; ?>">
                    <i class="fas fa-envelope me-2"></i>Inquiries
                </a>
                <a href="pages.php" class="list-group-item list-group-item-action bg-dark text-white <?php echo in_array($active_page, ['pages.php', 'edit_page.php']) ? 'active' : ''; ?>">
                    <i class="fas fa-file-alt me-2"></i>Static Pages
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="menu-toggle"><i class="fas fa-bars"></i></button>
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo htmlspecialchars($_SESSION['admin_user']); ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="logout.php">Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid py-4">
