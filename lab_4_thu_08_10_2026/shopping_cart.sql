-- 1. Create the database.
CREATE DATABASE IF NOT EXISTS web_applications_development_sem_i_2026_2027_lab_4_shopping_cart;

-- Switch to the newly created database.
USE web_applications_development_sem_i_2026_2027_lab_4_shopping_cart;

-- Create table 'products'.
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

-- Insert at least 5 sample products into the 'products' table.
INSERT INTO products (name, price, quantity) VALUES
('Laptop Dell XPS 15', 1499.99, 10),
('Apple iPhone 15 Pro Max', 1199, 25),
('Samsung Galaxy S24 Ultra', 1299.5, 30),
('Sony WH-1000XM5 Headphones', 348, 50),
('Keychron K8 Mechanical Keyboard', 89, 100);