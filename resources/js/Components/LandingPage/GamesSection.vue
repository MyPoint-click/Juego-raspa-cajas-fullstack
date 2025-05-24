<script setup>
import { ref, computed } from "vue";
import GameCard from "./GameCard.vue";

const games = ref([
    {
        id: 1,
        title: "Caja Sorpresa: ¡Elige tu Recompensa!",
        description:
            "Cautiva a tus clientes y dispara la interacción en tu web.Ideal para campañas express, lanzamientos o simplemente para añadir un toque de diversión que ¡multiplica tus conversiones y la recurrencia de tus clientes!",
        imageUrl: "/images/landing-page/game-section/box-game.png",
        genre: "Fidelización Instantánea",
        rating: 5.0,
        gameUrl: "/box-game",
    },
    {
        id: 2,
        title: "Raspa y Gana: ¡Premios al Instante, Clientes Fieles!",
        description:
            "Genera emoción y acciones inmediatas. Tus clientes rascan tarjetas virtuales para revelar descuentos exclusivos, ofertas especiales o regalos sorpresa. Perfecto para impulsar ventas rápidas, liquidar stock o crear una experiencia memorable que incentiva la compra y el retorno a tu negocio.",
        imageUrl: "/images/landing-page/game-section/scratch-game.png",
        genre: "Fidelización Instantánea",
        rating: 5.0,
        gameUrl: "/scratch-game",
    },
]);

const genres = ref(["All", "Interactive", "Instant Win"]);
const selectedGenre = ref("All");

const filteredGames = computed(() => {
    if (selectedGenre.value === "All") {
        return games.value;
    } else {
        return games.value.filter((game) => game.genre === selectedGenre.value);
    }
});
</script>

<template>
    <section id="games" class="section-spacing py-20 bg-slate-900">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2
                    class="text-4xl md:text-5xl font-bold mb-4 pb-4 text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-cyan-400"
                >
                    Conoce Nuestros Juegos
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Impulsa la fidelización con experiencias interactivas y
                    premios irresistibles. Descubre cómo nuestros juegos
                    transforman cada interacción en una oportunidad de
                    crecimiento.
                </p>
            </div>

            <!-- Games Grid -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto"
            >
                <GameCard
                    v-for="game in filteredGames"
                    :key="game.id"
                    :game="game"
                    data-aos="fade-up"
                    :data-aos-delay="game.id * 0"
                />
            </div>
            <div
                class="text-center mt-12"
                data-aos="fade-up"
                data-aos-delay="200"
            >
                <a
                    href="https://wa.me/+59167567444?text=¡Hola! Me interesa saber más sobre los juegos de GamePoint 🎮"
                    target="_blank"
                    class="px-8 py-3 rounded-full text-white font-bold transition-all shadow-lg bg-gradient-to-r from-cyan-500 to-purple-500 hover:from-cyan-600 hover:to-purple-600"
                >
                    ¡Quiero Fidelizar a Mis Clientes!
                </a>
            </div>
        </div>
    </section>
</template>
