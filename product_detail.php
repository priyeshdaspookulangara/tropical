<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

// --- Data Fetching ---
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;
$features = [];
$category_name = 'Products'; // Default category name

if ($product_id > 0) {
    // Fetch main product details
    $sql = "
        SELECT f.name, f.description, f.image, c.name as category_name
        FROM fruits f
        JOIN categories c ON f.category_id = c.id
        WHERE f.id = $product_id
    ";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);

    if ($product) {
        $category_name = $product['category_name'];
        // Fetch product features (selling types)
        $sql_features = "
            SELECT st.name
            FROM selling_types st
            JOIN fruit_selling_types fst ON st.id = fst.selling_type_id
            WHERE fst.fruit_id = $product_id
        ";
        $result_features = mysqli_query($conn, $sql_features);
        while ($row = mysqli_fetch_assoc($result_features)) {
            $features[] = $row['name'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Tropical Fruit Pulps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Assuming a general stylesheet -->
    <style>
        /* General Styles (Kept from original, assuming they are in a common file or block) */
        /* ... [Your existing general CSS, like .products-hero, .header-nav, footer styles, etc.] ... */

        /* === Product Details Page Specific Styles === */
        .product-detail-section {
            padding: 4rem 2rem;
            background: #fff;
        }

        .product-detail-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .product-detail-layout {
            display: grid;
            grid-template-columns: 1fr 1.5fr; /* Image on left, details on right */
            gap: 3rem;
        }

        .product-gallery {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .product-main-image {
            width: 100%;
            height: 450px; /* Adjusted height for detail view */
            object-fit: cover;
            background: #f3f4f6;
            display: block;
        }

        .product-info {
            padding: 1rem 0;
        }

        .product-info h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .product-category {
            font-size: 1rem;
            color: #3e940f;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .product-price {
            font-size: 2.2rem;
            font-weight: 700;
            color: #eb7c12;
            margin-bottom: 2rem;
            display: block;
        }

        .product-description-full {
            font-size: 1.1rem;
            color: #374151;
            line-height: 1.8;
            margin-bottom: 3rem;
            border-top: 1px solid #e5e7eb;
            padding-top: 2rem;
        }

        /* Feature List Styling */
        .features-list-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .features-list-section h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1.5rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            background: #f9fafb;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .feature-icon {
            font-size: 1.5rem;
            color: #3e940f;
            margin-right: 1rem;
        }

        .feature-text {
            font-weight: 600;
            color: #374151;
        }

        /* CTA button group for details page */
        .product-actions-detail {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-add-to-cart {
            flex: 2;
            padding: 1rem 2rem;
            background: linear-gradient(to right, #3e940f, #2d7a0c);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-add-to-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(62, 148, 15, 0.4);
        }

        .btn-request-sample {
            flex: 1;
            padding: 1rem 2rem;
            background: #fff;
            color: #3e940f;
            border: 2px solid #3e940f;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-request-sample:hover {
            background: #3e940f;
            color: #fff;
        }

        .not-found-message {
            text-align: center;
            padding: 8rem 2rem;
            font-size: 1.5rem;
            color: #6b7280;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .product-detail-layout {
                grid-template-columns: 1fr;
            }
            .product-main-image {
                height: 350px;
            }
        }
        @media (max-width: 576px) {
            .product-info h1 {
                font-size: 2rem;
            }
            .product-price {
                font-size: 1.8rem;
            }
            .product-actions-detail {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header>
        <!-- Assuming a common header is included here, or structure is duplicated -->
    </header>

    <nav class="breadcrumb-nav">
        <div class="breadcrumb-content">
            <ol class="breadcrumb" id="productBreadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="index.php">Products</a></li>
                <?php if ($product): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
                <?php endif; ?>
            </ol>
        </div>
    </nav>

    <section class="product-detail-section">
        <?php if ($product): ?>
            <div class="product-detail-container" style="display: block;">
                <div class="product-detail-layout">
                    <div class="product-gallery">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-main-image">
                    </div>

                    <div class="product-info">
                        <div class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></div>
                        <h1><?php echo htmlspecialchars($product['name']); ?></h1>

                        <span class="product-price">Contact for Price</span>

                        <div class="product-actions-detail">
                            <button class="btn-add-to-cart">Add to Order</button>
                            <button class="btn-request-sample">Request Sample</button>
                        </div>

                        <p class="product-description-full"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

                        <?php if (!empty($features)): ?>
                        <div class="features-list-section">
                            <h2>Key Product Features</h2>
                            <div class="features-grid">
                                <?php foreach ($features as $feature): ?>
                                    <div class="feature-item">
                                        <span class="feature-icon">★</span>
                                        <span class="feature-text"><?php echo htmlspecialchars($feature); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="not-found-message">
                Product not found. Please check the URL or <a href="index.php">return to all products</a>.
            </div>
        <?php endif; ?>
    </section>

    <footer>
        <!-- Assuming a common footer is included here -->
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
