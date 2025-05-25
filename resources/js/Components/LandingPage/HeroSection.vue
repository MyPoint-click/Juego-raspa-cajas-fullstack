<script setup>
import { onMounted, ref } from "vue";
import { gsap } from "gsap";
const whatsappLink = `https://wa.me/+59167567444?text=${encodeURIComponent(
    "¡Hola! Me interesa saber más sobre los juegos de GamePoint 🎮"
)}`;
const particlesInit = async (engine) => {
    await loadFull(engine);
};

const particlesLoaded = async (container) => {
    console.log("Particles container loaded", container);
};

onMounted(() => {
    // Animate hero elements
    gsap.from(".hero-title", {
        y: 50,
        opacity: 0,
        duration: 1,
        ease: "power3.out",
        delay: 0.2,
    });

    gsap.from(".hero-subtitle", {
        y: 30,
        opacity: 0,
        duration: 1,
        ease: "power3.out",
        delay: 0.4,
    });

    gsap.from(".hero-buttons", {
        y: 30,
        opacity: 0,
        duration: 1,
        ease: "power3.out",
        delay: 0.6,
    });

    gsap.from(".hero-image", {
        scale: 0.8,
        opacity: 0,
        duration: 1.2,
        ease: "power3.out",
        delay: 0.8,
    });
});
</script>

<template>
    <section
        id="hero"
        class="relative min-h-screen flex items-center overflow-hidden hero-gradient section-spacing"
    >
        <!-- Particles Background -->
        <vue-particles
            id="tsparticles"
            :particlesInit="particlesInit"
            :particlesLoaded="particlesLoaded"
            :options="{
                background: {
                    color: {
                        value: 'transparent',
                    },
                },
                fpsLimit: 60,
                interactivity: {
                    events: {
                        onClick: {
                            enable: true,
                            mode: 'push',
                        },
                        onHover: {
                            enable: true,
                            mode: 'repulse',
                        },
                    },
                    modes: {
                        bubble: {
                            distance: 400,
                            duration: 2,
                            opacity: 0.8,
                            size: 40,
                        },
                        push: {
                            quantity: 4,
                        },
                        repulse: {
                            distance: 200,
                            duration: 0.4,
                        },
                    },
                },
                particles: {
                    color: {
                        value: '#8B5CF6',
                    },
                    links: {
                        color: '#3B82F6',
                        distance: 150,
                        enable: true,
                        opacity: 0.3,
                        width: 1,
                    },
                    move: {
                        enable: true,
                        speed: 1,
                    },
                    number: {
                        density: {
                            enable: true,
                            area: 800,
                        },
                        value: 80,
                    },
                    opacity: {
                        value: 0.5,
                    },
                    size: {
                        value: 4,
                    },
                },
                detectRetina: true,
            }"
            class="absolute inset-0 z-0"
        />

        <div class="container mx-auto px-4 z-10 py-20">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="w-full md:w-1/2 text-center md:text-left">
                    <h1
                        class="hero-title text-4xl md:text-6xl lg:text-7xl font-bold mb-4"
                    >
                        <span class="block text-white"
                            >MyPoint.Click Presenta</span
                        >
                        <span
                            class="block text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-cyan-400 glow-text"
                            >¡Game Point!</span
                        >
                    </h1>

                    <p
                        class="hero-subtitle text-xl md:text-2xl text-gray-300 mb-8"
                    >
                        Atrae, retén y deleita a tus clientes como nunca antes.
                        Con nuestros juegos interactivos, convierte cada visita
                        en una oportunidad de crecimiento para tu negocio.
                    </p>

                    <div
                        class="hero-buttons flex flex-col sm:flex-row justify-center md:justify-start gap-4"
                    >
                        <a
                            href="#games"
                            class="hero-button px-8 py-3 rounded-full text-white font-bold transition-all shadow-lg"
                        >
                            Ver juegos
                        </a>
                        <a
                            :href="whatsappLink"
                            target="_blank"
                            class="hero-button-whatsapp inline-flex items-center justify-center px-8 py-3 rounded-full text-white font-bold transition-all shadow-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 cursor-pointer"
                        >
                            <svg
                                class="w-6 h-6 mr-2"
                                fill="currentColor"
                                viewBox="0 0 448 512"
                            >
                                <path
                                    d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"
                                />
                            </svg>
                            Contáctanos
                        </a>
                        <!-- <a
                            href="#how-it-works"
                            class="px-8 py-3 rounded-full border-2 border-purple-500 text-white font-bold hover:bg-purple-500/20 transition-all"
                        >
                            ¿Cómo funciona?
                        </a> -->
                    </div>
                </div>

                <div class="w-full md:w-1/2 mt-12 md:mt-0">
                    <div class="hero-image relative float-animation">
                        <img
                            src="/images/landing-page/robot-mypoint.jpeg"
                            alt="Robot amigable representando la fidelización de clientes"
                            class="rounded-lg shadow-2xl border border-purple-500/30 glow-border"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent rounded-lg"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
