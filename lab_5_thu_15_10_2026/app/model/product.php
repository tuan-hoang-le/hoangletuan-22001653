<?php
require_once __DIR__ . '/../common/dbConnect.php';

/* 
 * Validate that a given ID is a valid positive integer.
 */
function validateProductId($id) {
    return filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
}

/* 
 * Validate product form inputs according to business rules and schema limits.
 */
function validateProductInput($name, $price, $quantity) {
    $errors = [];

    if ($name === '') {
        $errors[] = "Product name cannot be empty.";
    } elseif (mb_strlen($name) > 100) {
        $errors[] = "Product name must not exceed 100 characters.";
    }

    if ($price === '' || !is_numeric($price) || (float) $price <= 0) {
        $errors[] = "Price must be a number greater than 0";
    } elseif ((float) $price > 99999999.99) {
        $errors[] = "Price cannot exceed 99999999.99";
    }

    if ($quantity === '' || filter_var($quantity, FILTER_VALIDATE_INT) === false) {
        $errors[] = "Quantity must be a valid whole integer.";
    } elseif ((int) $quantity < 0) {
        $errors[] = "Quantity must be greater than or equal to 0";
    } elseif ((int) $quantity > 2147483647) {
        $errors[] = "Quantity cannot exceed 2147483647";
    }

    return $errors;
}

/* 
 * Retrieve all products ordered by ID ascending.
 */
function getAllProducts() {
    global $conn;
    $products = [];
    $query = "SELECT id, name, price, quantity FROM " . TABLE_PRODUCTS . " ORDER BY id ASC";
    
    try {
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
    } catch (mysqli_sql_exception $e) {
        error_log("Error retrieving all products: " . $e->getMessage());
    }

    return $products;
}

/* 
 * Retrieve a single product by its ID.
 */
function getProductById($id) {
    global $conn;
    $validId = validateProductId($id);

    if ($validId === false) {
        return null;
    }

    $query = "SELECT id, name, price, quantity FROM " . TABLE_PRODUCTS . " WHERE id = ?";

    try {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $validId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $stmt->close();

            return $product;
        }

        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        error_log("Error retrieving product by ID " . $validId . ": " . $e->getMessage());
    }

    return null;
}

/* 
 * Add a new product to the database.
 */
function addProduct($name, $price, $quantity) {
    global $conn;
    $query = "INSERT INTO " . TABLE_PRODUCTS . " (name, price, quantity) VALUES (?, ?, ?)";
    
    try {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdi", $name, $price, $quantity);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    } catch (mysqli_sql_exception $e) {
        error_log("Error adding product: " . $e->getMessage());

        return false;
    }
}

/* 
 * Update the details of an existing product.
 */
function updateProduct($id, $name, $price, $quantity) {
    global $conn;
    $validId = validateProductId($id);

    if ($validId === false) {
        return false;
    }

    $query = "UPDATE " . TABLE_PRODUCTS . " SET name = ?, price = ?, quantity = ? WHERE id = ?";
    
    try {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdii", $name, $price, $quantity, $validId);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    } catch (mysqli_sql_exception $e) {
        error_log("Error updating product ID " . $validId . ": " . $e->getMessage());

        return false;
    }
}

/* 
 * Delete a product from the database by its ID.
 */
function deleteProduct($id) {
    global $conn;
    $validId = validateProductId($id);

    if ($validId === false) {
        return false;
    }

    $query = "DELETE FROM " . TABLE_PRODUCTS . " WHERE id = ?";

    try {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $validId);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    } catch (mysqli_sql_exception $e) {
        error_log("Error deleting product ID " . $validId . ": " . $e->getMessage());

        return false;
    }
}
?>