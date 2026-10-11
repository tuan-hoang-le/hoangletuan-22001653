<?php
require_once __DIR__ . '/../common/dbConnect.php';

/* 
 * Retrieve all products from the database.
 */
function getAllProducts() {
    global $conn;
    $products = [];
    $query = "SELECT * FROM " . TABLE_PRODUCTS . " ORDER BY id ASC";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    return $products;
}

/* 
 * Retrieve a specific product by its ID.
 */
function getProductById($id) {
    global $conn;
    $query = "SELECT * FROM " . TABLE_PRODUCTS . " WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

/* 
 * Add a new product to the database.
 */
function addProduct($name, $price, $quantity) {
    global $conn;
    $query = "INSERT INTO " . TABLE_PRODUCTS . " (name, price, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdi", $name, $price, $quantity);

    return $stmt->execute();
}

/* 
 * Update the details of an existing product.
 */
function updateProduct($id, $name, $price, $quantity) {
    global $conn;
    $query = "UPDATE " . TABLE_PRODUCTS . " SET name = ?, price = ?, quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdii", $name, $price, $quantity, $id);

    return $stmt->execute();
}

/* 
 * Delete a product by its ID.
 */
function deleteProduct($id) {
    global $conn;
    $query = "DELETE FROM " . TABLE_PRODUCTS . " WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}
?>