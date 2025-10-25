<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}

// Fetch categories and selling types for forms
$categories_sql = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_sql);
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);

$selling_types_sql = "SELECT * FROM selling_types";
$selling_types_result = mysqli_query($conn, $selling_types_sql);
$selling_types = mysqli_fetch_all($selling_types_result, MYSQLI_ASSOC);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new fruit
    if (isset($_POST['add'])) {
        $name = sanitize_input($conn, $_POST['name']);
        $category_id = (int)$_POST['category_id'];
        $description = sanitize_input($conn, $_POST['description']);
        $selected_selling_types = $_POST['selling_types'] ?? [];

        // Handle image upload
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = __DIR__ . '/../uploads/';
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = mime_content_type($_FILES['image']['tmp_name']);

            if (in_array($file_type, $allowed_types)) {
                $image_name = uniqid() . '-' . basename($_FILES['image']['name']);
                $image_path = 'uploads/' . $image_name;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../' . $image_path);
            } else {
                // Handle invalid file type
                $error = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            }
        }

        $sql = "INSERT INTO fruits (name, category_id, description, image) VALUES ('$name', $category_id, '$description', '$image_path')";
        mysqli_query($conn, $sql);
        $fruit_id = mysqli_insert_id($conn);

        foreach ($selected_selling_types as $type_id) {
            $type_id = (int)$type_id;
            $sql = "INSERT INTO fruit_selling_types (fruit_id, selling_type_id) VALUES ($fruit_id, $type_id)";
            mysqli_query($conn, $sql);
        }
    }
    // Edit fruit
    elseif (isset($_POST['edit'])) {
        $id = (int)$_POST['id'];
        $name = sanitize_input($conn, $_POST['name']);
        $category_id = (int)$_POST['category_id'];
        $description = sanitize_input($conn, $_POST['description']);
        $selected_selling_types = $_POST['selling_types'] ?? [];

        // Handle image upload
        $image_sql = "";
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = __DIR__ . '/../uploads/';
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = mime_content_type($_FILES['image']['tmp_name']);

            if (in_array($file_type, $allowed_types)) {
                $image_name = uniqid() . '-' . basename($_FILES['image']['name']);
                $image_path = 'uploads/' . $image_name;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../' . $image_path);
                $image_sql = ", image = '$image_path'";
            } else {
                // Handle invalid file type
                $error = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            }
        }

        $sql = "UPDATE fruits SET name = '$name', category_id = $category_id, description = '$description' $image_sql WHERE id = $id";
        mysqli_query($conn, $sql);

        // Update selling types
        $sql = "DELETE FROM fruit_selling_types WHERE fruit_id = $id";
        mysqli_query($conn, $sql);
        foreach ($selected_selling_types as $type_id) {
            $type_id = (int)$type_id;
            $sql = "INSERT INTO fruit_selling_types (fruit_id, selling_type_id) VALUES ($id, $type_id)";
            mysqli_query($conn, $sql);
        }
    }
    // Delete fruit
    elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        // You might want to delete the image file from the server as well
        $sql = "DELETE FROM fruits WHERE id = $id";
        mysqli_query($conn, $sql);
    }
    header('Location: fruits.php');
    exit;
}

// Fetch all fruits with category names
$sql = "SELECT f.*, c.name as category_name FROM fruits f JOIN categories c ON f.category_id = c.id ORDER BY f.id DESC";
$result = mysqli_query($conn, $sql);
$fruits = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch all fruit-selling-type relationships
$fruit_selling_types_sql = "SELECT * FROM fruit_selling_types";
$fruit_selling_types_result = mysqli_query($conn, $fruit_selling_types_sql);
$fruit_selling_types = [];
while ($row = mysqli_fetch_assoc($fruit_selling_types_result)) {
    $fruit_selling_types[$row['fruit_id']][] = $row['selling_type_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fruits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Manage Fruits</h2>
    <a href="index.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <!-- Add Fruit Form -->
    <div class="card mb-4">
        <div class="card-header">Add New Fruit</div>
        <div class="card-body">
            <form action="fruits.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Fruit Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Selling Types</label>
                    <div>
                        <?php foreach ($selling_types as $type): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="selling_types[]" value="<?php echo $type['id']; ?>">
                                <label class="form-check-label"><?php echo htmlspecialchars($type['name']); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" name="add" class="btn btn-primary">Add Fruit</button>
            </form>
        </div>
    </div>

    <!-- Fruits Table -->
    <div class="card">
        <div class="card-header">Existing Fruits</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($fruits as $fruit): ?>
                    <tr>
                        <td><?php echo $fruit['id']; ?></td>
                        <td><?php echo htmlspecialchars($fruit['name']); ?></td>
                        <td><?php echo htmlspecialchars($fruit['category_name']); ?></td>
                        <td>
                            <?php if ($fruit['image']): ?>
                                <img src="../<?php echo htmlspecialchars($fruit['image']); ?>" alt="<?php echo htmlspecialchars($fruit['name']); ?>" width="50">
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $fruit['id']; ?>">Edit</button>
                            <form action="fruits.php" method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $fruit['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?php echo $fruit['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Fruit</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="fruits.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $fruit['id']; ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Fruit Name</label>
                                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($fruit['name']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select name="category_id" class="form-select" required>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $fruit['category_id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" class="form-control" required><?php echo htmlspecialchars($fruit['description']); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Current Image</label>
                                            <div>
                                                <?php if ($fruit['image']): ?>
                                                    <img src="../<?php echo htmlspecialchars($fruit['image']); ?>" width="100">
                                                <?php endif; ?>
                                            </div>
                                            <label class="form-label mt-2">New Image (optional)</label>
                                            <input type="file" name="image" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Selling Types</label>
                                            <div>
                                                <?php
                                                $fruit_selling_type_ids = $fruit_selling_types[$fruit['id']] ?? [];
                                                foreach ($selling_types as $type): ?>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="selling_types[]" value="<?php echo $type['id']; ?>" <?php echo in_array($type['id'], $fruit_selling_type_ids) ? 'checked' : ''; ?>>
                                                        <label class="form-check-label"><?php echo htmlspecialchars($type['name']); ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>