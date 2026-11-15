-- 1. Create the database.
CREATE DATABASE IF NOT EXISTS product_management;

-- Switch to the newly created database.
USE product_management;

-- 2. Create table 'products'.
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price INT NOT NULL CHECK (price > 0),
    stock INT NOT NULL DEFAULT 0 CHECK (stock >= 0)
);

-- Insert 3 sample products into the 'products' table.
INSERT INTO products (name, price, stock) VALUES
('Bút bi', 5000, 20),
('Vở ô ly', 12000, 15),
('Thước kẻ', 8000, 0);

-- 3. (Verify) Query the list of newly added records.
SELECT * FROM products;