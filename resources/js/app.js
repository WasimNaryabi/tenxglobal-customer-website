import './bootstrap';
import '../css/app.css';  // Import CSS here
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createPinia } from 'pinia';

const pinia = createPinia();

createInertiaApp({
    title: (title) => title ? `${title} - ${import.meta.env.VITE_APP_NAME || 'Laravel'}` : import.meta.env.VITE_APP_NAME || 'Laravel',
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .mount(el);
    },
    progress: {
        delay: 250,
        color: '#dc2626',
        includeCSS: true,
        showSpinner: true,
    },
});