<?php
/* 
 * Class ProductPriceManager encapsulates product prices and pricing analytics.
 */
class ProductPriceManager {
    private array $prices;

    /* 
     * Constructor to initialize product price list.
     */
    public function __construct(array $prices = []) {
        $this->prices = $prices;
    }

    /* 
     * Add a product price to the list.
     */
    public function addPrice(float $price): void {
        $this->prices[] = $price;
    }

    /* 
     * Get all product prices.
     */
    public function getPrices(): array {
        return $this->prices;
    }

    /* 
     * Calculate the total price of all products.
     */
    public function calculateTotal(): float {
        if (empty($this->prices)) {
            return 0.0;
        }

        $total = 0.0;

        foreach ($this->prices as $price) {
            $total += (float) $price;
        }

        return $total;
    }

    /* 
     * Find and return the highest product price.
     */
    public function findMaxPrice(): float {
        if (empty($this->prices)) {
            return 0.0;
        }

        $maxPrice = (float) $this->prices[0];

        foreach ($this->prices as $price) {
            if ($price > $maxPrice) {
                $maxPrice = (float) $price;
            }
        }

        return $maxPrice;
    }

    /* 
     * Display product prices and summary calculations.
     */
    public function displaySummary(): void {
        echo "Danh sách giá các sản phẩm:<br>";
        foreach ($this->prices as $index => $price) {
            $itemNumber = $index + 1;
            echo "- Sản phẩm {$itemNumber}: " . number_format($price, 2) . " VNĐ<br>";
        }

        $total = $this->calculateTotal();
        $maxPrice = $this->findMaxPrice();

        echo "<br>--- Kết quả tính toán ---<br>";
        echo "Tổng giá trị của tất cả sản phẩm: " . number_format($total, 2) . " VNĐ<br>";
        echo "Giá sản phẩm cao nhất: " . number_format($maxPrice, 2) . " VNĐ<br>";
    }
}

// --------------
// MAIN EXECUTION
// --------------

// Declare at least 5 product prices and instantiate the manager.
$productPrices = [150.0, 320.5, 89.9, 450.0, 210.75];
$priceManager = new ProductPriceManager($productPrices);

// Render results.
$priceManager->displaySummary();