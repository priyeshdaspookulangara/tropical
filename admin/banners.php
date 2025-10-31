<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/header.php';

// Handle form submissions for add, edit, and delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $caption = sanitize_input($conn, $_POST['caption']);
        $caption_color = sanitize_input($conn, $_POST['caption_color']);
        $button_text = sanitize_input($conn, $_POST['button_text']);
        $button_url = sanitize_input($conn, $_POST['button_url']);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = "/uploads/" . basename($_FILES["image"]["name"]);
            }
        }

        $sql = "INSERT INTO banners (image_url, caption, caption_color, button_text, button_url, is_active) VALUES ('$image_url', '$caption', '$caption_color', '$button_text', '$button_url', $is_active)";
        mysqli_query($conn, $sql);
    } elseif (isset($_POST['edit'])) {
        $id = (int)$_POST['id'];
        $caption = sanitize_input($conn, $_POST['caption']);
        $caption_color = sanitize_input($conn, $_POST['caption_color']);
        $button_text = sanitize_input($conn, $_POST['button_text']);
        $button_url = sanitize_input($conn, $_POST['button_url']);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $image_url = sanitize_input($conn, $_POST['existing_image_url']);
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = "/uploads/" . basename($_FILES["image"]["name"]);
            }
        }

        $sql = "UPDATE banners SET image_url = '$image_url', caption = '$caption', caption_color = '$caption_color', button_text = '$button_text', button_url = '$button_url', is_active = $is_active WHERE id = $id";
        mysqli_query($conn, $sql);
    } elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        $sql = "DELETE FROM banners WHERE id = $id";
        mysqli_query($conn, $sql);
    }
    header('Location: banners.php');
    exit;
}

// Fetch all banners
$sql = "SELECT * FROM banners";
$result = mysqli_query($conn, $sql);
$banners = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<h1 class="mt-4">Manage Banners</h1>

<!-- Add Banner Form -->
<div class="card mb-4">
    <div class="card-header">Add New Banner</div>
    <div class="card-body">
        <form action="banners.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="image" class="form-label">Banner Image</label>
                <input type="file" name="image" id="image" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="caption" class="form-label">Caption</label>
                <input type="text" name="caption" id="caption" class="form-control">
            </div>
            <div class="mb-3">
                <label for="caption_color" class="form-label">Caption Color</label>
                <input type="color" name="caption_color" id="caption_color" class="form-control" value="#000000">
            </div>
            <div class="mb-3">
                <label for="button_text" class="form-label">Button Text</label>
                <input type="text" name="button_text" id="button_text" class="form-control">
            </div>
            <div class="mb-3">
                <label for="button_url" class="form-label">Button URL</label>
                <input type="text" name="button_url" id="button_url" class="form-control">
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1">
                <label class="form-check-label" for="is_active">
                    Active
                </label>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Add Banner</button>
        </form>
    </div>
</div>

<!-- Banners Table -->
<div class="card">
    <div class="card-header">Existing Banners</div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Caption</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($banners as $banner): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($banner['image_url']); ?>" width="100"></td>
                        <td><?php echo htmlspecialchars($banner['caption']); ?></td>
                        <td><?php echo $banner['is_active'] ? 'Yes' : 'No'; ?></td>
                        <td>
                            <!-- Edit Button and Modal -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $banner['id']; ?>">Edit</button>
                            <div class="modal fade" id="editModal<?php echo $banner['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Banner</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="banners.php" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="id" value="<?php echo $banner['id']; ?>">
                                                <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($banner['image_url']); ?>">
                                                <div class="mb-3">
                                                    <label for="image" class="form-label">Banner Image</label>
                                                    <input type="file" name="image" id="image" class="form-control">
                                                    <img src="<?php echo htmlspecialchars($banner['image_url']); ?>" width="100" class="mt-2">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="caption" class="form-label">Caption</label>
                                                    <input type="text" name="caption" class="form-control" value="<?php echo htmlspecialchars($banner['caption']); ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="caption_color" class="form-label">Caption Color</label>
                                                    <input type="color" name="caption_color" class="form-control" value="<?php echo htmlspecialchars($banner['caption_color']); ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="button_text" class="form-label">Button Text</label>
                                                    <input type="text" name="button_text" class="form-control" value="<?php echo htmlspecialchars($banner['button_text']); ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="button_url" class="form-label">Button URL</label>
                                                    <input type="text" name="button_url" class="form-control" value="<?php echo htmlspecialchars($banner['button_url']); ?>">
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" <?php echo $banner['is_active'] ? 'checked' : ''; ?>>
                                                    <label class="form-check-label">
                                                        Active
                                                    </label>
                                                </div>
                                                <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Form -->
                            <form action="banners.php" method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $banner['id']; ?>">
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