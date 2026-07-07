import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import { createApp, Fragment, h } from 'vue';
import CookieConsent from '@/Components/Legal/CookieConsent.vue';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });

        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h(Fragment, [
                h(App, props),
                h(CookieConsent),
            ]),
        })
            .use(plugin)
            .use(createPinia())
            .mount(el);
    },
});