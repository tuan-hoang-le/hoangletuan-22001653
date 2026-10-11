<?php
require_once 'model/product.php';

$errors = [];
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    if (empty($name)) {
        $errors[] = "Product name cannot be empty.";
    }

    if (!is_numeric($price) || $price <= 0) {
        $errors[] = "Price must be a number greater than 0.";
    }

    if (!is_numeric($quantity) || $quantity < 0) {
        $errors[] = "Quantity must be greater than or equal to 0.";
    }

    if (empty($errors)) {
        if (addProduct($name, (float) $price, (int) $quantity)) {
            $successMessage = "Product added successfully.";
            $name = $price = $quantity = '';
        } else {
            $errors[] = "Failed to add the product. Please try again later.";
        }
    }
}

include 'view/header.php';
?>

<h2>Add product</h2>

<?php if (!empty($error)): ?>
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
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>">
    </p>
    <p>
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price ?? ''); ?>">
    </p>
    <p>
        <label for="quantity">Quantity:</label><br>
        <input type="text" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity ?? ''); ?>">
    </p>
    <p>
        <button type="submit">Add product</button>
    </p>
</form>

<?php include 'view/footer.php'; ?>