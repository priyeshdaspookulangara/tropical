<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/header.php';

function handle_file_upload($file_key, $current_value) {
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/';
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                // In a real app, you would log this error
                exit("Error: Could not create the uploads directory.");
            }
        }

        $filename = uniqid() . '-' . basename($_FILES[$file_key]['name']);
        $destination = $upload_dir . $filename;

        if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $destination)) {
            return '/uploads/' . $filename;
        } else {
            // In a real app, you would log this error
             exit("Error: Could not move the uploaded file.");
        }
    } elseif (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] !== UPLOAD_ERR_NO_FILE) {
        // Handle other upload errors
        // In a real app, you would log this error with more details
        exit("Error: File upload failed with error code " . $_FILES[$file_key]['error']);
    }

    return $current_value;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $settings_to_update = [
        'site_name' => sanitize_input($conn, $_POST['site_name']),
        'banner_title' => sanitize_input($conn, $_POST['banner_title']),
        'banner_button1_text' => sanitize_input($conn, $_POST['banner_button1_text']),
        'banner_button1_link' => sanitize_input($conn, $_POST['banner_button1_link']),
        'banner_button1_style' => sanitize_input($conn, $_POST['banner_button1_style']),
        'banner_button2_text' => sanitize_input($conn, $_POST['banner_button2_text']),
        'banner_button2_link' => sanitize_input($conn, $_POST['banner_button2_link']),
        'banner_button2_style' => sanitize_input($conn, $_POST['banner_button2_style']),
    ];

    $sql = "SELECT * FROM settings";
    $result = mysqli_query($conn, $sql);
    $current_settings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $current_settings[$row['setting_key']] = $row['setting_value'];
    }

    $settings_to_update['site_logo'] = handle_file_upload('site_logo', $current_settings['site_logo']);
    $settings_to_update['favicon'] = handle_file_upload('favicon', $current_settings['favicon']);
    $settings_to_update['banner_image'] = handle_file_upload('banner_image', $current_settings['banner_image']);

    // Use prepared statements to prevent SQL injection
    $sql = "UPDATE settings SET setting_value = ? WHERE setting_key = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        foreach ($settings_to_update as $key => $value) {
            mysqli_stmt_bind_param($stmt, "ss", $value, $key);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        // Handle error: prepared statement failed
        // In a real app, you would log this error
        exit("Error: Could not prepare the statement.");
    }

    header('Location: settings.php');
    exit;
}

// Fetch all settings
$sql = "SELECT * FROM settings";
$result = mysqli_query($conn, $sql);
$settings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<h1 class="mt-4">Website Settings</h1>

