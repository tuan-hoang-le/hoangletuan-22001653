<?php
require_once 'model/product.php';

$errors = [];
$successMessage = '';
$product = null;
$id = $_GET['id'] ?? null;

if ($id) {
    $product = getProductById($id);
}

if (!$product) {
    die("Product not found. Please verify the product ID.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    if (empty($name)) {
        $errors[] = "Product name cannot be empty.";
    }

    if (!is_numeric($price) || $price <= 0) {
        $errors[] = "Price must be greater than 0.";
    }

    if (!is_numeric($quantity) || $quantity < 0) {
        $errors[] = "Quantity must be greater than or equal to 0.";
    }

    if (empty($errors)) {
        if (updateProduct($id, $name, (float) $price, (int) $quantity)) {
            $successMessage = "Product details updated successfully.";
            $product = getProductById($id);
        } else {
            $errors[] = "Failed to update product details.";
        }
    }
}

include 'view/header.php';
?>

<h2>Edit product</h2>

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
        <button type="submit">Update product</button>
    </p>
</form>

<?php include 'view/footer.php'; ?>