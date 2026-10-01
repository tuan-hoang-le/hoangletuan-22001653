-- 1. Create the database.
CREATE DATABASE IF NOT EXISTS web_applications_development_sem_i_2026_2027_lab_3_movie;

-- Switch to the newly created database.
USE web_applications_development_sem_i_2026_2027_lab_3_movie;

-- 1. Create table movies.
CREATE TABLE movies (
	id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL
);

-- 2. Practical exercises.
-- 2.1. Add at least 5 movies into the table.
INSERT INTO movies (title, price, total_seats, available_seats) VALUES
('Avengers', 100000.00, 100, 92),
('Avatar', 120000.00, 80, 30),
('Batman', 90000.00, 120, 120),
('Spider-Man', 150000.00, 90, 70),
('Inception', 80000.00, 150, 40);

-- 2.2. Display all movies.
SELECT * FROM movies;

-- 2.3. Display movies with price greater than 100000.
SELECT * FROM movies WHERE price > 100000;

-- 2.4. Display movies with more than 50 available seats.
SELECT * FROM movies WHERE available_seats > 50;

-- 2.5. Sort movies by price in descending order.
SELECT * FROM movies ORDER BY price DESC;

-- 2.6. Update the available seats of a movie (in example, update 'Avengers' with id = 1).
UPDATE movies SET available_seats = 80 WHERE id = 1;

-- 2.7. Delete a movie (in example, delete 'Batman' with id = 3).
DELETE FROM movies WHERE id = 3;

-- 2.8. Display the number of sold tickets for each movie (total_seats - available_seats).
SELECT title, (total_seats - available_seats) AS sold_tickets FROM movies;

-- 2.9. Calculate the revenue of each movie: (total_seats - available_seats) * price.
SELECT title, (total_seats - available_seats) * price AS revenue FROM movies;

-- 2.10. Calculate the total revenue of all movies.
SELECT SUM((total_seats - available_seats) * price) AS total_revenue FROM movies;

-- 2.11. Find the movie with the most tickets sold.
SELECT title, (total_seats - available_seats) AS sold_tickets
FROM movies
WHERE (total_seats - available_seats) = (
	SELECT MAX(total_seats - available_seats) FROM movies
);