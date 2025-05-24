<script setup>
import { onMounted } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

const steps = [
    {
        title: "Admin Dashboard",
        description:
            "Access your dedicated dashboard to manage codes, track usage, and analyze customer engagement.",
        icon: "M4 6h16M4 10h16M4 14h16M4 18h16",
    },
    {
        title: "Code Management",
        description:
            "Create and manage reward codes, set validity periods, and control distribution limits.",
        icon: "M12 6v6m0 0v6m0-6h6m-6 0H6",
    },
    {
        title: "Analytics & Insights",
        description:
            "Monitor game performance, redemption rates, and customer engagement metrics in real-time.",
        icon: "M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z",
    },
    {
        title: "Customer Support",
        description:
            "Built-in tools to handle customer inquiries and manage code redemption issues.",
        icon: "M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z",
    },
];

gsap.registerPlugin(ScrollTrigger);

onMounted(() => {
    const timeline = gsap.timeline({
        scrollTrigger: {
            trigger: ".process-steps",
            start: "top center",
            end: "bottom center",
            scrub: 1,
        },
    });

    timeline.to(".connection-lines", {
        height: "100%",
        duration: 1,
        ease: "power2.out",
        stagger: 0.2,
    });
});
</script>

<template>
    <section
        id="how-it-works"
        class="section-spacing py-20 relative bg-slate-800"
    >
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2
                    class="text-4xl md:text-5xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-cyan-400"
                >
                    Admin Management
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Easy-to-use tools for managing your reward codes and
                    tracking customer engagement
                </p>
            </div>

            <div class="process-steps max-w-5xl mx-auto">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8"
                >
                    <!-- Líneas de conexión -->
                    <div
                        class="connection-lines hidden lg:block absolute top-1/2 left-0 w-full"
                    >
                        <div
                            class="h-px bg-gradient-to-r from-purple-500 to-cyan-400 w-full"
                        ></div>
                    </div>

                    <div
                        v-for="(step, index) in steps"
                        :key="index"
                        class="glass-card p-6 relative z-10"
                        data-aos="fade-up"
                        :data-aos-delay="index * 300"
                    >
                        <div
                            class="w-16 h-16 rounded-full bg-slate-700/50 flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-8 w-8 text-cyan-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    :d="step.icon"
                                />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-center">
                            {{ step.title }}
                        </h3>
                        <p class="text-gray-300 text-center">
                            {{ step.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
