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

// Fetch advanced details
$product_lines_sql = "
    SELECT pl.name as line_name, pf.name as form_name, pm.name as medium_name
    FROM fruit_product_line_configs fplc
    JOIN product_lines pl ON fplc.product_line_id = pl.id
    LEFT JOIN product_forms pf ON fplc.product_form_id = pf.id
    LEFT JOIN packing_media pm ON fplc.packing_medium_id = pm.id
    WHERE fplc.fruit_id = $fruit_id
";
$product_lines_result = mysqli_query($conn, $product_lines_sql);
$product_lines = mysqli_fetch_all($product_lines_result, MYSQLI_ASSOC);

$packagings_sql = "
    SELECT p.name as packaging_name, s.name as storage_name, fp.shelf_life_months, pq.value, pq.unit
    FROM fruit_packagings fp
    JOIN packagings p ON fp.packaging_id = p.id
    JOIN storages s ON fp.storage_id = s.id
    LEFT JOIN packaging_quantities pq ON fp.id = pq.fruit_packaging_id
    WHERE fp.fruit_id = $fruit_id
";
$packagings_result = mysqli_query($conn, $packagings_sql);
$packagings = [];
while($row = mysqli_fetch_assoc($packagings_result)) {
    $packagings[$row['packaging_name']]['storage'] = $row['storage_name'];
    $packagings[$row['packaging_name']]['shelf_life'] = $row['shelf_life_months'];
    $packagings[$row['packaging_name']]['quantities'][] = ['value' => $row['value'], 'unit' => $row['unit']];
}

$applications_sql = "
    SELECT a.name FROM applications a
    JOIN fruit_application fa ON a.id = fa.application_id
    WHERE fa.fruit_id = $fruit_id
";
$applications_result = mysqli_query($conn, $applications_sql);
$applications = mysqli_fetch_all($applications_result, MYSQLI_ASSOC);

$trends_sql = "
    SELECT t.name FROM trends t
    JOIN fruit_trend ft ON t.id = ft.trend_id
    WHERE ft.fruit_id = $fruit_id
";
$trends_result = mysqli_query($conn, $trends_sql);
$trends = mysqli_fetch_all($trends_result, MYSQLI_ASSOC);
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

                <h4>Product Lines:</h4>
                <ul>
                    <?php foreach ($product_lines as $line): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($line['line_name']); ?></strong>
                            <?php if ($line['form_name']): ?>
                                (<?php echo htmlspecialchars($line['form_name']); ?>
                                <?php if ($line['medium_name']): ?>
                                    in <?php echo htmlspecialchars($line['medium_name']); ?>
                                <?php endif; ?>
                                )
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <hr>

                <h4>Packaging:</h4>
                <?php foreach ($packagings as $name => $pkg): ?>
                    <p>
                        <strong><?php echo htmlspecialchars($name); ?></strong><br>
                        Storage: <?php echo htmlspecialchars($pkg['storage']); ?><br>
                        Shelf Life: <?php echo htmlspecialchars($pkg['shelf_life']); ?> months<br>
                        Quantities:
                        <?php foreach ($pkg['quantities'] as $qty): ?>
                            <?php echo htmlspecialchars($qty['value'] . ' ' . $qty['unit']); ?>
                        <?php endforeach; ?>
                    </p>
                <?php endforeach; ?>

                <hr>

                <h4>Applications:</h4>
                <ul>
                    <?php foreach ($applications as $app): ?>
                        <li><?php echo htmlspecialchars($app['name']); ?></li>
                    <?php endforeach; ?>
                </ul>

                <hr>

                <h4>Trends:</h4>
                <ul>
                    <?php foreach ($trends as $trend): ?>
                        <li><?php echo htmlspecialchars($trend['name']); ?></li>
                    <?php endforeach; ?>
                </ul>

                <hr>

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