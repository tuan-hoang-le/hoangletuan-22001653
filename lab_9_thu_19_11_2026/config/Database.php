<?php
/* 
 * Database connection manager using PDO.
 */
class Database {
    // Database connection credentials.
    private static string $host = '127.0.0.1';
    private static string $dbName = 'product_management';
    private static string $username = 'root';
    private static string $password = '';
    private static ?PDO $connection = null;

    /* 
     * Return a singleton PDO connection instance.
     */
    public static function getConnection(): PDO {
        if (self::$connection === null) {
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbName . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$connection = new PDO($dsn, self::$username, self::$password, $options);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Database connection failed: ' . $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        return self::$connection;
    }
}
?>