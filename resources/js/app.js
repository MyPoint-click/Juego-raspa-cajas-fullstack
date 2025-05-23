import "../css/app.css";
import "./bootstrap";
import "../css/scratch-game/scritch-style.css";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

// Importaciones para landing page
// import Particles from "vue3-particles";
import Particles from "@tsparticles/vue3";
import { loadSlim } from "@tsparticles/slim";

import AOS from "aos";
import "aos/dist/aos.css";

// Inicializar AOS
AOS.init({
    duration: 800,
    easing: "ease-in-out",
    once: true,
    mirror: false,
});
// .Importaciones para landing page

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return (
            createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue)
                // landing page
                .use(Particles, {
                    init: async (engine) => {
                        // await loadFull(engine); // you can load the full tsParticles library from "tsparticles" if you need it
                        await loadSlim(engine); // or you can load the slim version from "@tsparticles/slim" if don't need Shapes or Animations
                    },
                })
                // .landing page
                .mount(el)
        );
    },
    progress: {
        color: "#4B5563",
    },
});
