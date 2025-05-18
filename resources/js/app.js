import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PermissionsMixin from './mixins/permissions'; // Import the mixin
import '@fortawesome/fontawesome-free/css/all.css';
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import '@fortawesome/fontawesome-free/css/all.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        // Create Vue app
        const vueApp = createApp({ render: () => h(App, props) });

        // Use plugins
        vueApp.use(plugin);
        vueApp.use(ZiggyVue);

        // Register the mixin globally
        vueApp.mixin(PermissionsMixin);

        // Mount the app
        vueApp.mount(el);

        // Use Element Plus
        vueApp.use(ElementPlus);
    },
    progress: {
        color: '#4B5563',
    },
});
