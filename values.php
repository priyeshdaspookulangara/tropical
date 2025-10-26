<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// Fetch the page content
$sql = "SELECT * FROM static_content WHERE page_slug = 'values'";
$result = mysqli_query($conn, $sql);
$page = mysqli_fetch_assoc($result);

if (!$page) {
    // Or show a 404 page
    $page = [
        'title' => 'Our Values',
        'content_body' => '<p>Content not found.</p>',
    ];
}

$page_title = $page['title'];

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="hero-content">
        <h1><?php echo htmlspecialchars($page['title']); ?></h1>
        <p>The principles that guide everything we do</p>
    </div>
</section>

<nav class="breadcrumb-nav">
    <div class="breadcrumb-content">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($page['title']); ?></li>
        </ol>
    </div>
</nav>

<div class="tab-content active">
    <?php echo $page['content_body']; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
