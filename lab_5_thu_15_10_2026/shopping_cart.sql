-- 1. Create the database.
CREATE DATABASE IF NOT EXISTS shopping_cart;

-- Switch to the newly created database.
USE shopping_cart;

-- 2. Create table 'products'.
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

-- Insert at least 5 sample products into the 'products' table.
INSERT INTO products (name, price, quantity) VALUES
('Apple MacBook Pro 14 M3', 1999.00, 15),
('LG UltraFine 27-inch 4K Monitor', 599.99, 20),
('Apple iPad Pro 11-inch M4', 999.00, 35),
('Bose QuietComfort Ultra Headphones', 429.00, 25),
('Samsung T7 Shield 2TB Portable SSD', 179.99, 60),
('Sony PlayStation 5 Slim Console', 499.99, 18);

-- 3. Create table 'users'.
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Insert sample accounts with passwords hashed.
-- Account 1: username = 'admin', plain password = 'Admin@123'
-- Account 2: username = 'john_doe', plain password = 'User@123'
INSERT INTO users (username, password) VALUES
('admin', '$2y$10$V3Icsgx2CWTZo5NdenVzbuFeEdH5oHFgA1NndtJAvPH1GNv1PO9SG'),
('john_doe', '$2y$10$eUzu65Lvm6Om4HHm5JP99e11/kq4nimr0BvHuLfMHwfAL9CeMb2Nq');