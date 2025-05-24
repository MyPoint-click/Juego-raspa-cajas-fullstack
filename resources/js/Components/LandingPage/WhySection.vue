<script setup>
const benefits = [
    {
        title: "Diferenciación y Ventaja Competitiva", // Nuevo beneficio, muy relevante
        description:
            "Destaca en el mercado ofreciendo una experiencia única que tu competencia no tiene. Atrae a nuevos clientes y consolida tu posición como líder innovador en tu sector.",
        icon: "M13 10V3L4 14h7v7l9-11h-7z",
    },
    {
        title: "Impulso de la Interacción y el Tráfico", // Más activo y enfocado en el resultado
        description:
            "Crea experiencias memorables que enganchan a tus clientes, generando más visitas a tu negocio, ya sea online o físico, y una participación activa con tu marca.",
        icon: "M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z",
    },
    {
        title: "Lealtad Inquebrantable a tu Marca", // Más fuerte que solo "Brand Loyalty"
        description:
            "Transforma a clientes ocasionales en embajadores de tu marca. Construye relaciones sólidas y duraderas a través de recompensas significativas y experiencias que los hacen sentir valorados.",
        icon: "M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z",
    },
];
</script>

<template>
    <section id="why" class="section-spacing py-20 bg-slate-900">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2
                    class="text-4xl md:text-5xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-cyan-400 pb-2"
                >
                    ¿Por qué elegirnos?
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Transforma tu negocio con una gamificación atractiva de
                    estrategias
                </p>
            </div>

            <div
                class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto"
            >
                <div
                    v-for="(benefit, index) in benefits"
                    :key="index"
                    class="glass-card p-8"
                    data-aos="fade-up"
                    :data-aos-delay="index * 100"
                >
                    <div
                        class="w-16 h-16 rounded-full bg-slate-700/50 flex items-center justify-center mb-6 mx-auto"
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
                                :d="benefit.icon"
                            />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-center">
                        {{ benefit.title }}
                    </h3>
                    <p class="text-gray-300 text-center">
                        {{ benefit.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
