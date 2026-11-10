/* 
 * Initial products collection.
 */
const CATALOG_PRODUCTS = [
    {
        id: 'sp-01',
        name: 'Đồng hồ thông minh Smartwatch Pro',
        price: 1890000,
        image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=400&q=80',
        description: 'Màn hình AMOLED sắc nét, theo dõi nhịp tim 24/7, kháng nước 5ATM và thời lượng pin ấn tượng lên tới 10 ngày.',
        stock: 12
    },
    {
        id: 'sp-02',
        name: 'Tai nghe chụp tai không dây ANC',
        price: 2450000,
        image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=400&q=80',
        description: 'Công nghệ chống ồn chủ động kép, đệm tai mút hoạt tính êm ái, âm bass sâu lắng và hỗ trợ kết nối đa điểm.',
        stock: 6
    },
    {
        id: 'sp-03',
        name: 'Ốp lưng MagSafe siêu mỏng chống sốc',
        price: 350000,
        image: 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?auto=format&fit=crop&w=400&q=80',
        description: 'Chất liệu Polycarbonate cao cấp chống ố vàng, lực hút nam châm chuẩn lực và viền bảo vệ cụm camera nhô cao.',
        stock: 0
    },
    {
        id: 'sp-04',
        name: 'Củ sạc nhanh 65W GaN 3 cổng',
        price: 490000,
        image: 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?auto=format&fit=crop&w=400&q=80',
        description: 'Công nghệ bán dẫn Gallium Nitride siêu nhỏ gọn, sạc cùng lúc laptop và 2 điện thoại với cơ chế tản nhiệt tối ưu.',
        stock: 18
    },
    {
        id: 'sp-05',
        name: 'Bàn di chuột chống trượt cỡ lớn (80x30 cm)',
        price: 190000,
        image: 'https://images.unsplash.com/photo-1616440347437-b1c73416efc2?auto=format&fit=crop&w=400&q=80',
        description: 'Bề mặt vải Speed mịn tối ưu hóa độ nhạy chuột, đế cao su thiên nhiên bám chặt mặt bàn và viền may bo chắc chắn.',
    }
];

const { createApp } = Vue;

/* 
 * Initialize parent Vue application instance.
 */
const app = createApp({
    data() {
        return {
            products: CATALOG_PRODUCTS,
            notificationMessage: ''
        };
    },

    methods: {
        /* 
         * Handle purchase event triggered from child <product-card> component.
         */
        handleProductBuy(productPayload) {
            this.notificationMessage = `Bạn vừa chọn mua "${productPayload.name}" thành công!`;

            setTimeout(() => {
                this.notificationMessage = '';
            }, 3000);
        }
    }
});

/* 
 * Register component globally BEFORE mounting the application.
 */
app.component('product-card', ProductCard);
app.mount('#app');