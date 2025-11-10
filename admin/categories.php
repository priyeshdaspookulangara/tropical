<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle form submissions for add, edit, and delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = sanitize_input($conn, $_POST['name']);
        $parent_id = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;
        $stmt = mysqli_prepare($conn, "INSERT INTO categories (name, parent_id) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "si", $name, $parent_id);
        mysqli_stmt_execute($stmt);
    } elseif (isset($_POST['edit'])) {
        $id = (int)$_POST['id'];
        $name = sanitize_input($conn, $_POST['name']);
        $parent_id = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;
        $stmt = mysqli_prepare($conn, "UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sii", $name, $parent_id, $id);
        mysqli_stmt_execute($stmt);
    } elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
    header('Location: categories.php');
    exit;
}

// Fetch all categories
$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

require_once __DIR__ . '/includes/header.php';
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
            <div class="mb-3">
                <label for="parent_id" class="form-label">Parent Category</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">None</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Add Category</button>
        </form>
    </div>
</div>

<!-- Categories Tree View -->
<div class="card">
    <div class="card-header">Existing Categories</div>
    <div class="card-body">
        <?php
        function build_category_tree(array $elements, $parentId = null) {
            $branch = array();
            foreach ($elements as $element) {
                if ($element['parent_id'] == $parentId) {
                    $children = build_category_tree($elements, $element['id']);
                    if ($children) {
                        $element['children'] = $children;
                    }
                    $branch[] = $element;
                }
            }
            return $branch;
        }

        function display_category_tree($tree, $categories) {
            echo '<ul class="list-group">';
            foreach ($tree as $category) {
                echo '<li class="list-group-item">';
                echo '<span>' . htmlspecialchars($category['name']) . '</span>';
                echo '<div class="float-end">';

                // Edit Button and Modal
                echo '<button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal' . $category['id'] . '">Edit</button>';
                echo '<div class="modal fade" id="editModal' . $category['id'] . '" tabindex="-1">';
                echo '<div class="modal-dialog">';
                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo '<h5 class="modal-title">Edit Category</h5>';
                echo '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo '<form action="categories.php" method="post">';
                echo '<input type="hidden" name="id" value="' . $category['id'] . '">';
                echo '<div class="mb-3">';
                echo '<label for="name" class="form-label">Category Name</label>';
                echo '<input type="text" name="name" class="form-control" value="' . htmlspecialchars($category['name']) . '" required>';
                echo '</div>';
                echo '<div class="mb-3">';
                echo '<label for="parent_id" class="form-label">Parent Category</label>';
                echo '<select name="parent_id" class="form-control">';
                echo '<option value="">None</option>';
                foreach ($categories as $cat) {
                    $selected = ($category['parent_id'] == $cat['id']) ? 'selected' : '';
                    echo '<option value="' . $cat['id'] . '" ' . $selected . '>' . htmlspecialchars($cat['name']) . '</option>';
                }
                echo '</select>';
                echo '</div>';
                echo '<button type="submit" name="edit" class="btn btn-primary">Save changes</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                // Delete Form
                echo '<form action="categories.php" method="post" class="d-inline ms-2">';
                echo '<input type="hidden" name="id" value="' . $category['id'] . '">';
                echo '<button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>';
                echo '</form>';

                echo '</div>';

                if (isset($category['children'])) {
                    display_category_tree($category['children'], $categories);
                }
                echo '</li>';
            }
            echo '</ul>';
        }

        $category_tree = build_category_tree($categories);
        display_category_tree($category_tree, $categories);
        ?>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
