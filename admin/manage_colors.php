<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/header.php';

// Handle form submissions for add, edit, and delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = sanitize_input($conn, $_POST['name']);
        $sql = "INSERT INTO colors (name) VALUES ('$name')";
        mysqli_query($conn, $sql);
    } elseif (isset($_POST['edit'])) {
        $id = (int)$_POST['id'];
        $name = sanitize_input($conn, $_POST['name']);
        $sql = "UPDATE colors SET name = '$name' WHERE id = $id";
        mysqli_query($conn, $sql);
    } elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        $sql = "DELETE FROM colors WHERE id = $id";
        mysqli_query($conn, $sql);
    }
    header('Location: manage_colors.php');
    exit;
}

// Fetch all colors
$sql = "SELECT * FROM colors";
$result = mysqli_query($conn, $sql);
$colors = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<h1 class="mt-4">Manage Colors</h1>

<!-- Add Color Form -->
<div class="card mb-4">
    <div class="card-header">Add New Color</div>
    <div class="card-body">
        <form action="manage_colors.php" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Color Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Add Color</button>
        </form>
    </div>
</div>

<!-- Colors Table -->
<div class="card">
    <div class="card-header">Existing Colors</div>
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
                <?php foreach ($colors as $color): ?>
                    <tr>
                        <td><?php echo $color['id']; ?></td>
                        <td><?php echo htmlspecialchars($color['name']); ?></td>
                        <td>
                            <!-- Edit Button and Modal -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $color['id']; ?>">Edit</button>
                            <div class="modal fade" id="editModal<?php echo $color['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Color</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="manage_colors.php" method="post">
                                                <input type="hidden" name="id" value="<?php echo $color['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Color Name</label>
                                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($color['name']); ?>" required>
                                                </div>
                                                <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Form -->
                            <form action="manage_colors.php" method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $color['id']; ?>">
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
