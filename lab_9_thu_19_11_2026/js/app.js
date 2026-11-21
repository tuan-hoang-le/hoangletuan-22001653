const { createApp } = Vue;

// Image validation constraints (2 MB limit, JPG/JPEG/PNG).
const MAX_IMAGE_BYTES = 2 * 1024 * 1024;
const ALLOWED_IMAGE_MIMES = ['image/jpeg', 'image/png'];
const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png'];

createApp({
    data() {
        return {
            products: [],
            pagination: {
                page: 1,
                limit: 5,
                total: 0,
                total_pages: 1
            },
            form: {
                id: null,
                name: '',
                price: '',
                stock: '',
                currentImage: null
            },
            selectedFile: null,
            imagePreviewUrl: null,
            pendingUploadProductId: null,
            isEditing: false,
            errorMessage: '',
            successMessage: '',
            isLoading: false
        };
    },

    computed: {
        /* 
         * Return the active preview image source (newly selected file or existing image).
         */
        activePreviewSource() {
            if (this.imagePreviewUrl) {
                return this.imagePreviewUrl;
            }

            if (this.isEditing && this.form.currentImage) {
                return this.form.currentImage;
            }

            return null;
        },

        /* 
         * Check whether the user can retry uploading a selected image for a saved product.
         */
        canRetryUpload() {
            return this.pendingUploadProductId !== null && this.selectedFile !== null;
        }
    },

    mounted() {
        this.fetchProducts(1);
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
         * Revoke temporary blob preview URL to prevent memory leaks.
         */
        revokePreviewUrl() {
            if (this.imagePreviewUrl) {
                URL.revokeObjectURL(this.imagePreviewUrl);
                this.imagePreviewUrl = null;
            }
        },

        /* 
         * Validate image file type (JPG, JPEG, PNG) and size (<= 2 MB) on client side.
         */
        validateImageFile(file) {
            if (!file) {
                return '';
            }

            const fileName = (file.name || '').toLowerCase();
            const extension = fileName.includes('.') ? fileName.split('.').pop() : '';
            const isMimeValid = ALLOWED_IMAGE_MIMES.includes(file.type);
            const isExtValid = ALLOWED_IMAGE_EXTENSIONS.includes(extension);

            if (!isMimeValid || !isExtValid) {
                return 'Chỉ chấp nhận tệp ảnh định dạng JPG, JPEG hoặc PNG.';
            }

            if (file.size <= 0 || file.size > MAX_IMAGE_BYTES) {
                return 'Dung lượng ảnh không được vượt quá 2 MB.';
            }

            return '';
        },

        /* 
         * Handle file input change event, validate image in Vue, and generate preview.
         */
        handleFileChange(event) {
            this.clearMessages();
            this.revokePreviewUrl();

            const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
            this.selectedFile = file;

            if (!file) {
                return;
            }

            const validationError = this.validateImageFile(file);

            if (validationError) {
                this.errorMessage = validationError;
                return;
            }

            this.imagePreviewUrl = URL.createObjectURL(file);
        },

        /* 
         * Clear the currently chosen file from the file input.
         */
        clearSelectedFile() {
            this.selectedFile = null;
            this.revokePreviewUrl();

            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },

        /* 
         * Fetch paginated products from backend API (GET api/products.php?page=...&limit=5).
         */
        async fetchProducts(targetPage = 1) {
            this.isLoading = true;

            try {
                const limit = this.pagination.limit || 5;
                const response = await fetch(`api/products.php?page=${targetPage}&limit=${limit}`);
                const result = await response.json();

                if (result.success) {
                    this.products = Array.isArray(result.data) ? result.data : [];

                    if (result.pagination) {
                        this.pagination = {
                            page: Number(result.pagination.page) || 1,
                            limit: Number(result.pagination.limit) || 5,
                            total: Number(result.pagination.total) || 0,
                            total_pages: Number(result.pagination.total_pages) || 1
                        };
                    }
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
         * Navigate to another page without reloading the browser.
         */
        async changePage(newPage) {
            if (this.isLoading || this.pagination.total === 0) {
                return;
            }

            if (newPage < 1 || newPage > this.pagination.total_pages || newPage === this.pagination.page) {
                return;
            }

            await this.fetchProducts(newPage);
        },

        /* 
         * Upload the selected image file for a saved product ID via POST api/upload.php.
         */
        async uploadProductImage(productId) {
            if (!this.selectedFile) {
                return { success: true };
            }

            // Re-validate in Vue before sending multipart/form-data to PHP.
            const vueValidationError = this.validateImageFile(this.selectedFile);
        
            const formData = new FormData();
            formData.append('id', productId);
            formData.append('image', this.selectedFile);

            try {
                const response = await fetch(`api/upload.php?id=${productId}`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (!result.success) {
                    return {
                        success: false,
                        message: result.message || vueValidationError || 'Tải ảnh lên thất bại.'
                    };
                }

                return {
                    success: true,
                    image: result.image,
                    message: result.message
                };
            } catch (error) {
                return {
                    success: false,
                    message: vueValidationError || ('Lỗi kết nối khi tải ảnh lên: ' + error.message)
                };
            }
        },

        /* 
         * Retry uploading the selected image for an already saved product.
         */
        async retryImageUpload() {
            if (!this.canRetryUpload) {
                return;
            }

            this.clearMessages();
            this.isLoading = true;

            try {
                const targetId = this.pendingUploadProductId;
                const uploadResult = await this.uploadProductImage(targetId);

                if (uploadResult.success) {
                    this.successMessage = 'Tải ảnh sản phẩm lên thành công!';
                    this.pendingUploadProductId = null;
                    this.resetForm();
                    await this.fetchProducts(this.pagination.page);
                } else {
                    this.errorMessage = uploadResult.message + ' Bạn có thể chọn lại ảnh hợp lệ và bấm "Lưu cập nhật" / "Thử lại".';
                }
            } finally {
                this.isLoading = false;
            }
        },

        /* 
         * Handle form submit event and route to create or update product.
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
         * Create a new product (JSON POST), then upload image if selected, and load page 1.
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

                if (!result.success) {
                    this.errorMessage = result.message || 'Không thể thêm sản phẩm.';
                    return;
                }

                const newProductId = result.id;

                if (this.selectedFile && newProductId) {
                    const uploadResult = await this.uploadProductImage(newProductId);

                    if (!uploadResult.success) {
                        await this.fetchProducts(1);
                        this.isEditing = true;
                        this.form.id = newProductId;
                        this.form.currentImage = null;
                        this.pendingUploadProductId = newProductId;
                        this.errorMessage = `Sản phẩm #${newProductId} đã được lưu nhưng tải ảnh thất bại: ${uploadResult.message}`;
                        return;
                    }
                }

                this.successMessage = result.message || 'Thêm sản phẩm thành công.';
                this.pendingUploadProductId = null;
                this.resetForm();
                await this.fetchProducts(1);
            } catch (error) {
                this.errorMessage = 'Lỗi gửi yêu cầu thêm sản phẩm: ' + error.message;
            } finally {
                this.isLoading = false;
            }
        },

        /* 
         * Populate the form with the selected product row for editing.
         */
        startEdit(product) {
            this.clearMessages();
            this.clearSelectedFile();
            this.isEditing = true;
            this.pendingUploadProductId = product.id;
            this.form = {
                id: product.id,
                name: product.name,
                price: product.price,
                stock: product.stock,
                currentImage: product.image || null
            };
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        /* 
         * Update product info (JSON PUT), then upload new image if selected, and reload current page.
         */
        async updateProduct() {
            this.isLoading = true;

            try {
                const targetId = this.form.id;
                const payload = {
                    name: this.form.name,
                    price: Number(this.form.price),
                    stock: Number(this.form.stock)
                };

                const response = await fetch(`api/products.php?id=${targetId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (!result.success) {
                    this.errorMessage = result.message || 'Không thể cập nhật sản phẩm.';
                    return;
                }

                if (this.selectedFile) {
                    const uploadResult = await this.uploadProductImage(targetId);

                    if (!uploadResult.success) {
                        await this.fetchProducts(this.pagination.page);
                        this.pendingUploadProductId = targetId;
                        this.errorMessage = `Thông tin sản phẩm #${targetId} đã lưu nhưng tải ảnh mới thất bại: ${uploadResult.message}`;
                        return;
                    }
                }

                this.successMessage = result.message || 'Cập nhật sản phẩm thành công!';
                this.pendingUploadProductId = null;
                this.resetForm();
                await this.fetchProducts(this.pagination.page);
            } catch (error) {
                this.errorMessage = 'Lỗi gửi yêu cầu cập nhật: ' + error.message;
            } finally {
                this.isLoading = false;
            }
        },

        /* 
         * Confirm with user, send DELETE request, and reload current page.
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

                    await this.fetchProducts(this.pagination.page);
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
         * Reset the input form and file preview back to initial add mode.
         */
        resetForm() {
            this.clearSelectedFile();
            this.form = {
                id: null,
                name: '',
                price: '',
                stock: '',
                currentImage: null
            };
            this.isEditing = false;
            this.pendingUploadProductId = null;
        }
    }
}).mount('#app');