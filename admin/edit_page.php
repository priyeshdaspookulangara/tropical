<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['slug'])) {
    header('Location: pages.php');
    exit;
}

$slug = sanitize_input($conn, $_GET['slug']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize_input($conn, $_POST['title']);
    // Sanitize the content_body to prevent SQL injection.
    $content_body = sanitize_input($conn, $_POST['content_body']);
    $last_updated_by = $_SESSION['admin_user'];

    $sql = "UPDATE static_content SET title = '$title', content_body = '$content_body', last_updated_by = '$last_updated_by' WHERE page_slug = '$slug'";

    if (mysqli_query($conn, $sql)) {
        $success_message = "Page updated successfully!";
    } else {
        $error_message = "Error updating page: " . mysqli_error($conn);
    }
}

// Fetch the page content
$sql = "SELECT * FROM static_content WHERE page_slug = '$slug'";
$result = mysqli_query($conn, $sql);
$page = mysqli_fetch_assoc($result);

if (!$page) {
    header('Location: pages.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page: <?php echo htmlspecialchars($page['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/wysiwyg.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Page: <?php echo htmlspecialchars($page['title']); ?></h2>
        <a href="pages.php" class="btn btn-secondary mb-3">Back to Pages List</a>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form action="edit_page.php?slug=<?php echo $page['page_slug']; ?>" method="post">
                    <div class="mb-3">
                        <label for="title" class="form-label">Page Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($page['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="content_body" class="form-label">Content</label>
                        <div class="editor-container">
                            <textarea name="content_body" id="content_body" cols="30" rows="10"><?php echo htmlspecialchars($page['content_body']); ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Upload Modal -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Upload Image</h2>
            <input type="file" id="imageInput" accept="image/*">
            <button id="uploadButton" class="btn btn-primary mt-2">Upload</button>
            <p id="uploadMessage" class="mt-2 text-danger"></p>
        </div>
    </div>

    <script src="../assets/js/wysiwyg.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initWyzywigEditor('content_body');
        });
    </script>
</body>
</html>