<?php
require_once 'model/product.php';

// Fetch all available products.
$products = getAllProducts();

include 'view/header.php';
?>

<h2>Product list</h2>

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
                        <a href="product_edit.php?id=<?php echo $product['id']; ?>" class="btn">Edit</a>
                        <a href="product_delete.php?id=<?php echo $product['id']; ?>" class="btn">Delete</a>
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