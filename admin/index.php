<?php
require_once __DIR__ . '/includes/header.php';
?>

<h1 class="mt-4">Dashboard</h1>
<p class="lead">Welcome, <?php echo htmlspecialchars($_SESSION['admin_user']); ?>!</p>
<p>This is the admin dashboard. You can manage the website content from here.</p>

<div class="list-group">
    <a href="categories.php" class="list-group-item list-group-item-action">Manage Categories</a>
    <a href="selling_types.php" class="list-group-item list-group-item-action">Manage Selling Types</a>
    <a href="fruits.php" class="list-group-item list-group-item-action">Manage Fruits</a>
    <a href="inquiries.php" class="list-group-item list-group-item-action">View Inquiries</a>
    <a href="pages.php" class="list-group-item list-group-item-action">Manage Static Pages</a>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>