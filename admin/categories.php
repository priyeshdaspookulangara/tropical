<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/header.php';

// Handle form submissions for add, edit, and delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = sanitize_input($conn, $_POST['name']);
        $sql = "INSERT INTO categories (name) VALUES ('$name')";
        mysqli_query($conn, $sql);
    } elseif (isset($_POST['edit'])) {
        $id = (int)$_POST['id'];
        $name = sanitize_input($conn, $_POST['name']);
        $sql = "UPDATE categories SET name = '$name' WHERE id = $id";
        mysqli_query($conn, $sql);
    } elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        $sql = "DELETE FROM categories WHERE id = $id";
        mysqli_query($conn, $sql);
    }
    header('Location: categories.php');
    exit;
}

// Fetch all categories
$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<h1 class="mt-4">Manage Categories</h1>

<!-- Add Category Form -->
<div class="card mb-4">
    <div class="card-header">Add New Category</div>
    <div class="card-body">
        <form action="categories.php" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Add Category</button>
        </form>
    </div>
</div>

<!-- Categories Table -->
<div class="card">
    <div class="card-header">Existing Categories</div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $category['id']; ?></td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td>
                            <!-- Edit Button and Modal -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $category['id']; ?>">Edit</button>
                            <div class="modal fade" id="editModal<?php echo $category['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Category</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="categories.php" method="post">
                                                <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Category Name</label>
                                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                                                </div>
                                                <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Form -->
                            <form action="categories.php" method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
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
