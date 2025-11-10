import './bootstrap';
import { createApp } from 'vue';

// Import Vue components
import BookSearch from './components/BookSearch.vue';
import ReadingProgress from './components/ReadingProgress.vue';
import ReviewForm from './components/ReviewForm.vue';

// Only mount Vue if there's a Vue root element
const vueRoot = document.querySelector('[data-vue]');
if (vueRoot) {
    const app = createApp({});

    // Register components
    app.component('book-search', BookSearch);
    app.component('reading-progress', ReadingProgress);
    app.component('review-form', ReviewForm);

    app.mount(vueRoot);
}
