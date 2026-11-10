// Fallback image URL when image prop is broken or omitted.
const DEFAULT_PRODUCT_IMAGE = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=400&q=80';

const ProductCard = {
    name: 'ProductCard',

    /* 
     * Component props with explicit type validation, containing at least one required prop and one with default value.
     */
    props: {
        // Product name (required string).
        name: {
            type: String,
            required: true
        },
        // Product price (required number).
        price: {
            type: Number,
            required: true
        },
        // Product thumbnail image URL (string with default fallback).
        image: {
            type: String,
            default: DEFAULT_PRODUCT_IMAGE
        },
        // Product description (string with default text).
        description: {
            type: String,
            default: 'Thông tin chi tiết về sản phẩm đang được cập nhật.'
        },
        // Available stock quantity (number with default 0).
        stock: {
            type: Number,
            default: 0
        }
    },

    /* 
     * Local component state for description toggle.
     * Isolated inside each component instance so toggling one does not affect others.
     */
    data() {
        return {
            isDescriptionOpen: false
        };
    },

    computed: {
        /* 
         * Check whether product has remaining inventory.
         */
        isInStock() {
            return typeof this.stock === 'number' && this.stock > 0;
        }
    },

    methods: {
        /* 
         * Toggle description accordion visibility.
         */
        toggleDescription() {
            this.isDescriptionOpen = !this.isDescriptionOpen;
        },

        /* 
         * Handle broken image links gracefully by replacing with default asset.
         */
        handleImageError(event) {
            event.target.src = DEFAULT_PRODUCT_IMAGE;
        },

        /* 
         * Emit purchase event when product is in stock.
         */
        handleBuyClick() {
            if (!this.isInStock) {
                return;
            }

            this.$emit('buy', {
                name: this.name,
                price: this.price
            });
        },

        /* 
         * Format numeric price into localized Vietnamese Dong (VND).
         */
        formatCurrency(amount) {
            if (typeof amount !== 'number' || isNaN(amount)) {
                return '0 đ';
            }

            return amount.toLocaleString('vi-VN') + ' đ';
        }
    },

    /* 
     * Component template.
     */
    template: `
        <article class="product-card" :class="{ 'product-card--out-of-stock': !isInStock }">
            <!-- Product thumbnail image and stock badge -->
            <div class="product-card__media">
                <img :src="image"
                     :alt="name"
                     class="product-card__img"
                     @error="handleImageError">

                <span class="badge" :class="isInStock ? 'badge--in-stock' : 'badge--out-of-stock'">
                    {{ isInStock ? 'Còn hàng (' + stock + ')' : 'Hết hàng' }}
                </span>
            </div>

            <!-- Product core information -->
            <div class="product-card__content">
                <h2 class="product-card__title">{{ name }}</h2>
                <div class="product-card__price">{{ formatCurrency(price) }}</div>

                <button type="button"
                        class="btn-toggle-desc"
                        @click="toggleDescription"
                        :aria-expanded="isDescriptionOpen">
                    <span>{{ isDescriptionOpen ? 'Ẩn mô tả' : 'Xem mô tả' }}</span>
                    <span class="toggle-icon">{{ isDescriptionOpen ? '▲' : '▼' }}</span>
                </button>

                <!-- Collapsible description content -->
                <div class="product-card__desc" v-show="isDescriptionOpen">
                    <p>{{ description }}</p>
                </div>
            </div>

            <!-- Product card footer action -->
            <footer class="product-card__footer">
                <button type="button"
                        class="btn btn--buy"
                        :disabled="!isInStock"
                        @click="handleBuyClick"
                        :title="!isInStock ? 'Sản phẩm tạm thời hết hàng' : 'Nhấn để thêm vào đơn mua'">
                    {{ isInStock ? '🛒 Mua ngay' : 'Tạm hết hàng' }}
                </button>
            </footer>
        </article>
    `
};