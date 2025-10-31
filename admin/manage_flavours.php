<?php
// Define the root directory
define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/config/db.php';
require_once ROOT_PATH . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

// Handle form submissions for add, edit, and delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = sanitize_input($conn, $_POST['name']);
        $sql = "INSERT INTO flavours (name) VALUES ('$name')";
        mysqli_query($conn, $sql);
    } elseif (isset($_POST['edit'])) {
        $id = (int)$_POST['id'];
        $name = sanitize_input($conn, $_POST['name']);
        $sql = "UPDATE flavours SET name = '$name' WHERE id = $id";
        mysqli_query($conn, $sql);
    } elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        $sql = "DELETE FROM flavours WHERE id = $id";
        mysqli_query($conn, $sql);
    }
    header('Location: manage_flavours.php');
    exit;
}

// Fetch all flavours
$sql = "SELECT * FROM flavours";
$result = mysqli_query($conn, $sql);
$flavours = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<h1 class="mt-4">Manage Flavours</h1>

<!-- Add Flavour Form -->
<div class="card mb-4">
    <div class="card-header">Add New Flavour</div>
    <div class="card-body">
        <form action="manage_flavours.php" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Flavour Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Add Flavour</button>
        </form>
    </div>
</div>

<!-- Flavours Table -->
<div class="card">
    <div class="card-header">Existing Flavours</div>
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
                <?php foreach ($flavours as $flavour): ?>
                    <tr>
                        <td><?php echo $flavour['id']; ?></td>
                        <td><?php echo htmlspecialchars($flavour['name']); ?></td>
                        <td>
                            <!-- Edit Button and Modal -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $flavour['id']; ?>">Edit</button>
                            <div class="modal fade" id="editModal<?php echo $flavour['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Flavour</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="manage_flavours.php" method="post">
                                                <input type="hidden" name="id" value="<?php echo $flavour['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Flavour Name</label>
                                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($flavour['name']); ?>" required>
                                                </div>
                                                <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Form -->
                            <form action="manage_flavours.php" method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $flavour['id']; ?>">
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
