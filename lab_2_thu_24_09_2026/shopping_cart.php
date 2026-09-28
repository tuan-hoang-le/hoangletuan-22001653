<?php
/* 
 * Class CartItem representing a single product in the shopping cart.
 */
class CartItem {
    private string $name;
    private float $price;
    private int $quantity;

    /* 
     * Constructor to initialize a new cart item with name, price, and quantity.
     */
    public function __construct(string $name, float $price, int $quantity) {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    /* 
     * Get the name of the product.
     */
    public function getName(): string {
        return $this->name;
    }

    /* 
     * Get the price of the product.
     */
    public function getPrice(): float {
        return $this->price;
    }

    /* 
     * Get the quantity of the product.
     */
    public function getQuantity(): int {
        return $this->quantity;
    }

    /* 
     * Calculate the total cost for this specific item using formula: price * quantity
     */
    public function getTotal(): float {
        return $this->price * $this->quantity;
    }
}

/* 
 * Class ShoppingCart to manage a collection of CartItem objects.
 */
class ShoppingCart {
    private array $items;

    /* 
     * Constructor to initialize an empty shopping cart.
     */
    public function __construct() {
        $this->items = [];
    }

    /* 
     * Add a new CartItem object to the shopping cart.
     */
    public function addItem(CartItem $item): void {
        if ($item->getPrice() <= 0) {
            echo "[Error] Cannot add '{$item->getName()}'. Price must be greater than 0.<br>";

            return;
        }

        if ($item->getQuantity() <= 0) {
            echo "[Error] Cannot add '{$item->getName()}'. Quantity must be greater than 0.<br>";

            return;
        }

        $this->items[] = $item;
        echo "[Success] '{$item->getName()}' has been added to the cart.<br>";
    }

    /* 
     * Remove a product from the shopping cart by its name.
     */
    public function removeItem(string $name): void {
        $itemFound = false;

        foreach ($this->items as $index => $item) {
            if ($item->getName() === $name) {
                unset($this->items[$index]);
                $this->items = array_values($this->items);
                $itemFound = true;
                echo "[Success] '{$name}' has been removed from the cart.<br>";
                break;
            }
        }

        if (!$itemFound) {
            echo "[Error] Product '{$name}' not found in the cart.<br>";
        }
    }

    /* 
     * Calculate the total price of all items currently in the cart.
     */
    public function calculateTotal(): float {
        if (empty($this->items)) {
            return 0.0;
        }

        $overallTotal = 0.0;

        foreach ($this->items as $item) {
            $overallTotal += $item->getTotal();
        }

        return $overallTotal;
    }

    /* 
     * Display all products in the cart along with their details and the overall total.
     */
    public function displayCart(): void {
        echo "<br>========== SHOPPING CART ==========<br>";

        if (empty($this->items)) {
            echo "The shopping cart is currently empty.<br>";
            echo "=================================<br><br>";

            return;
        }

        foreach ($this->items as $item) {
            $name = $item->getName();
            $price = $item->getPrice();
            $quantity = $item->getQuantity();
            $itemTotal = $item->getTotal();
            echo "- Product: {$name} | Unit price: \${$price} | Quantity: {$quantity} | Total: \${$itemTotal}<br>";
        }

        $grandTotal = $this->calculateTotal();
        echo "-----------------------------------<br>";
        echo "Grand total: \${$grandTotal}<br>";
        echo "===================================<br><br>";
    }
}

// ===============
// MAIN EXECUTION.
// ===============

// 1. Create a ShoppingCart object.
echo "--- 1. Creating shopping cart ---<br>";
$cart = new ShoppingCart();

// 2. Create at least 4 CartItem objects.
echo "<br>--- 2. Creating cart items ---<br>";
$laptop = new CartItem("Laptop", 1200.5, 1);
$mouse = new CartItem("Wireless mouse", 25, 2);
$keyboard = new CartItem("Mechanical keyboard", 85, 1);
$monitor = new CartItem("4K monitor", 300, 2);

// Invalid cases for demonstration (price <= 0 and quantity <= 0).
$invalidPriceItem = new CartItem("Free sample", 0, 1);
$invalidQuantityItem = new CartItem("Sold out item", 50, 0);

// 3. Add items to the cart.
echo "<br>--- 3. Adding items to cart ---<br>";
$cart->addItem($laptop);
$cart->addItem($mouse);
$cart->addItem($keyboard);
$cart->addItem($monitor);

// Testing invalid additions.
echo "<br>[Testing invalid additions]<br>";
$cart->addItem($invalidPriceItem);
$cart->addItem($invalidQuantityItem);

// 4. Display the whole cart.
echo "<br>--- 4. Displaying entire cart ---<br>";
$cart->displayCart();

// 5. Calculate and display the total price of the cart.
echo "--- 5. Calculating total price ---<br>";
$totalPrice = $cart->calculateTotal();
echo "The total price of all items is: \${$totalPrice}<br>";

// 6. Remove a product by name.
echo "<br>--- 6. Removing a product ---<br>";
$cart->removeItem("Wireless mouse");

// Testing removal of non-existent product.
echo "<br>[Testing invalid removal]<br>";
$cart->removeItem("Tablet");

// 7. Display the cart again after removal.
echo "<br>--- 7. Displaying cart after removal ---<br>";
$cart->displayCart();

// Testing calculateTotal() with an empty cart.
echo "--- Testing empty cart scenario ---<br>";
$cart->removeItem("Laptop");
$cart->removeItem("Mechanical keyboard");
$cart->removeItem("4K monitor");
$cart->displayCart();
echo "Empty cart total: \$" . $cart->calculateTotal() . "<br>";
?>