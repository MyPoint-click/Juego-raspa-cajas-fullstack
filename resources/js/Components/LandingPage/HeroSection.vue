<script setup>
import { onMounted, ref } from "vue";
import { gsap } from "gsap";

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
                        <span class="block text-white">Enter The</span>
                        <span
                            class="block text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-cyan-400 glow-text"
                            >GAMING UNIVERSE</span
                        >
                    </h1>

                    <p
                        class="hero-subtitle text-xl md:text-2xl text-gray-300 mb-8"
                    >
                        Experience the next generation of immersive gaming with
                        cutting-edge technology and stunning visuals.
                    </p>

                    <div
                        class="hero-buttons flex flex-col sm:flex-row justify-center md:justify-start gap-4"
                    >
                        <a
                            href="#games"
                            class="hero-button px-8 py-3 rounded-full text-white font-bold transition-all shadow-lg"
                        >
                            Explore Games
                        </a>
                        <a
                            href="#how-it-works"
                            class="px-8 py-3 rounded-full border-2 border-purple-500 text-white font-bold hover:bg-purple-500/20 transition-all"
                        >
                            Learn More
                        </a>
                    </div>
                </div>

                <div class="w-full md:w-1/2 mt-12 md:mt-0">
                    <div class="hero-image relative float-animation">
                        <img
                            src="/images/landing-page/robot-mypoint.jpeg"
                            alt="Gaming Setup"
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
