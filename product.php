<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$fruit_id = (int)$_GET['id'];

// Fetch fruit details
$sql = "
    SELECT f.*, c.name as category_name
    FROM fruits f
    JOIN categories c ON f.category_id = c.id
    WHERE f.id = $fruit_id
";
$result = mysqli_query($conn, $sql);
$fruit = mysqli_fetch_assoc($result);

if (!$fruit) {
    header('Location: index.php');
    exit;
}

// Fetch available selling types for this fruit
$sql = "
    SELECT st.id, st.name
    FROM selling_types st
    JOIN fruit_selling_types fst ON st.id = fst.selling_type_id
    WHERE fst.fruit_id = $fruit_id
";
$result = mysqli_query($conn, $sql);
$selling_types = mysqli_fetch_all($result, MYSQLI_ASSOC);

$product_specifications = [
    '8oz_squat' => [
        'name' => '8 oz (squat)',
        'cutting_details' => [
            ['cutting' => 'Slices', 'net_weight' => 227, 'drained_weight' => 142],
            ['cutting' => 'Chunks', 'net_weight' => 227, 'drained_weight' => 142],
            ['cutting' => 'Tidbits', 'net_weight' => 227, 'drained_weight' => 142],
        ]
    ],
    '15oz_tin' => [
        'name' => '15 oz (tin)',
        'cutting_details' => [
            ['cutting' => 'Slices', 'net_weight' => 425, 'drained_weight' => 284],
            ['cutting' => 'Chunks', 'net_weight' => 425, 'drained_weight' => 284],
        ]
    ],
    '20oz_tin' => [
        'name' => '20 oz (tin)',
        'cutting_details' => [
            ['cutting' => 'Slices', 'net_weight' => 567, 'drained_weight' => 380],
            ['cutting' => 'Crushed', 'net_weight' => 567, 'drained_weight' => 380],
        ]
    ],
    '30oz_tin' => [
        'name' => '30 oz (tin)',
        'cutting_details' => [
            ['cutting' => 'Slices', 'net_weight' => 850, 'drained_weight' => 567],
            ['cutting' => 'Chunks', 'net_weight' => 850, 'drained_weight' => 567],
        ]
    ],
];

$active_size_slug = array_keys($product_specifications)[0];
if (isset($_GET['can_size']) && isset($product_specifications[$_GET['can_size']])) {
    $active_size_slug = $_GET['can_size'];
}

$active_specification = $product_specifications[$active_size_slug];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($fruit['name']); ?> - B2B Fruit Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <header class="bg-light p-3">
        <div class="container">
            <a href="index.php" class="btn btn-secondary">Back to Products</a>
        </div>
    </header>

    <main class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($fruit['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($fruit['name']); ?>">
            </div>
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($fruit['name']); ?></h1>
                <p class="text-muted">Category: <?php echo htmlspecialchars($fruit['category_name']); ?></p>
                <p><?php echo nl2br(htmlspecialchars($fruit['description'])); ?></p>

                <hr>

                <h4>Available As:</h4>
                <ul>
                    <?php foreach ($selling_types as $type): ?>
                        <li><?php echo htmlspecialchars($type['name']); ?></li>
                    <?php endforeach; ?>
                </ul>

                <hr>

                <!-- Specifications Section -->
                <div id="specifications-section" class="mt-4">
                    <h4>Specifications</h4>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <?php foreach ($product_specifications as $slug => $spec): ?>
                            <a href="?id=<?php echo $fruit_id; ?>&can_size=<?php echo $slug; ?>"
                               class="btn <?php echo ($slug === $active_size_slug) ? 'btn-primary' : 'btn-outline-primary'; ?>">
                                <?php echo htmlspecialchars($spec['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Cutting</th>
                                <th>Net Weight (G)</th>
                                <th>Drained Weight (G)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($active_specification['cutting_details'] as $detail): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($detail['cutting']); ?></td>
                                    <td><?php echo htmlspecialchars($detail['net_weight']); ?></td>
                                    <td><?php echo htmlspecialchars($detail['drained_weight']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Inquiry Form -->
                <div id="inquiry-form-container" class="card mt-4">
                    <div class="card-header">
                        <h3>Request a Quote</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success">Your inquiry has been sent successfully!</div>
                        <?php endif; ?>
                        <form action="submit_inquiry.php" method="post">
                            <input type="hidden" name="fruit_id" value="<?php echo $fruit['id']; ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Your Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="selling_type_id" class="form-label">Desired Selling Type</label>
                                <select name="selling_type_id" class="form-select" required>
                                    <?php foreach ($selling_types as $type): ?>
                                        <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Desired Quantity/Volume</label>
                                <input type="text" name="quantity" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message/Details</label>
                                <textarea name="message" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white p-4 text-center mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> B2B Fruit Products. All Rights Reserved.</p>
            <nav class="nav justify-content-center">
                <a class="nav-link text-white" href="page.php?slug=about">About Us</a>
                <a class="nav-link text-white" href="page.php?slug=values">Our Values</a>
                <a class="nav-link text-white" href="page.php?slug=contact">Contact Us</a>
            </nav>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>