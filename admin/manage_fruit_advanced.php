<?php
// Define the root directory
define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/config/db.php';
require_once ROOT_PATH . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

// Fetch categories, selling types, flavours, colors, and suppliers for forms
$categories_sql = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_sql);
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);

$selling_types_sql = "SELECT * FROM selling_types";
$selling_types_result = mysqli_query($conn, $selling_types_sql);
$selling_types = mysqli_fetch_all($selling_types_result, MYSQLI_ASSOC);

$flavours_sql = "SELECT * FROM flavours";
$flavours_result = mysqli_query($conn, $flavours_sql);
$flavours = mysqli_fetch_all($flavours_result, MYSQLI_ASSOC);

$colors_sql = "SELECT * FROM colors";
$colors_result = mysqli_query($conn, $colors_sql);
$colors = mysqli_fetch_all($colors_result, MYSQLI_ASSOC);

$suppliers_sql = "SELECT * FROM suppliers";
$suppliers_result = mysqli_query($conn, $suppliers_sql);
$suppliers = mysqli_fetch_all($suppliers_result, MYSQLI_ASSOC);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new fruit
    if (isset($_POST['add'])) {
        $name = sanitize_input($conn, $_POST['name']);
        $category_id = (int)$_POST['category_id'];
        $description = sanitize_input($conn, $_POST['description']);
        $selected_selling_types = $_POST['selling_types'] ?? [];
        $selected_flavours = $_POST['flavours'] ?? [];
        $selected_colors = $_POST['colors'] ?? [];
        $selected_suppliers = $_POST['suppliers'] ?? [];

        // Handle image upload
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = ROOT_PATH . '/uploads/';
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = mime_content_type($_FILES['image']['tmp_name']);

            if (in_array($file_type, $allowed_types)) {
                $image_name = uniqid() . '-' . basename($_FILES['image']['name']);
                $image_path = 'uploads/' . $image_name;
                move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);
            } else {
                $error = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            }
        }

        $stmt = mysqli_prepare($conn, "INSERT INTO fruits (name, category_id, description, image) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "siss", $name, $category_id, $description, $image_path);
        mysqli_stmt_execute($stmt);
        $fruit_id = mysqli_insert_id($conn);

        foreach ($selected_selling_types as $type_id) {
            $type_id = (int)$type_id;
            $stmt = mysqli_prepare($conn, "INSERT INTO fruit_selling_types (fruit_id, selling_type_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ii", $fruit_id, $type_id);
            mysqli_stmt_execute($stmt);
        }

        foreach ($selected_flavours as $flavour_id) {
            $flavour_id = (int)$flavour_id;
            $stmt = mysqli_prepare($conn, "INSERT INTO fruit_flavours (fruit_id, flavour_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ii", $fruit_id, $flavour_id);
            mysqli_stmt_execute($stmt);
        }

        foreach ($selected_colors as $color_id) {
            $color_id = (int)$color_id;
            $stmt = mysqli_prepare($conn, "INSERT INTO fruit_colors (fruit_id, color_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ii", $fruit_id, $color_id);
            mysqli_stmt_execute($stmt);
        }

        foreach ($selected_suppliers as $supplier_id) {
            $supplier_id = (int)$supplier_id;
            $stmt = mysqli_prepare($conn, "INSERT INTO fruit_suppliers (fruit_id, supplier_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ii", $fruit_id, $supplier_id);
            mysqli_stmt_execute($stmt);
        }
    }
    // Edit fruit
    elseif (isset($_POST['edit'])) {
        $id = (int)$_POST['id'];
        $name = sanitize_input($conn, $_POST['name']);
        $category_id = (int)$_POST['category_id'];
        $description = sanitize_input($conn, $_POST['description']);
        $selected_selling_types = $_POST['selling_types'] ?? [];
        $selected_flavours = $_POST['flavours'] ?? [];
        $selected_colors = $_POST['colors'] ?? [];
        $selected_suppliers = $_POST['suppliers'] ?? [];

        // Handle image upload
        $image_sql = "";
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = ROOT_PATH . '/uploads/';
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = mime_content_type($_FILES['image']['tmp_name']);

            if (in_array($file_type, $allowed_types)) {
                $image_name = uniqid() . '-' . basename($_FILES['image']['name']);
                $image_path = 'uploads/' . $image_name;
                move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);
                $stmt = mysqli_prepare($conn, "UPDATE fruits SET name = ?, category_id = ?, description = ?, image = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "sissi", $name, $category_id, $description, $image_path, $id);
            } else {
                $error = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
                $stmt = mysqli_prepare($conn, "UPDATE fruits SET name = ?, category_id = ?, description = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "sisi", $name, $category_id, $description, $id);
            }
        } else {
            $stmt = mysqli_prepare($conn, "UPDATE fruits SET name = ?, category_id = ?, description = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sisi", $name, $category_id, $description, $id);
        }
        mysqli_stmt_execute($stmt);

        // Update selling types
        $stmt = mysqli_prepare($conn, "DELETE FROM fruit_selling_types WHERE fruit_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        foreach ($selected_selling_types as $type_id) {
            $type_id = (int)$type_id;
            $stmt = mysqli_prepare($conn, "INSERT INTO fruit_selling_types (fruit_id, selling_type_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ii", $id, $type_id);
            mysqli_stmt_execute($stmt);
        }

        // Update flavours
        $stmt = mysqli_prepare($conn, "DELETE FROM fruit_flavours WHERE fruit_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        foreach ($selected_flavours as $flavour_id) {
            $flavour_id = (int)$flavour_id;
            $stmt = mysqli_prepare($conn, "INSERT INTO fruit_flavours (fruit_id, flavour_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ii", $id, $flavour_id);
            mysqli_stmt_execute($stmt);
        }

        // Update colors
        $stmt = mysqli_prepare($conn, "DELETE FROM fruit_colors WHERE fruit_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        foreach ($selected_colors as $color_id) {
            $color_id = (int)$color_id;
            $stmt = mysqli_prepare($conn, "INSERT INTO fruit_colors (fruit_id, color_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ii", $id, $color_id);
            mysqli_stmt_execute($stmt);
        }

        // Update suppliers
        $stmt = mysqli_prepare($conn, "DELETE FROM fruit_suppliers WHERE fruit_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        foreach ($selected_suppliers as $supplier_id) {
            $supplier_id = (int)$supplier_id;
            $stmt = mysqli_prepare($conn, "INSERT INTO fruit_suppliers (fruit_id, supplier_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ii", $id, $supplier_id);
            mysqli_stmt_execute($stmt);
        }
    }
    // Delete fruit
    elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];

        // First, get the image path
        $stmt = mysqli_prepare($conn, "SELECT image FROM fruits WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $image_path = $row['image'];
            if ($image_path && file_exists(ROOT_PATH . '/' . $image_path)) {
                unlink(ROOT_PATH . '/' . $image_path);
            }
        }

        // Then, delete the fruit record
        $stmt = mysqli_prepare($conn, "DELETE FROM fruits WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
    header('Location: manage_fruit_advanced.php');
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

// Fetch all fruit-flavour relationships
$fruit_flavours_sql = "SELECT * FROM fruit_flavours";
$fruit_flavours_result = mysqli_query($conn, $fruit_flavours_sql);
$fruit_flavours = [];
while ($row = mysqli_fetch_assoc($fruit_flavours_result)) {
    $fruit_flavours[$row['fruit_id']][] = $row['flavour_id'];
}

// Fetch all fruit-color relationships
$fruit_colors_sql = "SELECT * FROM fruit_colors";
$fruit_colors_result = mysqli_query($conn, $fruit_colors_sql);
$fruit_colors = [];
while ($row = mysqli_fetch_assoc($fruit_colors_result)) {
    $fruit_colors[$row['fruit_id']][] = $row['color_id'];
}

// Fetch all fruit-supplier relationships
$fruit_suppliers_sql = "SELECT * FROM fruit_suppliers";
$fruit_suppliers_result = mysqli_query($conn, $fruit_suppliers_sql);
$fruit_suppliers = [];
while ($row = mysqli_fetch_assoc($fruit_suppliers_result)) {
    $fruit_suppliers[$row['fruit_id']][] = $row['supplier_id'];
}
?>

<h1 class="mt-4">Manage Fruits</h1>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<!-- Add Fruit Form -->
<div class="card mb-4">
    <div class="card-header">Add New Fruit</div>
    <div class="card-body">
        <form action="manage_fruit_advanced.php" method="post" enctype="multipart/form-data">
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
            <div class="mb-3">
                <label class="form-label">Flavours</label>
                <div>
                    <?php foreach ($flavours as $flavour): ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="flavours[]" value="<?php echo $flavour['id']; ?>">
                            <label class="form-check-label"><?php echo htmlspecialchars($flavour['name']); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Colors</label>
                <div>
                    <?php foreach ($colors as $color): ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="colors[]" value="<?php echo $color['id']; ?>">
                            <label class="form-check-label"><?php echo htmlspecialchars($color['name']); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Suppliers</label>
                <div>
                    <?php foreach ($suppliers as $supplier): ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="suppliers[]" value="<?php echo $supplier['id']; ?>">
                            <label class="form-check-label"><?php echo htmlspecialchars($supplier['name']); ?></label>
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
                        <form action="manage_fruit_advanced.php" method="post" class="d-inline">
                            <input type="hidden" name="id" value="<?php echo $fruit['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?php echo $fruit['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Fruit</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form action="manage_fruit_advanced.php" method="post" enctype="multipart/form-data">
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
                                    <div class="mb-3">
                                        <label class="form-label">Flavours</label>
                                        <div>
                                            <?php
                                            $fruit_flavour_ids = $fruit_flavours[$fruit['id']] ?? [];
                                            foreach ($flavours as $flavour): ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="flavours[]" value="<?php echo $flavour['id']; ?>" <?php echo in_array($flavour['id'], $fruit_flavour_ids) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label"><?php echo htmlspecialchars($flavour['name']); ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Colors</label>
                                        <div>
                                            <?php
                                            $fruit_color_ids = $fruit_colors[$fruit['id']] ?? [];
                                            foreach ($colors as $color): ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="colors[]" value="<?php echo $color['id']; ?>" <?php echo in_array($color['id'], $fruit_color_ids) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label"><?php echo htmlspecialchars($color['name']); ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Suppliers</label>
                                        <div>
                                            <?php
                                            $fruit_supplier_ids = $fruit_suppliers[$fruit['id']] ?? [];
                                            foreach ($suppliers as $supplier): ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="suppliers[]" value="<?php echo $supplier['id']; ?>" <?php echo in_array($supplier['id'], $fruit_supplier_ids) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label"><?php echo htmlspecialchars($supplier['name']); ?></label>
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

<?php
require_once __DIR__ . '/includes/footer.php';
?>
