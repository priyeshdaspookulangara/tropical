<?php
$page_title = 'Our Products';
require_once __DIR__ . '/includes/header.php';

// Fetch categories and their product counts
$sql = "
    SELECT c.id, c.name, COUNT(f.id) as fruit_count
    FROM categories c
    LEFT JOIN fruits f ON c.id = f.category_id
    GROUP BY c.id, c.name
    ORDER BY c.name
";
$result = mysqli_query($conn, $sql);
$categories = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

// Fetch all fruits with their categories
$sql = "
    SELECT f.*, c.name as category_name
    FROM fruits f
    JOIN categories c ON f.category_id = c.id
    ORDER BY f.name
";
$result = mysqli_query($conn, $sql);
$fruits = [];
while ($row = mysqli_fetch_assoc($result)) {
    $fruits[] = $row;
}
?>
<link rel="stylesheet" href="assets/css/products.css">

<section class="products-hero">
    <div class="products-hero-content">
        <h1>Our Premium Fruit Pulps</h1>
        <p>Discover our extensive collection of high-quality tropical fruit pulps, sourced from the finest farms worldwide</p>
    </div>
</section>

<nav class="breadcrumb-nav">
    <div class="breadcrumb-content">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Products</li>
        </ol>
    </div>
</nav>

<section class="products-section">
    <div class="products-container">
        <div class="products-layout">
            <!-- Sidebar Filters -->
            <aside class="products-sidebar">
                <div class="filter-section">
                    <h3>Categories</h3>
                    <?php foreach ($categories as $category): ?>
                        <div class="filter-option">
                            <input type="checkbox" id="cat-<?php echo $category['id']; ?>" checked>
                            <label for="cat-<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></label>
                            <span class="filter-count">(<?php echo $category['fruit_count']; ?>)</span>
                        </div>
                    <?php endforeach; ?>
                    <a href="#" class="clear-filters">Clear All</a>
                </div>

                <div class="filter-section">
                    <h3>Packaging Size</h3>
                    <div class="filter-option">
                        <input type="checkbox" id="size-small">
                        <label for="size-small">Small (1-5kg)</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="size-medium">
                        <label for="size-medium">Medium (5-25kg)</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="size-bulk">
                        <label for="size-bulk">Bulk (25kg+)</label>
                    </div>
                </div>

                <div class="filter-section">
                    <h3>Features</h3>
                    <div class="filter-option">
                        <input type="checkbox" id="feat-organic">
                        <label for="feat-organic">Organic</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="feat-nongmo">
                        <label for="feat-nongmo">Non-GMO</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="feat-nosugar">
                        <label for="feat-nosugar">No Added Sugar</label>
                    </div>
                    <div class="filter-option">
                        <input type="checkbox" id="feat-preservative">
                        <label for="feat-preservative">Preservative-Free</label>
                    </div>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="products-main">
                <div class="products-header">
                    <div class="products-count">
                        Showing <strong>1-12</strong> of <strong>36</strong> products
                    </div>
                    <div class="sort-dropdown">
                        <label for="sort">Sort by:</label>
                        <select id="sort">
                            <option value="featured">Featured</option>
                            <option value="name-asc">Name (A-Z)</option>
                            <option value="name-desc">Name (Z-A)</option>
                            <option value="popular">Most Popular</option>
                            <option value="newest">Newest</option>
                        </select>
                    </div>
                </div>

                <div class="products-grid" id="productsGrid">
                    <?php foreach ($fruits as $fruit): ?>
                        <div class="product-card">
                            <img src="<?php echo htmlspecialchars($fruit['image']); ?>" alt="<?php echo htmlspecialchars($fruit['name']); ?> Pulp" class="product-image">
                            <div class="product-content">
                                <div class="product-category"><?php echo htmlspecialchars($fruit['category_name']); ?></div>
                                <h3 class="product-title"><?php echo htmlspecialchars($fruit['name']); ?> Pulp</h3>
                                <p class="product-description"><?php echo htmlspecialchars(substr($fruit['description'], 0, 100)); ?>...</p>
                                <div class="product-actions">
                                    <a href="product.php?id=<?php echo $fruit['id']; ?>" class="btn-view">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="pagination-section">
                    <div class="pagination">
                        <a href="#" class="pagination-btn disabled">‹</a>
                        <a href="#" class="pagination-btn active">1</a>
                        <a href="#" class="pagination-btn">2</a>
                        <a href="#" class="pagination-btn">3</a>
                        <a href="#" class="pagination-btn">›</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
