<?php
// Set response headers for JSON and cross-origin handling.
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Respond early to preflight requests.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Include database connection class.
require_once __DIR__ . '/../config/Database.php';

/* 
 * Send a standardized JSON response and terminate script execution.
 */
function sendJsonResponse(int $statusCode, array $payload): void {
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/* 
 * Validate product payload for insert and update operations.
 */
function validateProductData($data): array {
    if (!is_array($data)) {
        return [false, 'Dữ liệu gửi lên không đúng định dạng JSON.'];
    }

    if (!isset($data['name'])) {
        return [false, 'Tên sản phẩm là bắt buộc.'];
    }

    $trimmedName = trim((string) $data['name']);
    $nameLength = mb_strlen($trimmedName, 'UTF-8');

    if ($nameLength < 1 || $nameLength > 100) {
        return [false, 'Tên sản phẩm phải có độ dài từ 1 đến 100 ký tự.'];
    }

    if (!isset($data['price']) || filter_var($data['price'], FILTER_VALIDATE_INT) === false) {
        return [false, 'Đơn giá phải là số nguyên hợp lệ.'];
    }

    if ((int) $data['price'] <= 0) {
        return [false, 'Đơn giá phải là số nguyên lớn hơn 0.'];
    }

    if (!isset($data['stock']) || filter_var($data['stock'], FILTER_VALIDATE_INT) === false) {
        return [false, 'Số lượng tồn phải là số nguyên hợp lệ.'];
    }

    if ((int) $data['stock'] < 0) {
        return [false, 'Số lượng tồn kho phải là số nguyên không âm.'];
    }

    return [true, ''];
}

/* 
 * Verify whether a product exists in the database by its ID.
 */
function productExists(PDO $pdo, int $id): bool {
    $stmt = $pdo->prepare('SELECT id FROM products WHERE id = :id');
    $stmt->execute([':id' => $id]);

    return $stmt->fetch() !== false;
}

/* 
 * Return all products (GET request).
 */
function handleGetProducts(PDO $pdo): void {
    try {
        $stmt = $pdo->query('SELECT id, name, price, stock FROM products ORDER BY id DESC');
        $products = $stmt->fetchAll();
        sendJsonResponse(200, [
            'success' => true,
            'data' => $products
        ]);
    } catch (PDOException $e) {
        sendJsonResponse(500, [
            'success' => false,
            'message' => 'Lỗi khi truy vấn dữ liệu: ' . $e->getMessage()
        ]);
    }
}

/* 
 * Insert a new product (POST request).
 */
function handleCreateProduct(PDO $pdo, $data): void {
    [$isValid, $errorMessage] = validateProductData($data);

    if (!$isValid) {
        sendJsonResponse(400, [
            'success' => false,
            'message' => $errorMessage
        ]);
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)');
        $stmt->execute([
            ':name' => trim((string) $data['name']),
            ':price' => (int) $data['price'],
            ':stock' => (int) $data['stock']
        ]);

        sendJsonResponse(201, [
            'success' => true,
            'message' => 'Thêm sản phẩm thành công.',
            'id' => (int) $pdo->lastInsertId()
        ]);
    } catch (PDOException $e) {
        sendJsonResponse(500, [
            'success' => false,
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
        ]);
    }
}

/* 
 * Update an existing product by ID from query string (PUT request).
 */
function handleUpdateProduct(PDO $pdo, $id, $data): void {
    $productId = filter_var($id, FILTER_VALIDATE_INT);

    if (!$productId || $productId <= 0) {
        sendJsonResponse(400, [
            'success' => false,
            'message' => 'Mã sản phẩm trên URL không hợp lệ.'
        ]);
    }

    if (!productExists($pdo, $productId)) {
        sendJsonResponse(404, [
            'success' => false,
            'message' => 'Sản phẩm cần sửa không tồn tại.'
        ]);
    }

    [$isValid, $errorMessage] = validateProductData($data);

    if (!$isValid) {
        sendJsonResponse(400, [
            'success' => false,
            'message' => $errorMessage
        ]);
    }

    try {
        $stmt = $pdo->prepare('UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id');
        $stmt->execute([
            ':name' => trim((string) $data['name']),
            ':price' => (int) $data['price'],
            ':stock' => (int) $data['stock'],
            ':id' => $productId
        ]);

        sendJsonResponse(200, [
            'success' => true,
            'message' => 'Cập nhật sản phẩm thành công.'
        ]);
    } catch (PDOException $e) {
        sendJsonResponse(500, [
            'success' => false,
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
        ]);
    }
}

/* 
 * Delete a product by ID from query string (DELETE request).
 */
function handleDeleteProduct(PDO $pdo, $id): void {
    $productId = filter_var($id, FILTER_VALIDATE_INT);

    if (!$productId || $productId <= 0) {
        sendJsonResponse(400, [
            'success' => false,
            'message' => 'Mã sản phẩm trên URL không hợp lệ.'
        ]);
    }

    if (!productExists($pdo, $productId)) {
        sendJsonResponse(404, [
            'success' => false,
            'message' => 'Sản phẩm cần xóa không tồn tại.'
        ]);
    }

    try {
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute([':id' => $productId]);

        sendJsonResponse(200, [
            'success' => true,
            'message' => 'Xóa sản phẩm thành công.'
        ]);
    } catch (PDOException $e) {
        sendJsonResponse(500, [
            'success' => false,
            'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
        ]);
    }
}

// -----------------
// ROUTER DISPATCHER
// -----------------
$pdo = Database::getConnection();
$requestMethod = $_SERVER['REQUEST_METHOD'];
$productId = $_GET['id'] ?? null;
$rawInput = file_get_contents('php://input');
$requestData = json_decode($rawInput, true);

switch ($requestMethod) {
    case 'GET':
        handleGetProducts($pdo);
        break;

    case 'POST':
        handleCreateProduct($pdo, $requestData);
        break;

    case 'PUT':
        handleUpdateProduct($pdo, $productId, $requestData);
        break;

    case 'DELETE':
        handleDeleteProduct($pdo, $productId);
        break;

    default:
        sendJsonResponse(405, [
            'success' => false,
            'message' => 'Phương thức HTTP không được hỗ trợ.'
        ]);
        break;
}
?>