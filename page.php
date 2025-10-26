<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

if (!isset($_GET['slug'])) {
    header('Location: index.php');
    exit;
}

$slug = sanitize_input($conn, $_GET['slug']);

// Fetch the page content
$sql = "SELECT * FROM static_content WHERE page_slug = '$slug'";
$result = mysqli_query($conn, $sql);
$page = mysqli_fetch_assoc($result);

if (!$page) {
    // Or show a 404 page
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page['title']); ?> - B2B Fruit Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <header class="bg-light p-3">
        <div class="container">
            <a href="index.php" class="btn btn-secondary">Back to Products</a>
        </div>
    </header>

    <main class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h1><?php echo htmlspecialchars($page['title']); ?></h1>
            </div>
            <div class="card-body">
                <?php echo $page['content_body']; // Outputting raw HTML from the WYSIWYG editor ?>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white p-4 text-center mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> B2B Fruit Products. All Rights Reserved.</p>
            <nav class="nav justify-content-center">
                <a class="nav-link text-white" href="page.php?slug=about">About Us</a>
                <a class="nav-link text-white" href="page.php?slug=values">Our Values</a>
                <a class="nav-link text-white" href="page.php?slug=contact">Contact Us</a>
            </nav>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>