<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/header.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = sanitize_input($conn, $_POST['status']);
    $sql = "UPDATE inquiries SET status = '$status' WHERE id = $id";
    mysqli_query($conn, $sql);
    header('Location: inquiries.php');
    exit;
}

// Fetch all inquiries with fruit and selling type names
$sql = "
    SELECT
        i.*,
        f.name as fruit_name,
        st.name as selling_type_name
    FROM inquiries i
    JOIN fruits f ON i.fruit_id = f.id
    JOIN selling_types st ON i.selling_type_id = st.id
    ORDER BY i.created_at DESC
";
$result = mysqli_query($conn, $sql);
$inquiries = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<h1 class="mt-4">Manage Inquiries</h1>

<div class="card">
    <div class="card-header">All Inquiries</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Message</th>
                        <th>Received At</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inquiries as $inquiry): ?>
                        <tr>
                            <td><?php echo $inquiry['id']; ?></td>
                            <td><?php echo htmlspecialchars($inquiry['name']); ?></td>
                            <td><?php echo htmlspecialchars($inquiry['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($inquiry['email']); ?></td>
                            <td><?php echo htmlspecialchars($inquiry['phone']); ?></td>
                            <td><?php echo htmlspecialchars($inquiry['fruit_name']); ?></td>
                            <td><?php echo htmlspecialchars($inquiry['selling_type_name']); ?></td>
                            <td><?php echo htmlspecialchars($inquiry['quantity']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($inquiry['message'])); ?></td>
                            <td><?php echo $inquiry['created_at']; ?></td>
                            <td>
                                <span class="badge bg-<?php echo $inquiry['status'] === 'New' ? 'primary' : ($inquiry['status'] === 'Contacted' ? 'info' : 'success'); ?>">
                                    <?php echo htmlspecialchars($inquiry['status']); ?>
                                </span>
                            </td>
                            <td>
                                <form action="inquiries.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $inquiry['id']; ?>">
                                    <select name="status" class="form-select form-select-sm mb-2">
                                        <option value="New" <?php echo ($inquiry['status'] === 'New') ? 'selected' : ''; ?>>New</option>
                                        <option value="Contacted" <?php echo ($inquiry['status'] === 'Contacted') ? 'selected' : ''; ?>>Contacted</option>
                                        <option value="Quoted" <?php echo ($inquiry['status'] === 'Quoted') ? 'selected' : ''; ?>>Quoted</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>