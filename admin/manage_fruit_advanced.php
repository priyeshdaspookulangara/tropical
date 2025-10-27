<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}

// Fetch master data for form fields
$storages = get_master_data($conn, 'storages');
$product_lines = get_master_data($conn, 'product_lines');
$product_forms = get_master_data($conn, 'product_forms');
$packing_media = get_master_data($conn, 'packing_media');
$packagings = get_master_data($conn, 'packagings');
$applications = get_master_data($conn, 'applications');
$trends = get_master_data($conn, 'trends');
$suppliers = get_master_data($conn, 'suppliers');

function get_master_data($conn, $table_name) {
    $sql = "SELECT * FROM `$table_name` ORDER BY `name`";
    $result = mysqli_query($conn, $sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Step 1: Insert base fruit details
        $name = sanitize_input($conn, $_POST['name']);
        $origin = sanitize_input($conn, $_POST['origin']);
        $region = sanitize_input($conn, $_POST['region']);
        $storage_id = (int)$_POST['storage_id'];

        // For simplicity, we assume category_id is 1. This should be improved in a real app.
        $sql = "INSERT INTO `fruits` (`category_id`, `name`, `description`, `image`, `origin`, `region`, `storage_id`) VALUES (1, '$name', '', NULL, '$origin', '$region', $storage_id)";
        mysqli_query($conn, $sql);
        $fruit_id = mysqli_insert_id($conn);

        // Step 2: Insert product lines
        if (isset($_POST['product_lines'])) {
            foreach ($_POST['product_lines'] as $line) {
                $line_id = (int)$line['id'];
                $form_id = !empty($line['form_id']) ? (int)$line['form_id'] : 'NULL';
                $medium_id = !empty($line['medium_id']) ? (int)$line['medium_id'] : 'NULL';
                $sql = "INSERT INTO `fruit_product_line_configs` (`fruit_id`, `product_line_id`, `product_form_id`, `packing_medium_id`) VALUES ($fruit_id, $line_id, $form_id, $medium_id)";
                mysqli_query($conn, $sql);
            }
        }

        // Step 3: Insert packaging
        if (isset($_POST['packagings'])) {
            foreach ($_POST['packagings'] as $pkg) {
                $pkg_id = (int)$pkg['id'];
                $pkg_storage_id = (int)$pkg['storage_id'];
                $shelf_life = !empty($pkg['shelf_life']) ? (int)$pkg['shelf_life'] : 'NULL';

                $sql = "INSERT INTO `fruit_packagings` (`fruit_id`, `packaging_id`, `storage_id`, `shelf_life_months`) VALUES ($fruit_id, $pkg_id, $pkg_storage_id, $shelf_life)";
                mysqli_query($conn, $sql);
                $fruit_packaging_id = mysqli_insert_id($conn);

                if (isset($pkg['quantities'])) {
                    foreach ($pkg['quantities'] as $qty) {
                        $value = (float)$qty['value'];
                        $unit = sanitize_input($conn, $qty['unit']);
                        $sql = "INSERT INTO `packaging_quantities` (`fruit_packaging_id`, `value`, `unit`) VALUES ($fruit_packaging_id, $value, '$unit')";
                        mysqli_query($conn, $sql);
                    }
                }
            }
        }

        // Step 4: Link applications
        if (isset($_POST['applications'])) {
            foreach ($_POST['applications'] as $app_id) {
                $app_id = (int)$app_id;
                $sql = "INSERT INTO `fruit_application` (`fruit_id`, `application_id`) VALUES ($fruit_id, $app_id)";
                mysqli_query($conn, $sql);
            }
        }

        // Step 5: Link trends
        if (isset($_POST['trends'])) {
            foreach ($_POST['trends'] as $trend_id) {
                $trend_id = (int)$trend_id;
                $sql = "INSERT INTO `fruit_trend` (`fruit_id`, `trend_id`) VALUES ($fruit_id, $trend_id)";
                mysqli_query($conn, $sql);
            }
        }

        // Step 6: Link suppliers
        if (isset($_POST['suppliers'])) {
            foreach ($_POST['suppliers'] as $supplier_id) {
                $supplier_id = (int)$supplier_id;
                $sql = "INSERT INTO `fruit_supplier` (`fruit_id`, `supplier_id`) VALUES ($fruit_id, $supplier_id)";
                mysqli_query($conn, $sql);
            }
        }

        // Commit transaction
        mysqli_commit($conn);
        $success_message = "Fruit created successfully!";

    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        $error_message = "Error creating fruit: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fruit - Advanced</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-step { display: none; }
        .form-step.active { display: block; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Manage Fruit - Advanced</h2>
        <a href="fruits.php" class="btn btn-secondary mb-3">Back to Fruits List</a>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form action="manage_fruit_advanced.php" method="post" id="fruitForm">
                    <!-- Step 1: Basic Details -->
                    <div class="form-step active" id="step1">
                        <h4>Step 1: Basic Details</h4>
                        <div class="mb-3">
                            <label for="name" class="form-label">Fruit Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="origin" class="form-label">Origin</label>
                            <input type="text" name="origin" id="origin" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="region" class="form-label">Region</label>
                            <input type="text" name="region" id="region" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="storage_id" class="form-label">Storage Condition</label>
                            <select name="storage_id" id="storage_id" class="form-control">
                                <?php foreach ($storages as $storage): ?>
                                    <option value="<?php echo $storage['id']; ?>"><?php echo htmlspecialchars($storage['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
                    </div>

                    <!-- Step 2: Product Lines -->
                    <div class="form-step" id="step2">
                        <h4>Step 2: Product Lines</h4>
                        <!-- Dynamic product line rows will be added here -->
                        <div id="productLinesContainer"></div>
                        <button type="button" class="btn btn-info mt-2" onclick="addProductLine()">Add Product Line</button>
                        <hr>
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
                    </div>

                    <!-- Step 3: Packaging -->
                    <div class="form-step" id="step3">
                        <h4>Step 3: Packaging</h4>
                        <!-- Dynamic packaging rows will be added here -->
                        <div id="packagingContainer"></div>
                        <button type="button" class="btn btn-info mt-2" onclick="addPackaging()">Add Packaging</button>
                        <hr>
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
                    </div>

                    <!-- Step 4: Applications -->
                    <div class="form-step" id="step4">
                        <h4>Step 4: Applications</h4>
                        <div class="mb-3">
                            <label class="form-label">Select Applications</label>
                            <?php foreach ($applications as $application): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="applications[]" value="<?php echo $application['id']; ?>" id="app_<?php echo $application['id']; ?>">
                                    <label class="form-check-label" for="app_<?php echo $application['id']; ?>"><?php echo htmlspecialchars($application['name']); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <hr>
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
                    </div>

                    <!-- Step 5: Trends -->
                    <div class="form-step" id="step5">
                        <h4>Step 5: Trends</h4>
                        <div class="mb-3">
                            <label class="form-label">Select Trends</label>
                            <?php foreach ($trends as $trend): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="trends[]" value="<?php echo $trend['id']; ?>" id="trend_<?php echo $trend['id']; ?>">
                                    <label class="form-check-label" for="trend_<?php echo $trend['id']; ?>"><?php echo htmlspecialchars($trend['name']); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <hr>
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
                    </div>

                    <!-- Step 6: Suppliers -->
                    <div class="form-step" id="step6">
                        <h4>Step 6: Suppliers</h4>
                        <div class="mb-3">
                            <label class="form-label">Select Suppliers</label>
                            <?php foreach ($suppliers as $supplier): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="suppliers[]" value="<?php echo $supplier['id']; ?>" id="supplier_<?php echo $supplier['id']; ?>">
                                    <label class="form-check-label" for="supplier_<?php echo $supplier['id']; ?>"><?php echo htmlspecialchars($supplier['name']); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <hr>
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">Previous</button>
                        <button type="submit" class="btn btn-success">Save Fruit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;

        function showStep(step) {
            document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
            document.getElementById('step' + step).classList.add('active');
        }

        function nextStep() {
            if (currentStep < 6) {
                currentStep++;
                showStep(currentStep);
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        }

        // Dynamic form rows for product lines
        let productLineIndex = 0;
        function addProductLine() {
            productLineIndex++;
            const container = document.getElementById('productLinesContainer');
            const newRow = document.createElement('div');
            newRow.className = 'border p-3 mb-3';
            newRow.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Product Line</label>
                        <select name="product_lines[${productLineIndex}][id]" class="form-control">
                            <?php foreach ($product_lines as $line): ?>
                                <option value="<?php echo $line['id']; ?>"><?php echo htmlspecialchars($line['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Product Form (Optional)</label>
                        <select name="product_lines[${productLineIndex}][form_id]" class="form-control">
                            <option value="">None</option>
                            <?php foreach ($product_forms as $form): ?>
                                <option value="<?php echo $form['id']; ?>"><?php echo htmlspecialchars($form['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Packing Medium (Optional)</label>
                        <select name="product_lines[${productLineIndex}][medium_id]" class="form-control">
                            <option value="">None</option>
                            <?php foreach ($packing_media as $medium): ?>
                                <option value="<?php echo $medium['id']; ?>"><?php echo htmlspecialchars($medium['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.parentElement.remove()">Remove</button>
            `;
            container.appendChild(newRow);
        }

        // Dynamic form rows for packaging
        let packagingIndex = 0;
        function addPackaging() {
            packagingIndex++;
            const container = document.getElementById('packagingContainer');
            const newRow = document.createElement('div');
            newRow.className = 'border p-3 mb-3';
            newRow.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Packaging Type</label>
                        <select name="packagings[${packagingIndex}][id]" class="form-control">
                            <?php foreach ($packagings as $pkg): ?>
                                <option value="<?php echo $pkg['id']; ?>"><?php echo htmlspecialchars($pkg['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Storage</label>
                        <select name="packagings[${packagingIndex}][storage_id]" class="form-control">
                            <?php foreach ($storages as $storage): ?>
                                <option value="<?php echo $storage['id']; ?>"><?php echo htmlspecialchars($storage['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Shelf Life (Months)</label>
                        <input type="number" name="packagings[${packagingIndex}][shelf_life]" class="form-control">
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Quantities</h6>
                    <div id="quantitiesContainer_${packagingIndex}"></div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addQuantity(${packagingIndex})">Add Quantity</button>
                </div>
                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.parentElement.remove()">Remove Packaging</button>
            `;
            container.appendChild(newRow);
        }

        let quantityCounters = {};
        function addQuantity(pkgIndex) {
            if (!quantityCounters[pkgIndex]) {
                quantityCounters[pkgIndex] = 0;
            }
            quantityCounters[pkgIndex]++;
            const container = document.getElementById(`quantitiesContainer_${pkgIndex}`);
            const newRow = document.createElement('div');
            newRow.className = 'row mb-2';
            newRow.innerHTML = `
                <div class="col-5">
                    <input type="number" step="0.01" name="packagings[${pkgIndex}][quantities][${quantityCounters[pkgIndex]}][value]" class="form-control" placeholder="Value" required>
                </div>
                <div class="col-5">
                    <input type="text" name="packagings[${pkgIndex}][quantities][${quantityCounters[pkgIndex]}][unit]" class="form-control" placeholder="Unit (e.g., kg)" required>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">X</button>
                </div>
            `;
            container.appendChild(newRow);
        }

        showStep(currentStep);
    </script>
</body>
</html>
