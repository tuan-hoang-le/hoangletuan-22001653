<?php
require_once 'model/product.php';

$error = '';
$product = null;
$id = $_GET['id'] ?? null;

if ($id) {
    $product = getProductById($id);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        if ($product) {
            if (deleteProduct($id)) {
                header("Location: product_list.php");
                exit;
            } else {
                $error = "Failed to delete the product due to a system error.";
            }
        } else {
            $error = "The requested product does not exist.";
        }
    } else {
        header("Location: product_list.php");
        exit;
    }
}

include 'view/header.php';
?>

<h2>Delete product</h2>

<?php if ($error): ?>
    <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <a href="product_list.php" class="btn">Back to product list</a>
<?php elseif (!$product): ?>
    <p class="error">The product you are trying to delete does not exist or has already been removed.</p>
    <a href="product_list.php" class="btn">Back to product list</a>
<?php else: ?>
    <p>Are you certain you want to permanently delete the product: <strong><?php echo htmlspecialchars($product['name']); ?></strong>?</p>

    <form method="post" action="">
        <button type="submit" name="confirm" value="yes" style="color: #ff0000;">Yes, delete</button>
        <button type="submit" name="confirm" value="no">Cancel</button>
    </form>
<?php endif; ?>

<?php include 'view/footer.php'; ?>