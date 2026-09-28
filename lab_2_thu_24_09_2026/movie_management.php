<?php
/* 
 * Class Movie representing a single movie and its ticketing information.
 */
class Movie {
    private int $id;
    private string $title;
    private float $price;
    private int $totalSeats;
    private int $availableSeats;

    /* 
     * Constructor to initialize a new movie with id, title, price, and total seats available.
     */
    public function __construct(int $id, string $title, float $price, int $totalSeats) {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->totalSeats = $totalSeats;
        $this->availableSeats = $totalSeats;
    }

    /* 
     * Get Movie ID.
     */
    public function getId(): int {
        return $this->id;
    }

    /* 
     * Get Movie title.
     */
    public function getTitle(): string {
        return $this->title;
    }

    /* 
     * Book a specified number of tickets.
     */
    public function bookTicket(int $quantity): bool {
        if ($quantity <= 0) {
            echo "[Error] Cannot book {$quantity} tickets for '{$this->title}'. Quantity must be greater than 0.<br>";

            return false;
        }

        if ($quantity > $this->availableSeats) {
            echo "[Error] Cannot book {$quantity} tickets for '{$this->title}'. Only {$this->availableSeats} seats available.<br>";

            return false;
        }

        $this->availableSeats -= $quantity;
        echo "[Success] Booked {$quantity} tickets for '{$this->title}'.<br>";

        return true;
    }

    /* 
     * Cancel a specified number of booked tickets.
     */
    public function cancelTicket(int $quantity): bool {
        if ($quantity <= 0) {
            echo "[Error] Cannot cancel {$quantity} tickets for '{$this->title}'. Quantity must be greater than 0.<br>";

            return false;
        }

        $soldSeats = $this->getSoldSeats();

        if ($quantity > $soldSeats) {
            echo "[Error] Cannot cancel {$quantity} tickets for '{$this->title}'. Only {$soldSeats} tickets have been sold.<br>";

            return false;
        }

        $this->availableSeats += $quantity;
        echo "[Success] Cancelled {$quantity} tickets for '{$this->title}'.<br>";

        return true;
    }

    /* 
     * Get the number of tickets already sold using formula: totalSeats - availableSeats
     */
    public function getSoldSeats(): int {
        return $this->totalSeats - $this->availableSeats;
    }

    /* 
     * Calculate the total revenue from sold tickets using formula: sold seats * price
     */
    public function getRevenue(): float {
        return $this->getSoldSeats() * $this->price;
    }

    /* 
     * Display all information about the movie.
     */
    public function displayInfo(): void {
        echo "------------------------------------------------------<br>";
        echo "Movie ID: {$this->id}<br>";
        echo "Title: {$this->title}<br>";
        echo "Ticket price: " . number_format($this->price, 0, ',', '.') . " VND<br>";
        echo "Total seats: {$this->totalSeats}<br>";
        echo "Available seats: {$this->availableSeats}<br>";
        echo "Sold seats: {$this->getSoldSeats()}<br>";
        echo "Revenue: " . number_format($this->getRevenue(), 0, ',', '.') . " VND<br>";
        echo "------------------------------------------------------<br>";
    }
}

// =========================================
// Functions for processing lists of movies.
// =========================================

/* 
 * Find a movie by its ID in the provided array.
 */
function findMovieById(array $movies, int $id): ?Movie {
    if (empty($movies)) {
        echo "[Warning] Cannot search: The movie list is empty.<br>";

        return null;
    }

    foreach ($movies as $movie) {
        if ($movie->getId() === $id) {
            return $movie;
        }
    }

    echo "[Warning] Movie with ID {$id} not found in the list.<br>";

    return null;
}

/* 
 * Calculate the total revenue of all movies in the list.
 */
function getTotalRevenue(array $movies): float {
    if (empty($movies)) {
        echo "[Warning] Cannot calculate revenue: The movie list is empty.<br>";

        return 0.0;
    }

    $total = 0.0;

    foreach ($movies as $movie) {
        $total += $movie->getRevenue();
    }

    return $total;
}

/* 
 * Find the movie with the highest number of sold tickets.
 */
function getBestSellingMovie(array $movies): ?Movie {
    if (empty($movies)) {
        echo "[Warning] Cannot find best-selling movie: The list is empty.<br>";

        return null;
    }

    $bestMovie = null;
    $maxSold = -1;

    foreach ($movies as $movie) {
        $sold = $movie->getSoldSeats();

        if ($sold > $maxSold) {
            $maxSold = $sold;
            $bestMovie = $movie;
        }
    }

    return $bestMovie;
}

// ===============
// MAIN EXECUTION.
// ===============

// 1. Create a list of Movie objects.
echo "1. Creating movie list...<br>";
$movies = [
    new Movie(1, "Avengers", 100000, 100),
    new Movie(2, "Avatar", 120000, 80),
    new Movie(3, "Batman", 90000, 120),
];

// Find movies for testing.
$avengers = findMovieById($movies, 1);
$avatar = findMovieById($movies, 2);
echo "<br>--- [Testing book & cancel scenarios] ---<br>";

// 2. Book tickets for Avengers.
echo "<br>2. Booking tickets for Avengers:<br>";

if ($avengers) {
    $avengers->bookTicket(10);
    $avengers->bookTicket(-5);
    $avengers->bookTicket(200);
}

// 3. Book tickets for Avatar.
echo "<br>3. Booking tickets for Avatar:<br>";

if ($avatar) {
    $avatar->bookTicket(30);
    $avatar->bookTicket(20);
}

// 4. Cancel some tickets of Avengers.
echo "<br>4. Cancelling tickets for Avengers:<br>";

if ($avengers) {
    $avengers->cancelTicket(2);
    $avengers->cancelTicket(0);
    $avengers->cancelTicket(15);
}

echo "<br>--- [Testing list functions & edge cases] ---<br>";

// Testing finding non-existent movie.
echo "<br>[Test] Find non-existent movie (ID 99):<br>";
findMovieById($movies, 99);

// Testing empty list for list-processing functions.
echo "<br>[Test] Passing empty list to functions:<br>";
$emptyMoviesList = [];
findMovieById($emptyMoviesList, 1);
getTotalRevenue($emptyMoviesList);
getBestSellingMovie($emptyMoviesList);

echo "<br>--- [Final results] ---<br>";

// 5. Display information of all movies.
echo "<br>5. Displaying information of all movies:<br>";

foreach ($movies as $movie) {
    $movie->displayInfo();
}

// 6. Calculate and display total revenue of all movies.
echo "<br>6. Calculating total revenue of all movies:<br>";
$totalRevenue = getTotalRevenue($movies);
echo "=> TOTAL REVENUE OF ALL MOVIES: " . number_format($totalRevenue, 0, ',', '.') . " VND<br>";

// 7. Find and display the movie with the most tickets sold.
echo "<br>7. Finding the best-selling movie:<br>";
$bestSelling = getBestSellingMovie($movies);

if ($bestSelling) {
    echo "=> BEST-SELLING MOVIE: '{$bestSelling->getTitle()}'<br>";
    $bestSelling->displayInfo();
}
?>