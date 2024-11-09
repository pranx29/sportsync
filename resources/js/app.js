import "../css/app.css";
import "./bootstrap";

import { createInertiaApp, Head, Link, router } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .component("Head", Head)
            .component("Link", Link)
            .use(ZiggyVue)
            .mount(el);
    },

    progress: {
        color: "#0d8c8c",
        includeCSS: true,
        showSpinner: true,
    },
});
