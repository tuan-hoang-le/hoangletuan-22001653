/*
 * Filter state enumeration.
 */
const FILTER_TYPES = {
    ALL: 'ALL',
    ACTIVE: 'ACTIVE',
    COMPLETED: 'COMPLETED'
};

/* 
 * Filter tab metadata definitions.
 */
const FILTER_CONFIG = [
    {
        key: FILTER_TYPES.ALL,
        label: 'Tất cả'
    },
    {
        key: FILTER_TYPES.ACTIVE,
        label: 'Chưa hoàn thành'
    },
    {
        key: FILTER_TYPES.COMPLETED,
        label: 'Đã hoàn thành'
    }
];

/*
 * Contextual empty notifications based on active filter.
 */
const EMPTY_MESSAGES = {
    [FILTER_TYPES.ALL]: 'Danh sách công việc đang trống. Hãy thêm việc mới để bắt đầu!',
    [FILTER_TYPES.ACTIVE]: 'Tuyệt vời! Bạn không còn công việc nào chưa hoàn thành.',
    [FILTER_TYPES.COMPLETED]: 'Chưa có công việc nào được đánh dấu hoàn thành.'
};

/* 
 * Initial daily tasks data (id, title, completed).
 */
const INITIAL_TASKS = [
    {
        id: 1,
        title: 'Xem video bài giảng Vue.js cơ bản',
        completed: true
    },
    {
        id: 2,
        title: 'Làm bài tập thực hành số 1 và 2',
        completed: true
    },
    {
        id: 3,
        title: 'Hoàn thiện bài tập số 3 - Quản lý công việc',
        completed: false
    },
    {
        id: 4,
        title: 'Đọc tài liệu về Vue computed properties và watch',
        completed: false
    }
];

const { createApp } = Vue;

createApp({
    data() {
        return {
            filterList: FILTER_CONFIG,
            filterTypes: FILTER_TYPES,
            currentFilter: FILTER_TYPES.ALL,
            tasks: JSON.parse(JSON.stringify(INITIAL_TASKS)),
            newTaskTitle: ''
        };
    },

    computed: {
        /* 
         * Validate input title (must not be empty after trimming).
         */
        isInputValid() {
            return typeof this.newTaskTitle === 'string' && this.newTaskTitle.trim().length > 0;
        },

        /* 
         * Compute total number of tasks in system.
         */
        totalTasksCount() {
            return this.tasks.length;
        },

        /* 
         * Compute number of pending (active) tasks.
         */
        pendingTasksCount() {
            return this.tasks.filter(task => !task.completed).length;
        },

        /* 
         * Compute number of completed tasks.
         */
        completedTasksCount() {
            return this.tasks.filter(task => task.completed).length;
        },

        /* 
         * Evaluate and return filtered task list based on current active tab.
         */
        filteredTasks() {
            switch (this.currentFilter) {
                case FILTER_TYPES.ACTIVE:
                    return this.tasks.filter(task => !task.completed);
                case FILTER_TYPES.COMPLETED:
                    return this.tasks.filter(task => task.completed);
                case FILTER_TYPES.ALL:
                default:
                    return this.tasks;
            }
        },

        /* 
         * Resolve appropriate empty state notification.
         */
        emptyStateMessage() {
            return EMPTY_MESSAGES[this.currentFilter] || EMPTY_MESSAGES[FILTER_TYPES.ALL];
        }
    },

    methods: {
        /* 
         * Add new task to list upon button click or Enter key.
         */
        handleAddTask() {
            if (!this.isInputValid) {
                return;
            }

            const newTask = {
                id: this.generateUniqueId(),
                title: this.newTaskTitle.trim(),
                completed: false
            };

            this.tasks.unshift(newTask);
            this.newTaskTitle = '';
        },

        /* 
         * Remove a single task by its id.
         */
        deleteTask(taskId) {
            this.tasks = this.tasks.filter(task => task.id !== taskId);
        },

        /* 
         * Update active filter tab.
         */
        setFilter(filterKey) {
            this.currentFilter = filterKey;
        },

        /* 
         * Compute task count for each specific filter tab badge.
         */
        getFilterCount(filterKey) {
            if (filterKey === FILTER_TYPES.ACTIVE) {
                return this.pendingTasksCount;
            }

            if (filterKey === FILTER_TYPES.COMPLETED) {
                return this.completedTasksCount;
            }

            return this.totalTasksCount;
        },

        /* 
         * Remove all completed tasks in bulk.
         */
        clearCompletedTasks() {
            this.tasks = this.tasks.filter(task => !task.completed);
        },

        /* 
         * Generate unique identifier for newly created tasks.
         */
        generateUniqueId() {
            return Date.now() + Math.floor(Math.random() * 1000);
        }
    }
}).mount('#app');