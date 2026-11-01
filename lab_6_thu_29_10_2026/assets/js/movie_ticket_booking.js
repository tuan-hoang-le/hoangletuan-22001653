/*
 * Ticket types configuration.
 */
const TICKET_TYPES = [
    {
        id: 'standard',
        name: 'Vé thường',
        price: 70000,
        description: 'Ghế tiêu chuẩn, âm thanh vòm sống động'
    },
    {
        id: 'vip',
        name: 'Vé vip',
        price: 100000,
        description: 'Ghế trung tâm êm ái, góc nhìn tối ưu'
    }
];

/* 
 * Available showtimes (at least 3 options as required).
 */
const SHOWTIMES = [
    {
        id: 'st-01',
        time: '14:30',
        room: 'Rạp 1',
        format: '2D phụ đề'
    },
    {
        id: 'st-02',
        time: '18:15',
        room: 'Rạp 3',
        format: '2D lồng tiếng'
    },
    {
        id: 'st-03',
        time: '20:45',
        room: 'Rạp 2',
        format: '3D phụ đề'
    },
    {
        id: 'st-04',
        time: '22:30',
        room: 'Rạp 5',
        format: '2D phụ đề'
    }
];

/*
 * Popcorn and drink combo add-on configuration.
 */
const COMBO_CONFIG = {
    id: 'popcorn-soda',
    name: 'Combo bắp nước',
    price: 45000,
    description: '1 bắp rang bơ lớn (caramel / phô mai) + 2 nước ngọt có ga'
};

/* 
 * Quantity boundary rules.
 */
const QUANTITY_LIMITS = {
    MIN: 1,
    MAX: 10
};

/* 
 * Initial state for form fields.
 */
const INITIAL_FORM_STATE = {
    customerName: '',
    selectedShowtimeId: SHOWTIMES[0].id,
    selectedTicketTypeId: TICKET_TYPES[0].id,
    ticketQuantity: 1,
    hasCombo: false
};

const { createApp } = Vue;

createApp({
    data() {
        return {
            ticketTypes: TICKET_TYPES,
            showtimes: SHOWTIMES,
            comboConfig: COMBO_CONFIG,
            quantityLimits: QUANTITY_LIMITS,
            form: { ...INITIAL_FORM_STATE },
            confirmedBooking: null
        };
    },

    computed: {
        /* 
         * Resolve the selected ticket type object based on selected radio value.
         */
        currentTicketType() {
            return this.ticketTypes.find(t => t.id === this.form.selectedTicketTypeId) || this.ticketTypes[0];
        },

        /* 
         * Resolve the selected showtime object.
         */
        currentShowtime() {
            return this.showtimes.find(s => s.id === this.form.selectedShowtimeId) || this.showtimes[0];
        },

        /* 
         * Calculate subtotal for tickets using formula: unit price * quantity
         */
        ticketSubtotal() {
            if (!this.isQuantityValid) {
                return 0;
            }

            return this.currentTicketType.price * this.form.ticketQuantity;
        },

        /* 
         * Compute overall total price according to the formula: total = (ticket price * quantity) + combo price
         */
        totalPrice() {
            const comboPrice = this.form.hasCombo ? this.comboConfig.price : 0;

            return this.ticketSubtotal + comboPrice;
        },

        /* 
         * Validate customer name (must not be empty after trimming).
         */
        isCustomerNameValid() {
            return typeof this.form.customerName === 'string' && this.form.customerName.trim().length > 0;
        },

        /* 
         * Validate quantity (must be an integer from 1 to 10).
         */
        isQuantityValid() {
            const qty = this.form.ticketQuantity;

            return Number.isInteger(qty) && qty >= this.quantityLimits.MIN && qty <= this.quantityLimits.MAX;
        },

        /* 
         * Determine if the submit button should be enabled ONLY when customer name is not empty AND quantity is valid.
         */
        isFormValid() {
            return this.isCustomerNameValid && this.isQuantityValid;
        }
    },

    methods: {
        /* 
         * Increment ticket quantity by 1 up to MAX limit.
         */
        increaseQuantity() {
            if (this.form.ticketQuantity < this.quantityLimits.MAX) {
                this.form.ticketQuantity += 1;
            }
        },

        /* 
         * Decrement ticket quantity by 1 down to MIN limit.
         */
        decreaseQuantity() {
            if (this.form.ticketQuantity > this.quantityLimits.MIN) {
                this.form.ticketQuantity -= 1;
            }
        },

        /* 
         * Reset form fields back to default state and clears receipt.
         */
        resetBookingForm() {
            this.form = {
                ...INITIAL_FORM_STATE
            };
            this.confirmedBooking = null;
        },

        /* 
         * Handle form submission, capture booking snapshot and display confirmation receipt on page.
         */
        handleBookingSubmit() {
            if (!this.isFormValid) {
                return;
            }

            this.confirmedBooking = {
                customerName: this.form.customerName,
                showtimeText: `${this.currentShowtime.time} - ${this.currentShowtime.room} (${this.currentShowtime.format})`,
                ticketTypeName: this.currentTicketType.name,
                quantity: this.form.ticketQuantity,
                hasCombo: this.form.hasCombo,
                totalAmount: this.totalPrice,
                bookedAt: new Date().toLocaleDateString('vi-VN', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                })
            };
        },

        /* 
         * Dismiss the confirmation receipt card.
         */
        dismissReceipt() {
            this.confirmedBooking = null;
        },

        /* 
         * Format numeric currency values into localized Vietnamese Dong.
         */
        formatCurrency(amount) {
            if (typeof amount !== 'number' || isNaN(amount)) {
                return '0 đ';
            }

            return amount.toLocaleString('vi-VN') + ' đ';
        }
    }
}).mount('#app');