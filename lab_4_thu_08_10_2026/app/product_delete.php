<?php
require_once 'model/product.php';

$error = '';
$product = null;
$rawId = $_GET['id'] ?? null;
$id = validateProductId($rawId);

if ($id === false) {
    $error = "Invalid product ID.";
} else {
    $product = getProductById($id);

    if (!$product) {
        $error = "The product you are trying to delete does not exist or has already been removed.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        if ($product && $id !== false) {
            try {
                if (deleteProduct($id)) {
                    header("Location: product_list.php");
                    exit;
                } else {
                    $error = "Failed to delete the product due to a database error.";
                }
            } catch (Exception $e) {
                $error = "An unexpected error occurred while deleting: " . $e->getMessage();
            }
        }
    } else {
        header("Location: product_list.php");
        exit;
    }
}

include 'view/header.php';
?>

<h2>Delete product</h2>

<?php if ($error || !$product): ?>
    <div class="error">
        <p><?php echo htmlspecialchars($error ?: 'The product you are trying to delete does not exist or has already been removed.'); ?></p>
    </div>
    <a href="product_list.php" class="btn">Back to product list</a>
<?php else: ?>
    <p>Are you certain you want to permanently delete the product: <strong><?php echo htmlspecialchars($product['name']); ?></strong>?</p>

    <form method="post" action="">
        <button type="submit" name="confirm" value="yes" style="color: #ff0000;">Yes, delete</button>
        <button type="submit" name="confirm" value="no">Cancel</button>
    </form>
<?php endif; ?>

<?php include 'view/footer.php'; ?>