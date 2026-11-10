/* 
 * Available product categories.
 */
const CATEGORIES = [
    'Điện tử & Phụ kiện',
    'Thời trang & Đồ dùng',
    'Gia dụng & Tiện ích'
];

/* 
 * Initial mock product data.
 */
const INITIAL_PRODUCTS = [
    {
        id: 101,
        name: 'Tai nghe Bluetooth True Wireless',
        price: 750000,
        category: 'Điện tử & Phụ kiện',
        stock: 15
    },
    {
        id: 102,
        name: 'Bàn phím cơ Tenkeyless RGB',
        price: 1200000,
        category: 'Điện tử & Phụ kiện',
        stock: 8
    },
    {
        id: 103,
        name: 'Áo khoác thể thao thoáng khí',
        price: 450000,
        category: 'Thời trang & Đồ dùng',
        stock: 0
    },
    {
        id: 104,
        name: 'Balo laptop chống nước cao cấp',
        price: 620000,
        category: 'Thời trang & Đồ dùng',
        stock: 20
    },
    {
        id: 105,
        name: 'Bình giữ nhiệt Inox 304 (750 ml)',
        price: 280000,
        category: 'Gia dụng & Tiện ích',
        stock: 35
    },
    {
        id: 106,
        name: 'Đèn LED bàn học cảm ứng chống cận',
        price: 390000,
        category: 'Gia dụng & Tiện ích',
        stock: 0
    }
];

/* 
 * Initial blank from data template for creating a product.
 */
const BLANK_NEW_PRODUCT = {
    name: '',
    category: CATEGORIES[0],
    price: null,
    stock: null
};

const { createApp } = Vue;

createApp({
    data() {
        return {
            categoryList: CATEGORIES,
            pageStatus: 'Đang tải...',
            isPageReady: false,
            searchKeyword: '',
            selectedCategory: 'ALL',
            selectedSortOrder: 'DEFAULT',
            searchWarningMessage: '',
            products: JSON.parse(JSON.stringify(INITIAL_PRODUCTS)),
            newProduct: { ...BLANK_NEW_PRODUCT },
            formErrorMessage: ''
        };
    },

    computed: {
        /* 
         * Total count of all existing products.
         */
        totalProductsCount() {
            return this.products.length;
        },

        /* 
         * Combined search, filter, and sorting.
         * Immutably sort without mutating the original products array.
         */
        filteredProducts() {
            let result = this.products.filter(product => {
                if (!this.searchKeyword) {
                    return true;
                }

                const keywordLower = this.searchKeyword.toLowerCase();

                return product.name.toLowerCase().includes(keywordLower);
            });

            if (this.selectedCategory !== 'ALL') {
                result = result.filter(product => product.category === this.selectedCategory);
            }

            if (this.selectedSortOrder === 'ASC') {
                result = [...result].sort((a, b) => a.price - b.price);
            } else if (this.selectedSortOrder === 'DESC') {
                result = [...result].sort((a, b) => b.price - a.price);
            }

            return result;
        },

        /* 
         * Number of products currently displayed matching filters.
         */
        displayedProductsCount() {
            return this.filteredProducts.length;
        },

        /* 
         * Total inventory value of currently displayed products: Inventory value = Price * Stock quantity.
         */
        totalDisplayedInventoryValue() {
            return this.filteredProducts.reduce((accum, product) => {
                const itemValue = Number(product.price) * Number(product.stock);

                return accum + (isNaN(itemValue) ? 0 : itemValue);
            }, 0);
        },

        /* 
         * Validate if the new product form inputs meet all criteria:
         * - Name is not empty.
         * - Price is greater than 0.
         * - Stock is a non-negative integer (>= 0).
         */
        isFormSubmittable() {
            const hasName = typeof this.newProduct.name === 'string' && this.newProduct.name.trim().length > 0;
            const hasValidPrice = typeof this.newProduct.price === 'number' && this.newProduct.price > 0;
            const hasValidStock = Number.isInteger(this.newProduct.stock) && this.newProduct.stock >= 0;

            return hasName && hasValidPrice && hasValidStock;
        }
    },

    watch: {
        /* 
         * Watch searchKeyword:
         * - Show notice when keyword has exactly 1 character.
         * - Clear notice when keyword is empty or has 2+ characters.
         */
        searchKeyword(newKeyword) {
            const length = typeof newKeyword === 'string' ? newKeyword.trim().length : 0;

            if (length === 1) {
                this.searchWarningMessage = 'Vui lòng nhập từ 2 ký tự trở lên để tìm kiếm chính xác hơn.';
            } else {
                this.searchWarningMessage = '';
            }
        }
    },

    mounted() {
        /* 
         * Set page state to "Sẵn sàng" upon component mounting.
         */
        this.pageStatus = 'Sẵn sàng';
        this.isPageReady = true;
    },

    methods: {
        /* 
         * Check whether a product has stock.
         */
        isProductInStock(product) {
            return Number(product.stock) > 0;
        },

        /* 
         * Calculate inventory value for an individual product item.
         */
        calculateItemInventoryValue(product) {
            return (Number(product.price) || 0) * (Number(product.stock) || 0);
        },

        /* 
         * Handle creation and insertion of a new product.
         */
        handleCreateProduct() {
            if (!this.isFormSubmittable) {
                this.formErrorMessage = 'Vui lòng điền tên không rỗng, giá > 0 và tồn kho là số nguyên >= 0.';
                return;
            }

            const createdItem = {
                id: this.generateNextProductId(),
                name: this.newProduct.name.trim(),
                category: this.newProduct.category,
                price: Number(this.newProduct.price),
                stock: Number(this.newProduct.stock)
            };

            this.products.unshift(createdItem);
            this.resetNewProductForm();
        },

        /* 
         * Reset add product form fields and clear error messages.
         */
        resetNewProductForm() {
            this.newProduct = {
                name: '',
                category: this.categoryList[0],
                price: null,
                stock: null
            };
            this.formErrorMessage = '';
        },

        /* 
         * Prompt confirmation before permanently deleting a product.
         */
        confirmAndDeleteProduct(product) {
            const confirmed = window.confirm(`Bạn có chắc chắn muốn xóa sản phẩm "${product.name}" (mã sản phẩm: #${product.id}) không?`);

            if (confirmed) {
                this.deleteProductById(product.id);
            }
        },

        /* 
         * Remove a product from the list by its ID.
         */
        deleteProductById(productId) {
            this.products = this.products.filter(item => item.id !== productId);
        },

        /* 
         * Generate unique sequential product ID.
         */
        generateNextProductId() {
            if (this.products.length === 0) {
                return 101;
            }

            const maxId = Math.max(...this.products.map(p => Number(p.id) || 0));

            return maxId + 1;
        },

        /* 
         * Format numeric currency values into localized Vietnamese Dong (VND).
         */
        formatCurrency(amount) {
            if (typeof amount !== 'number' || isNaN(amount)) {
                return '0 đ';
            }

            return amount.toLocaleString('vi-VN') + ' đ';
        }
    }
}).mount('#app');