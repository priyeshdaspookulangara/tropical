<?php
$page_title = 'Our Products';
require_once __DIR__ . '/includes/header.php';

// Fetch all categories and their fruits
$sql = "
    SELECT
        c.id as category_id,
        c.name as category_name,
        f.id as fruit_id,
        f.name as fruit_name,
        f.image as fruit_image
    FROM categories c
    LEFT JOIN fruits f ON c.id = f.category_id
    ORDER BY c.name, f.name
";
$result = mysqli_query($conn, $sql);

$categories = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categories[$row['category_name']]['id'] = $row['category_id'];
    if ($row['fruit_id']) {
        $categories[$row['category_name']]['fruits'][] = [
            'id' => $row['fruit_id'],
            'name' => $row['fruit_name'],
            'image' => $row['fruit_image']
        ];
    }
}
?>

<main class="container mt-4">
    <?php foreach ($categories as $category_name => $category_data): ?>
        <section class="mb-5">
            <h2><?php echo htmlspecialchars($category_name); ?></h2>
            <hr>
            <div class="row">
                <?php if (!empty($category_data['fruits'])): ?>
                    <?php foreach ($category_data['fruits'] as $fruit): ?>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card h-100">
                                <img src="<?php echo htmlspecialchars($fruit['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($fruit['name']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($fruit['name']); ?></h5>
                                    <a href="product.php?id=<?php echo $fruit['id']; ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No products in this category yet.</p>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
