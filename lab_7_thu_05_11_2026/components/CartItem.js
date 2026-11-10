/*
 * Boundary quantity constants (1 - 99).
 */
const QUANTITY_RULES = {
    MIN: 1,
    MAX: 99
};

const CartItem = {
    name: 'CartItem',

    /*
     * Explicitly declare custom events emitted to parent.
     */
    emits: [
        'change-quantity',
        'remove-item'
    ],

    /*
     * Receive item attributes via component props.
     */
    props: {
        id: {
            type: [Number, String],
            required: true
        },
        name: {
            type: String,
            default: ''
        },
        price: {
            type: Number,
            default: 0
        },
        quantity: {
            type: Number,
            default: 1
        }
    },

    computed: {
        /*
         * Resolve product name with fallback text.
         */
        displayName() {
            return this.name || 'Chưa có tên sản phẩm';
        },

        /*
         * Resolve unit price with safe numeric fallback.
         */
        unitPrice() {
            return typeof this.price === 'number' ? this.price : 0;
        },

        /*
         * Resolve current quantity value safely without mutative props.
         */
        currentQuantity() {
            return typeof this.quantity === 'number' ? this.quantity : 1;
        },

        /*
         * Compute line subtotal using formula: unit price * quantity
         */
        subtotal() {
            return this.unitPrice * this.currentQuantity;
        },

        /*
         * Check if decrement button should be disabled (at min boundary 1).
         */
        isMinQuantity() {
            return this.currentQuantity <= QUANTITY_RULES.MIN;
        },

        /*
         * Check if increment button should be disabled (at max boundary 99).
         */
        isMaxQuantity() {
            return this.currentQuantity >= QUANTITY_RULES.MAX;
        }
    },

    methods: {
        /*
         * Emit quantity change to parent.
         */
        emitQuantityUpdate(newQuantity) {
            const payload = {
                id: this.id,
                newQuantity: newQuantity
            };

            this.$emit('change-quantity', payload);
        },

        /*
         * Emit quantity decrement without mutating props.
         */
        decreaseQuantity() {
            if (this.isMinQuantity) {
                return;
            }

            this.emitQuantityUpdate(this.currentQuantity - 1);
        },

        /*
         * Emit quantity increment without mutating props.
         */
        increaseQuantity() {
            if (this.isMaxQuantity) {
                return;
            }

            this.emitQuantityUpdate(this.currentQuantity + 1);
        },

        /*
         * Handle manual input changes on the quantity field.
         */
        handleInputChange(event) {
            const rawValue = parseInt(event.target.value, 10);

            if (isNaN(rawValue) || rawValue < QUANTITY_RULES.MIN) {
                this.emitQuantityUpdate(QUANTITY_RULES.MIN);
                return;
            }

            if (rawValue > QUANTITY_RULES.MAX) {
                this.emitQuantityUpdate(QUANTITY_RULES.MAX);
                return;
            }

            this.emitQuantityUpdate(rawValue);
        },

        /*
         * Emit item deletion request to parent.
         */
        requestDelete() {
            this.$emit('remove-item', this.id);
        },

        /*
         * Format numeric price into localized Vietnamese Dong string.
         */
        formatCurrency(amount) {
            if (typeof amount !== 'number' || isNaN(amount)) {
                return '0 ₫';
            }

            return amount.toLocaleString('vi-VN') + ' ₫';
        }
    },

    template: `
        <article class="cart-item-row" aria-label="Cart item row">
            <!-- Product title -->
            <div class="cart-item-col col-product">
                <span class="product-title">{{ displayName }}</span>
                <span class="product-sku">Mã sản phẩm: #{{ id }}</span>
            </div>

            <!-- Unit price -->
            <div class="cart-item-col col-price">
                <span class="mobile-label">Đơn giá:</span>
                <span class="price-text">{{ formatCurrency(unitPrice) }}</span>
            </div>

            <!-- Stepper quantity control (1 - 99) -->
            <div class="cart-item-col col-quantity">
                <div class="stepper-box">
                    <button type="button" 
                            class="btn-stepper" 
                            :disabled="isMinQuantity" 
                            @click="decreaseQuantity"
                            aria-label="Giảm số lượng">
                        −
                    </button>
                    
                    <input type="number" 
                           class="stepper-input" 
                           :value="currentQuantity"
                           :min="1" 
                           :max="99" 
                           @change="handleInputChange"
                           aria-label="Số lượng sản phẩm">

                    <button type="button" 
                            class="btn-stepper" 
                            :disabled="isMaxQuantity" 
                            @click="increaseQuantity"
                            aria-label="Tăng số lượng">
                        +
                    </button>
                </div>
            </div>

            <!-- Calculated subtotal -->
            <div class="cart-item-col col-subtotal">
                <span class="mobile-label">Thành tiền:</span>
                <strong class="subtotal-text">{{ formatCurrency(subtotal) }}</strong>
            </div>

            <!-- Delete action button -->
            <div class="cart-item-col col-action">
                <button type="button" 
                        class="btn-delete-item" 
                        @click="requestDelete"
                        :title="'Xóa ' + displayName + ' khỏi giỏ hàng'"
                        aria-label="Xóa sản phẩm">
                    🗑️
                </button>
            </div>
        </article>
    `
};