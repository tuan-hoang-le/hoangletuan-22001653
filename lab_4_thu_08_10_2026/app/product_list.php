<?php
require_once 'model/product.php';

$errorMessage = '';
$products = [];

try {
    // Fetch all available products from the database.
    $products = getAllProducts();
} catch (Exception $e) {
    $errorMessage = "Failed to load products: " . $e->getMessage();
}

include 'view/header.php';
?>

<h2>Product list</h2>

<?php if ($errorMessage): ?>
    <p class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Product name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($product['id']); ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($product['name']); ?>
                    </td>
                    <td>
                        $<?php echo number_format($product['price'], 2); ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($product['quantity']); ?>
                    </td>
                    <td>
                        <a href="product_edit.php?id=<?php echo urlencode($product['id']); ?>" class="btn">Edit</a>
                        <a href="product_delete.php?id=<?php echo urlencode($product['id']); ?>" class="btn">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No products found in the database.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'view/footer.php'; ?>