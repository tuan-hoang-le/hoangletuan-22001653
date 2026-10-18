<?php
require_once 'model/product.php';
require_once 'model/user.php';

// Access control: only authenticated users are permitted to add products.
requireLogin();

$errors = [];
$successMessage = '';
$name = '';
$price = '';
$quantity = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');
    $errors = validateProductInput($name, $price, $quantity);

    if (empty($errors)) {
        try {
            if (addProduct($name, (float) $price, (int) $quantity)) {
                $successMessage = "Product added successfully.";
                $name = '';
                $price = '';
                $quantity = '';
            } else {
                $errors[] = "Failed to add the product. Please try again later.";
            }
        } catch (Exception $e) {
            $errors[] = "An unexpected error occurred: " . $e->getMessage();
        }
    }
}

include 'view/header.php';
?>

<h2>Add product</h2>

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
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
    </p>
    <p>
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>">
    </p>
    <p>
        <label for="quantity">Quantity:</label><br>
        <input type="text" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>">
    </p>
    <p>
        <button type="submit" class="btn">Add product</button>
        <a href="product_list.php" class="btn">Cancel</a>
    </p>
</form>

<?php include 'view/footer.php'; ?>