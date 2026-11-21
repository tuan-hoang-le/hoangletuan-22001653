-- 1. Select the target database.
USE product_management;

-- 2. Add the nullable 'image' column to the existing 'products' table.
ALTER TABLE products
ADD COLUMN image VARCHAR(255) NULL DEFAULT NULL AFTER stock;

-- 3. Insert 9 additional sample products to reach 12 products in total.
INSERT INTO products (name, price, stock, image) VALUES
('Bút chì 2B', 4000, 50, NULL),
('Tẩy', 3000, 40, NULL),
('Băng xóa', 18000, 10, NULL),
('Bút cảm ứng', 215000, 5, NULL),
('Hộp bút chì', 35000, 12, NULL),
('Kéo', 22000, 8, NULL),
('Keo thỏi', 10000, 30, NULL),
('Giấy ghi chú dán', 14000, 45, NULL),
('Folder hồ sơ A4', 25000, 18, NULL);

-- 4. (Verify) Check the updated table structure and records.
SELECT * FROM products ORDER BY id DESC;