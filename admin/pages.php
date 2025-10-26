<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/header.php';

// Fetch all static pages
$sql = "SELECT * FROM static_content ORDER BY title";
$result = mysqli_query($conn, $sql);
$pages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<h1 class="mt-4">Manage Static Pages</h1>

<div class="card">
    <div class="card-header">Existing Pages</div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Page Title</th>
                    <th>Last Updated</th>
                    <th>Updated By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($page['title']); ?></td>
                        <td><?php echo $page['updated_at']; ?></td>
                        <td><?php echo htmlspecialchars($page['last_updated_by']); ?></td>
                        <td>
                            <a href="edit_page.php?slug=<?php echo $page['page_slug']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>