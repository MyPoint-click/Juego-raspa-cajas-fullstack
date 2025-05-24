<script setup>
const currentYear = new Date().getFullYear();
const whatsappLink = `https://wa.me/+59167567444?text=${encodeURIComponent(
    "¡Hola! Me interesa saber más sobre los juegos de GamePoint 🎮"
)}`;

const socialLinks = [
    {
        name: "WhatsApp",
        href: whatsappLink,
        iconPath:
            "M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z",
        viewBox: "0 0 448 512",
        fill: "currentColor",
    },
    {
        name: "Facebook",
        href: "https://facebook.com/mypoint.click",
        iconPath:
            "M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z",
        viewBox: "0 0 24 24",
        fill: "none",
        stroke: "currentColor",
    },
    {
        name: "Email",
        href: "mailto:anibal.zapata@mypoint.click",
        iconPath:
            "M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z",
        viewBox: "0 0 24 24",
        fill: "none",
        stroke: "currentColor",
    },
];
</script>

<template>
    <footer class="bg-slate-900 border-t border-slate-800 relative z-10">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="text-center md:text-left">
                    <a
                        href="#"
                        class="text-2xl font-bold tracking-wider inline-block mb-3"
                    >
                        <span class="text-purple-500">Game</span>
                        <span class="text-cyan-400">Point</span>
                    </a>
                    <p class="text-gray-400 text-sm">
                        Creando experiencias únicas de juego para ti y tus
                        clientes
                    </p>
                </div>

                <div class="flex justify-center md:justify-end space-x-6">
                    <a
                        v-for="link in socialLinks"
                        :key="link.name"
                        :href="link.href"
                        :title="link.name"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-gray-400 hover:text-purple-500 transition-colors duration-300"
                    >
                        <span class="sr-only">{{ link.name }}</span>
                        <svg
                            class="h-6 w-6"
                            :fill="link.fill"
                            :viewBox="link.viewBox"
                            :stroke="link.stroke"
                        >
                            <path
                                :stroke-linecap="
                                    link.stroke ? 'round' : undefined
                                "
                                :stroke-linejoin="
                                    link.stroke ? 'round' : undefined
                                "
                                :stroke-width="link.stroke ? '2' : undefined"
                                :d="link.iconPath"
                            />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-slate-800">
                <p class="text-center text-gray-400 text-sm">
                    <a href="https://mypoint.click/" target="_blank">
                        © {{ currentYear }} MyPoint.Click. Todos los derechos
                        reservados.</a
                    >
                </p>
            </div>
        </div>
    </footer>
</template>
