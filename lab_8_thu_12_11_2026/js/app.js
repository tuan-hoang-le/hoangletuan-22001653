const { createApp } = Vue;

createApp({
    data() {
        return {
            products: [],
            form: {
                id: null,
                name: '',
                price: '',
                stock: ''
            },
            isEditing: false,
            errorMessage: '',
            successMessage: '',
            isLoading: false
        };
    },

    mounted() {
        this.fetchProducts();
    },

    methods: {
        /* 
         * Clear all notification alerts.
         */
        clearMessages() {
            this.errorMessage = '';
            this.successMessage = '';
        },

        /* 
         * Format an integer price into Vietnamese currency string.
         */
        formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        },

        /* 
         * Fetch the product list from backend API (GET api/products.php).
         */
        async fetchProducts() {
            this.isLoading = true;

            try {
                const response = await fetch('api/products.php');
                const result = await response.json();

                if (result.success) {
                    this.products = result.data;
                } else {
                    this.errorMessage = result.message || 'Không thể tải danh sách sản phẩm.';
                }
            } catch (error) {
                this.errorMessage = 'Lỗi kết nối máy chủ khi tải danh sách: ' + error.message;
            } finally {
                this.isLoading = false;
            }
        },

        /* 
         * Handle the form submit event and routes to create or update.
         */
        async handleSubmit() {
            this.clearMessages();

            if (this.isEditing) {
                await this.updateProduct();
            } else {
                await this.createProduct();
            }
        },

        /* 
         * Send POST request to create a new product.
         */
        async createProduct() {
            this.isLoading = true;

            try {
                const payload = {
                    name: this.form.name,
                    price: Number(this.form.price),
                    stock: Number(this.form.stock)
                };

                const response = await fetch('api/products.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    this.successMessage = result.message || 'Thêm sản phẩm thành công';
                    this.resetForm();
                    await this.fetchProducts();
                } else {
                    this.errorMessage = result.message || 'Không thể thêm sản phẩm.';
                }
            } catch (error) {
                this.errorMessage = 'Lỗi gửi yêu cầu thêm sản phẩm: ' + error.message;
            } finally {
                this.isLoading = false;
            }
        },

        /* 
         * Load selected row data into the form for editing.
         */
        startEdit(product) {
            this.clearMessages();
            this.isEditing = true;
            this.form = {
                id: product.id,
                name: product.name,
                price: product.price,
                stock: product.stock
            };
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        /* 
         * Send PUT request to update the product by ID (api/products.php?id=...).
         */
        async updateProduct() {
            this.isLoading = true;

            try {
                const payload = {
                    name: this.form.name,
                    price: Number(this.form.price),
                    stock: Number(this.form.stock)
                };

                const response = await fetch(`api/products.php?id=${this.form.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    this.successMessage = result.message || 'Cập nhật sản phẩm thành công!';
                    this.resetForm();
                    await this.fetchProducts();
                } else {
                    this.errorMessage = result.message || 'Không thể cập nhật sản phẩm.';
                }
            } catch (error) {
                this.errorMessage = 'Lỗi gửi yêu cầu cập nhật: ' + error.message;
            } finally {
                this.isLoading = false;
            }
        },

        /* 
         * Confirm with user and send DELETE request if confirmed.
         */
        async deleteProduct(product) {
            this.clearMessages();

            const confirmed = window.confirm(`Bạn có chắc chắn muốn xóa sản phẩm "${product.name}" không?`);

            if (!confirmed) {
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch(`api/products.php?id=${product.id}`, {
                    method: 'DELETE'
                });
                const result = await response.json();

                if (result.success) {
                    this.successMessage = result.message || 'Xóa sản phẩm thành công!';

                    if (this.isEditing && this.form.id === product.id) {
                        this.resetForm();
                    }

                    await this.fetchProducts();
                } else {
                    this.errorMessage = result.message || 'Không thể xóa sản phẩm.';
                }
            } catch (error) {
                this.errorMessage = 'Lỗi gửi yêu cầu xóa: ' + error.message;
            } finally {
                this.isLoading = false;
            }
        },

        /* 
         * Reset the input form back to initial add mode.
         */
        resetForm() {
            this.form = {
                id: null,
                name: '',
                price: '',
                stock: ''
            };
            this.isEditing = false;
        }
    }
}).mount('#app');