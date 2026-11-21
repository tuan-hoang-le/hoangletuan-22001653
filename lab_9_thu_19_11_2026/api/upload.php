<?php
// Set response headers for JSON and cross-origin handling.
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Respond early to preflight requests.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Include database connection class.
require_once __DIR__ . '/../config/Database.php';

// Maximum allowed image size: 2 MB (2 * 1024 * 1024 bytes).
const MAX_IMAGE_SIZE = 2097152;

// Allowed MIME types and extensions map.
const ALLOWED_MIME_TYPES = [
    'image/jpeg' => ['jpg', 'jpeg'],
    'image/png' => ['png']
];

/* 
 * Send a standardized JSON response and terminate script execution.
 */
function sendJsonResponse(int $statusCode, array $payload): void {
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/* 
 * Find product by ID and return its record, or null if not found.
 */
function getProductById(PDO $pdo, int $productId): ?array {
    $stmt = $pdo->prepare('SELECT id, image FROM products WHERE id = :id');
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch();

    return $product !== false ? $product : null;
}

/* 
 * Validate uploaded image file (upload status, 2 MB limit, actual MIME and image content).
 */
function validateUploadedImage(?array $file): array {
    if ($file === null || !isset($file['error'])) {
        return [false, 'Vui lòng đính kèm tệp ảnh cần tải lên.', ''];
    }

    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        return [false, 'Dung lượng ảnh vượt quá giới hạn tối đa 2 MB.', ''];
    }

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return [false, 'Không có tệp ảnh nào được chọn.', ''];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [false, 'Xảy ra lỗi trong quá trình tải tệp lên máy chủ.', ''];
    }

    $fileSize = (int) ($file['size'] ?? 0);

    if ($fileSize <= 0) {
        return [false, 'Tệp ảnh rỗng hoặc không hợp lệ.', ''];
    }

    if ($fileSize > MAX_IMAGE_SIZE) {
        return [false, 'Dung lượng ảnh không vượt quá 2 MB.', ''];
    }

    $tmpPath = $file['tmp_name'] ?? '';

    if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
        return [false, 'Tệp tải lên không hợp lệ.', ''];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $actualMime = (string) $finfo->file($tmpPath);

    if (!array_key_exists($actualMime, ALLOWED_MIME_TYPES)) {
        return [false, 'Loại tệp không hợp lệ. Chỉ chấp nhận ảnh JPG, JPEG hoặc PNG.', ''];
    }

    $imageInfo = @getimagesize($tmpPath);

    if ($imageInfo === false || !in_array($imageInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
        return [false, 'Nội dung tệp không phải là định dạng ảnh JPG, JPEG hoặc PNG hợp lệ.', ''];
    }

    $originalExtension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
    $allowedExtensions = ALLOWED_MIME_TYPES[$actualMime];
    $safeExtension = in_array($originalExtension, $allowedExtensions, true)
        ? $originalExtension
        : $allowedExtensions[0];

    return [true, '', $safeExtension];
}

/* 
 * Ensure the 'uploads' directory exists and return its absolute path.
 */
function ensureUploadsDirectory(): string {
    $uploadDir = __DIR__ . '/../uploads';

    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            sendJsonResponse(500, [
                'success' => false,
                'message' => 'Không thể khởi tạo thư mục lưu trữ ảnh uploads.'
            ]);
        }
    }

    return $uploadDir;
}

/* 
 * Generate a collision-free filename for the uploaded product image.
 */
function generateUniqueFileName(int $productId, string $extension): string {
    $randomSuffix = bin2hex(random_bytes(5));

    return sprintf('product_%d_%s_%s.%s', $productId, date('YmdHis'), $randomSuffix, $extension);
}

/* 
 * Remove the previous image file from disk after a new image is saved.
 */
function deleteOldImageFile(?string $oldRelativePath): void {
    if (empty($oldRelativePath)) {
        return;
    }

    $oldFullPath = __DIR__ . '/../' . ltrim($oldRelativePath, '/\\');

    if (is_file($oldFullPath)) {
        @unlink($oldFullPath);
    }
}

/* 
 * Handle POST request to upload and attach an image to an existing product.
 */
function handleUploadProductImage(PDO $pdo): void {
    if (empty($_FILES) && empty($_POST) && isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > 0) {
        sendJsonResponse(400, [
            'success' => false,
            'message' => 'Dung lượng tệp tải lên vượt quá giới hạn tối đa 2 MB.'
        ]);
    }

    $rawProductId = $_POST['id'] ?? $_POST['product_id'] ?? $_GET['id'] ?? null;
    $productId = filter_var($rawProductId, FILTER_VALIDATE_INT);

    if (!$productId || $productId <= 0) {
        sendJsonResponse(400, [
            'success' => false,
            'message' => 'Mã sản phẩm không hợp lệ.'
        ]);
    }

    $product = getProductById($pdo, $productId);

    if ($product === null) {
        sendJsonResponse(404, [
            'success' => false,
            'message' => 'Sản phẩm không tồn tại trong hệ thống.'
        ]);
    }

    $uploadedFile = $_FILES['image'] ?? $_FILES['file'] ?? null;
    [$isValid, $errorMessage, $safeExtension] = validateUploadedImage($uploadedFile);

    if (!$isValid) {
        sendJsonResponse(400, [
            'success' => false,
            'message' => $errorMessage
        ]);
    }

    $uploadDir = ensureUploadsDirectory();
    $uniqueFileName = generateUniqueFileName($productId, $safeExtension);
    $destinationPath = $uploadDir . '/' . $uniqueFileName;
    $relativePath = 'uploads/' . $uniqueFileName;

    if (!move_uploaded_file($uploadedFile['tmp_name'], $destinationPath)) {
        sendJsonResponse(500, [
            'success' => false,
            'message' => 'Không thể lưu tệp ảnh vào thư mục uploads.'
        ]);
    }

    try {
        $stmt = $pdo->prepare('UPDATE products SET image = :image WHERE id = :id');
        $stmt->execute([
            ':image' => $relativePath,
            ':id' => $productId
        ]);
        
        deleteOldImageFile($product['image'] ?? null);

        sendJsonResponse(200, [
            'success' => true,
            'message' => 'Tải ảnh sản phẩm lên thành công.',
            'image' => $relativePath
        ]);
    } catch (PDOException $e) {
        if (is_file($destinationPath)) {
            @unlink($destinationPath);
        }

        sendJsonResponse(500, [
            'success' => false,
            'message' => 'Lỗi cập nhật đường dẫn ảnh vào cơ sở dữ liệu: ' . $e->getMessage()
        ]);
    }
}

// -----------------
// ROUTER DISPATCHER
// -----------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(405, [
        'success' => false,
        'message' => 'API upload chỉ hỗ trợ phương thức POST.'
    ]);
}

$pdo = Database::getConnection();
handleUploadProductImage($pdo);
?>