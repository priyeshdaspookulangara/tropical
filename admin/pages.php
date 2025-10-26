<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}

// Fetch all static pages
$sql = "SELECT * FROM static_content ORDER BY title";
$result = mysqli_query($conn, $sql);
$pages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Static Pages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Manage Static Pages</h2>
        <a href="index.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

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
    </div>
</body>
</html>