<form action="settings.php" method="post" enctype="multipart/form-data">
    <!-- Site Branding Settings -->
    <div class="card mb-4">
        <div class="card-header">Site Branding Settings</div>
        <div class="card-body">
            <!-- Site Logo -->
            <div class="mb-3">
                <label for="site_logo" class="form-label">Site Logo</label>
                <input type="file" name="site_logo" id="site_logo" class="form-control">
                <small class="form-text text-muted">Recommended size: 200x50 pixels, PNG format.</small>
                <?php if (!empty($settings['site_logo'])): ?>
                    <div class="mt-2">
                        <img src="<?php echo htmlspecialchars($settings['site_logo']); ?>" alt="Site Logo Preview" style="max-height: 50px;">
                    </div>
                <?php endif; ?>
            </div>

            <!-- Site Name -->
            <div class="mb-3">
                <label for="site_name" class="form-label">Site Name</label>
                <input type="text" name="site_name" id="site_name" class="form-control" value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
            </div>

            <!-- Favicon -->
            <div class="mb-3">
                <label for="favicon" class="form-label">Favicon</label>
                <input type="file" name="favicon" id="favicon" class="form-control">
                <small class="form-text text-muted">A small square icon (e.g., 32x32 pixels, ICO or PNG format).</small>
                 <?php if (!empty($settings['favicon'])): ?>
                    <div class="mt-2">
                        <img src="<?php echo htmlspecialchars($settings['favicon']); ?>" alt="Favicon Preview" style="max-height: 32px;">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Homepage Banner/Hero Section Management -->
    <div class="card mb-4">
        <div class="card-header">Homepage Banner/Hero Section Management</div>
        <div class="card-body">
            <!-- Banner/Hero Image Upload -->
            <div class="mb-3">
                <label for="banner_image" class="form-label">Banner/Hero Image</label>
                <input type="file" name="banner_image" id="banner_image" class="form-control">
                <small class="form-text text-muted">Recommended aspect ratio: 16:9, max file size: 2MB.</small>
                <?php if (!empty($settings['banner_image'])): ?>
                    <div class="mt-2">
                        <img src="<?php echo htmlspecialchars($settings['banner_image']); ?>" alt="Banner Preview" style="max-width: 300px;">
                    </div>
                <?php endif; ?>
            </div>

            <!-- Banner Title -->
            <div class="mb-3">
                <label for="banner_title" class="form-label">Banner Title</label>
                <input type="text" name="banner_title" id="banner_title" class="form-control" value="<?php echo htmlspecialchars($settings['banner_title']); ?>">
            </div>

            <!-- Action Button 1 -->
            <h5 class="mt-4">Action Button 1</h5>
            <div class="mb-3">
                <label for="banner_button1_text" class="form-label">Button Text</label>
                <input type="text" name="banner_button1_text" id="banner_button1_text" class="form-control" value="<?php echo htmlspecialchars($settings['banner_button1_text']); ?>">
            </div>
            <div class="mb-3">
                <label for="banner_button1_link" class="form-label">Button Link (URL)</label>
                <input type="url" name="banner_button1_link" id="banner_button1_link" class="form-control" value="<?php echo htmlspecialchars($settings['banner_button1_link']); ?>">
            </div>
            <div class="mb-3">
                <label for="banner_button1_style" class="form-label">Button Style/Color</label>
                <select name="banner_button1_style" id="banner_button1_style" class="form-select">
                    <option value="primary" <?php echo ($settings['banner_button1_style'] == 'primary') ? 'selected' : ''; ?>>Primary</option>
                    <option value="secondary" <?php echo ($settings['banner_button1_style'] == 'secondary') ? 'selected' : ''; ?>>Secondary</option>
                    <option value="custom" <?php echo ($settings['banner_button1_style'] == 'custom') ? 'selected' : ''; ?>>Custom</option>
                </select>
            </div>

            <!-- Action Button 2 -->
            <h5 class="mt-4">Action Button 2</h5>
            <div class="mb-3">
                <label for="banner_button2_text" class="form-label">Button Text</label>
                <input type="text" name="banner_button2_text" id="banner_button2_text" class="form-control" value="<?php echo htmlspecialchars($settings['banner_button2_text']); ?>">
            </div>
            <div class="mb-3">
                <label for="banner_button2_link" class="form-label">Button Link (URL)</label>
                <input type="url" name="banner_button2_link" id="banner_button2_link" class="form-control" value="<?php echo htmlspecialchars($settings['banner_button2_link']); ?>">
            </div>
            <div class="mb-3">
                <label for="banner_button2_style" class="form-label">Button Style/Color</label>
                <select name="banner_button2_style" id="banner_button2_style" class="form-select">
                    <option value="primary" <?php echo ($settings['banner_button2_style'] == 'primary') ? 'selected' : ''; ?>>Primary</option>
                    <option value="secondary" <?php echo ($settings['banner_button2_style'] == 'secondary') ? 'selected' : ''; ?>>Secondary</option>
                    <option value="custom" <?php echo ($settings['banner_button2_style'] == 'custom') ? 'selected' : ''; ?>>Custom</option>
                </select>
            </div>
        </div>
    </div>

    <button type="submit" name="save_settings" class="btn btn-primary">Save Settings</button>
</form>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
