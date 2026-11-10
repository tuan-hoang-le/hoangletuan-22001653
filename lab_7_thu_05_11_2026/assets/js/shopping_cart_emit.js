/*
 * Initial mock cart items.
 */
const DEFAULT_CART_ITEMS = [
    {
        id: 1,
        name: 'Bút bi cao cấp',
        price: 5000,
        quantity: 2
    },
    {
        id: 2,
        name: 'Quyển vở kẻ ngang',
        price: 12000,
        quantity: 3
    }
];

/*
 * Quantity boundary rules.
 */
const QUANTITY_LIMITS = {
    MIN: 1,
    MAX: 99
};

const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            cartItems: JSON.parse(JSON.stringify(DEFAULT_CART_ITEMS))
        };
    },

    computed: {
        /*
         * Compute total quantity of all items in cart.
         */
        totalQuantity() {
            return this.cartItems.reduce((accum, item) => accum + Number(item.quantity), 0);
        },

        /*
         * Compute total price of all items in cart.
         */
        totalPrice() {
            return this.cartItems.reduce((accum, item) => {
                const lineTotal = Number(item.price) * Number(item.quantity);
                return accum + (isNaN(lineTotal) ? 0 : lineTotal);
            }, 0);
        }
    },

    methods: {
        /*
         * Receive quantity change event, validate input (integer 1 - 99), and update master cartItems array.
         */
        handleQuantityChange(payload) {
            if (!payload || payload.id === undefined) {
                return;
            }

            const targetItem = this.cartItems.find(item => item.id === payload.id);

            if (!targetItem) {
                return;
            }

            const parsedQty = parseInt(payload.newQuantity, 10);

            if (Number.isInteger(parsedQty) && parsedQty >= QUANTITY_LIMITS.MIN && parsedQty <= QUANTITY_LIMITS.MAX) {
                targetItem.quantity = parsedQty;
            }
        },

        /*
         * Receive deletion request and remove product from array.
         */
        handleDeleteRequest(productId) {
            this.cartItems = this.cartItems.filter(item => item.id !== productId);
        },

        /*
         * Clear all items from the cart.
         */
        clearEntireCart() {
            const confirmed = window.confirm('Bạn có chắc chắn muốn xóa toàn bộ sản phẩm trong giỏ hàng?');

            if (confirmed) {
                this.cartItems = [];
            }
        },

        /*
         * Restore default test items (2 pens, 3 notebooks).
         */
        restoreDefaultItems() {
            this.cartItems = JSON.parse(JSON.stringify(DEFAULT_CART_ITEMS));
        },

        /*
         * Simulate checkout completion.
         */
        handleCheckout() {
            if (this.cartItems.length === 0) {
                return;
            }

            alert(`Đặt hàng thành công!\nTổng số lượng: ${this.totalQuantity} món\nTổng thanh toán: ${this.formatCurrency(this.totalPrice)}`);
        },

        /*
         * Format numeric price into localized Vietnamese Dong (VND).
         */
        formatCurrency(amount) {
            if (typeof amount !== 'number' || isNaN(amount)) {
                return '0 ₫';
            }

            return amount.toLocaleString('vi-VN') + ' ₫';
        }
    }
});

/* 
 * Register component globally BEFORE mounting the application.
 */
app.component('cart-item', CartItem);
app.mount('#app');