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

$page_title = $page['title'];
require_once __DIR__ . '/includes/header.php';
?>

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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
