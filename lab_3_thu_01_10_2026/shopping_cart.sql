-- 1. Create the database.
CREATE DATABASE IF NOT EXISTS web_applications_development_sem_i_2026_2027_lab_3_shopping_cart;

-- Switch to the newly created database.
USE web_applications_development_sem_i_2026_2027_lab_3_shopping_cart;

-- Create table cart_items.
CREATE TABLE cart_items (
	id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

-- 2. Practical exercises.
-- 2.1. Add at least 5 products into the table.
INSERT INTO cart_items (name, price, quantity) VALUES
('Laptop', 25000000.00, 2),
('Wireless mouse', 150000.00, 10),
('Mechanical keyboard', 850000.00, 3),
('4K monitor', 5000000.00, 1),
('USB-C Hub', 350000.00, 6);

-- 2.2. Display all products.
SELECT * FROM cart_items;

-- 2.3. Display products with price greater than 100000.
SELECT * FROM cart_items WHERE price > 100000;

-- 2.4. Display products with quantity greater than 5.
SELECT * FROM cart_items WHERE quantity > 5;

-- 2.5. Sort products by price in descending order.
SELECT * FROM cart_items ORDER BY price DESC;

-- 2.6. Update the price of a product (in example, update the price of the 'Laptop' with id = 1).
UPDATE cart_items SET price = 24500000.00 WHERE id = 1;

-- 2.7. Update the quantity of a product (in example, update the quantity of the 'Wireless mouse' with id = 2).
UPDATE cart_items SET quantity = 15 WHERE id = 2;

-- 2.8. Delete a product (in example, delete the "USB-C Hub" with id = 5).
DELETE FROM cart_items WHERE id = 5;

-- 2.9. Display product name, price, quantity, and total amount (price * quantity).
SELECT name, price, quantity, (price * quantity) AS total_amount FROM cart_items;

-- 2.10. Calculate the total amount of the entire shopping cart.
SELECT SUM(price * quantity) AS grand_total FROM cart_items;