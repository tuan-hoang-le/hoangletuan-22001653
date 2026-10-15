<?php
require_once 'model/product.php';

$errors = [];
$successMessage = '';
$product = null;
$rawId = $_GET['id'] ?? null;
$id = validateProductId($rawId);

if ($id !== false) {
    $product = getProductById($id);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!$product) {
        $errors[] = "Cannot update a non-existent product.";
    } else {
        $name = trim($_POST['name'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $quantity = trim($_POST['quantity'] ?? '');
        $errors = validateProductInput($name, $price, $quantity);

        if (empty($errors)) {
            try {
                if (updateProduct($id, $name, (float) $price, (int) $quantity)) {
                    $successMessage = "Product details updated successfully.";
                    $product = getProductById($id);
                } else {
                    $errors[] = "Failed to update product details. Please try again.";
                }
            } catch (Exception $e) {
                $errors[] = "An unexpected error occurred: " . $e->getMessage();
            }
        }
    }
}

include 'view/header.php';
?>

<h2>Edit product</h2>

<?php if (!$product): ?>
    <div class="error">
        <p>Product not found or invalid product ID.</p>
    </div>
    <a href="product_list.php" class="btn">Back to product list</a>
<?php else: ?>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($successMessage): ?>
        <div class="success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <p>
            <label for="name">Product name:</label><br>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? $product['name']); ?>">
        </p>
        <p>
            <label for="price">Price:</label><br>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($_POST['price'] ?? $product['price']); ?>">
        </p>
        <p>
            <label for="quantity">Quantity:</label><br>
            <input type="text" id="quantity" name="quantity" value="<?php echo htmlspecialchars($_POST['quantity'] ?? $product['quantity']); ?>">
        </p>
        <p>
            <button type="submit" class="btn">Update product</button>
            <a href="product_list.php" class="btn">Cancel</a>
        </p>
    </form>
<?php endif; ?>

<?php include 'view/footer.php'; ?>