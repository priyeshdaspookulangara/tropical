<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inquiries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Manage Inquiries</h2>
        <a href="index.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <div class="card">
            <div class="card-header">All Inquiries</div>
            <div class="card-body">
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
</body>
</html>