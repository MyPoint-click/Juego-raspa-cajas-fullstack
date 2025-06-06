<script setup>
import { onMounted } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

const demoCredentials = {
    email: "admin@gmail.com",
    password: "12345678",
};

const steps = [
    {
        title: "Tablero de Control Intuitivo",
        description:
            "Accede a tu panel de control personalizado y fácil de usar. Gestiona tus códigos en un solo lugar, sin complicaciones.",
        icon: "M4 6h16M4 10h16M4 14h16M4 18h16",
    },
    {
        title: "Gestión de Códigos Flexible",
        description:
            "Crea códigos de premio únicos con total flexibilidad. Define su validez, establece límites. ¡Automatiza tus incentivos!",
        icon: "M12 6v6m0 0v6m0-6h6m-6 0H6",
    },
    {
        title: "Gestión Práctica de Campañas",
        description:
            "Administra los códigos y la configuración de tus campañas de forma sencilla. Para tus promociones de fechas especiales.",
        icon: "M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z",
    },
    {
        title: "Soporte y Asistencia",
        description:
            "No estás solo. Nuestro soporte está listo para ayudarte con cualquier consulta. Asegura una experiencia fluida para ti y tus clientes.",
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
                    class="text-4xl md:text-5xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-cyan-400 pb-2"
                >
                    Administración de Códigos
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Con herramientas fáciles de usar para administrar sus
                    códigos de recompensa para mejorar la experiencia del
                    cliente
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
        <!-- Sección probar sistema -->
        <div class="mt-12 max-w-3xl mx-auto px-4 relative z-20">
            <div class="glass-card p-6" data-aos="fade-up">
                <div class="flex flex-col gap-4">
                    <div
                        class="flex flex-col md:flex-row items-start justify-between gap-6"
                    >
                        <div class="flex-1 relative z-20">
                            <h3
                                class="text-xl font-bold mb-2 text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-cyan-400 select-text"
                            >
                                ¿Quieres probar el sistema?
                            </h3>
                            <ul
                                class="text-sm text-gray-300 list-disc list-inside space-y-1 mb-4 select-text"
                            >
                                <li>Gestión de códigos de premio</li>
                                <li>Verificación de códigos</li>
                                <li>Eliminación individual o masiva</li>
                            </ul>
                            <div
                                class="flex flex-wrap gap-4 items-center text-sm select-text"
                            >
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-400">Email:</span>
                                    <code
                                        class="bg-slate-700/50 px-2 py-1 rounded text-cyan-400"
                                    >
                                        {{ demoCredentials.email }}
                                    </code>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-400"
                                        >Contraseña:</span
                                    >
                                    <code
                                        class="bg-slate-700/50 px-2 py-1 rounded text-cyan-400"
                                    >
                                        {{ demoCredentials.password }}
                                    </code>
                                </div>
                            </div>
                        </div>

                        <div
                            class="md:self-center w-full md:w-auto relative z-20"
                        >
                            <a
                                :href="route('admin.prize-codes.index')"
                                class="block w-full md:w-auto text-center shrink-0 px-6 py-2 rounded-full text-white font-bold transition-all shadow-lg bg-gradient-to-r from-purple-500 to-cyan-400 hover:from-purple-600 hover:to-cyan-500 cursor-pointer"
                                target="_blank"
                            >
                                Acceder al Panel
                            </a>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 text-center relative z-20">
                        * Cuenta de demostración *
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
