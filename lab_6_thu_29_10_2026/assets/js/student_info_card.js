/*
 * Academic ranking criteria table (order from highest to lowest score).
 */
const ACADEMIC_CONFIG = [
    {
        minScore: 8.0,
        label: 'Giỏi',
        badgeClass: 'badge--success'
    },
    {
        minScore: 6.5,
        label: 'Khá',
        badgeClass: 'badge--info'
    },
    {
        minScore: 5.0,
        label: 'Trung bình',
        badgeClass: 'badge--warning'
    },
    {
        minScore: 0.0,
        label: 'Chưa đạt',
        badgeClass: 'badge--danger'
    }
];

/*
 * Student enrollment status configuration.
 */
const STATUS_CONFIG = {
    ACTIVE: {
        value: true,
        label: 'Đang học'
    },
    WITHDRAWN: {
        value: false,
        label: 'Đã nghỉ'
    }
};

/* 
 * Default initial model data.
 */
const DEFAULT_STUDENT_DATA = {
    studentId: 'B22DCCN123',
    fullName: 'Nguyễn Văn An',
    className: 'D22CQCN01-B',
    avatarUrl: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
    avatarAlt: 'Ảnh đại diện của sinh viên Nguyễn Văn An',
    gpa: 8.5,
    isEnrolled: true,
    bio: 'Sinh viên năm 3 chuyên ngành công nghệ thông tin. Yêu thích lập trình giao diện người dùng và hệ sinh thái Vue.js'
};

// Local SVG placeholder used when image URL is broken or offline.
const FALLBACK_AVATAR = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='110' height='110' viewBox='0 0 24 24' fill='%2394a3b8'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E";

const { createApp } = Vue;

createApp({
    data() {
        return {
            statusConfig: STATUS_CONFIG,
            student: JSON.parse(JSON.stringify(DEFAULT_STUDENT_DATA)),
            isBioVisible: true
        };
    },

    methods: {
        /* 
         * Evaluate current GPA against the configuration table and return ranking details.
         */
        getAcademicRank() {
            const currentScore = Number(this.student.gpa) || 0;
            const matchedTier = ACADEMIC_CONFIG.find(tier => currentScore >= tier.minScore);

            return matchedTier || ACADEMIC_CONFIG[ACADEMIC_CONFIG.length - 1];
        },

        /* 
         * Toggle the visibility state of the biography block.
         */
        toggleBioVisibility() {
            this.isBioVisible = !this.isBioVisible;
        },

        /* 
         * Gracefully handle broken avatar images by applying local SVG placeholder.
         */
        handleAvatarFallback(event) {
            event.target.src = FALLBACK_AVATAR;
        },

        /* 
         * Reset reactive student state back to the initial configuration.
         */
        resetToDefaultData() {
            this.student = JSON.parse(JSON.stringify(DEFAULT_STUDENT_DATA));
            this.isBioVisible = true;
        }
    }
}).mount('#app');