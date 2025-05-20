<script setup>
import { ref, onMounted } from "vue";
import BoxComponent from "@/Components/box_game/BoxComponent.vue";
import confetti from "canvas-confetti";

// Constantes
const TOTAL_BOXES = 3;
const DEFAULT_DISCOUNT = "20% DE DESCUENTO";

// Estado
const businessName = ref("Nuestra Tienda");
const attempts = ref(0);
const maxAttempts = ref(2);
const gameState = ref("initial");
const winningBox = ref(Math.floor(Math.random() * TOTAL_BOXES) + 1);
const selectedBox = ref(null);
const discount = ref(DEFAULT_DISCOUNT);

// Sonido de victoria
const winSound = new Audio("/sounds/tada.mp3");

// Propiedades computadas
const canSelectBox = () =>
    gameState.value === "playing" && attempts.value < maxAttempts.value;
const isWinner = () => selectedBox.value === winningBox.value;
const attemptText = () => (attempts.value === 0 ? "primera" : "segunda");
const boxesArray = Array.from({ length: TOTAL_BOXES }, (_, i) => i + 1);

// Funciones
const startGame = () => {
    gameState.value = "playing";
    attempts.value = 0;
    winningBox.value = Math.floor(Math.random() * TOTAL_BOXES) + 1;
    selectedBox.value = null;
};

const selectBox = (boxNumber) => {
    if (!canSelectBox() || selectedBox.value === boxNumber) return;

    selectedBox.value = boxNumber;
    attempts.value++;

    // Verificar condiciones de victoria/derrota
    if (isWinner()) {
        gameState.value = "won";
        // Reproducir sonido de victoria
        winSound.play();
        // Activar confeti
        setTimeout(() => {
            confetti({
                particleCount: 100,
                spread: 100,
                origin: { y: 0.6 },
            });
        }, 500);
    } else if (attempts.value >= maxAttempts.value) {
        gameState.value = "lost";
    }
};

const resetGame = () => {
    gameState.value = "initial";
    attempts.value = 0;
    selectedBox.value = null;
    winningBox.value = Math.floor(Math.random() * TOTAL_BOXES) + 1;
};

// Inicializar juego
onMounted(() => {
    businessName.value = "Especial para Mamá";
    discount.value = "25% DE DESCUENTO";
});
</script>

<template>
    <div
        class="container mx-auto px-4 py-8 flex flex-col items-center justify-center min-h-screen"
    >
        <!-- Encabezado -->
        <div class="text-center mb-8">
            <h1
                class="text-3xl md:text-4xl lg:text-5xl font-bold text-primary-700 mb-2"
            >
                ¡Feliz Día de las Madres!
            </h1>
            <h2 class="text-xl md:text-2xl text-secondary-600 mb-6">
                Oferta Especial de {{ businessName }}
            </h2>

            <div class="max-w-2xl mx-auto text-center">
                <p
                    class="text-lg text-gray-700 mb-4"
                    v-if="gameState === 'initial'"
                >
                    <!-- ¡Elige una caja de regalo y gana un descuento especial para el Día de
          las Madres! -->
                </p>
                <p
                    class="text-lg text-gray-700 mb-4"
                    v-else-if="gameState === 'playing'"
                >
                    ¡Esta es tu {{ attemptText() }} oportunidad! Elige
                    sabiamente...
                    <!-- ¡Esta es tu gran oportunidad! Elige sabiamente... -->
                </p>
                <p
                    class="text-xl font-bold text-primary-600 mb-4"
                    v-else-if="gameState === 'won'"
                >
                    <!-- ¡Felicitaciones! ¡Ganaste {{ discount }} en tu próxima compra! -->
                    ¡Felicitaciones!, Reclama el código:123 en la tienda
                </p>
                <p
                    class="text-lg text-gray-600 mb-4"
                    v-else-if="gameState === 'lost'"
                >
                    ¡Lo siento! Mejor suerte la próxima vez.
                </p>
            </div>
        </div>

        <!-- Área de juego -->
        <div
            class="w-full max-w-4xl bg-white rounded-xl shadow-lg px-6 pt-0 pb-4 mb-8"
        >
            <!-- Cajas de juego -->
            <div
                class="grid grid-cols-2 min-[1035px]:flex min-[1035px]:flex-row justify-around items-center gap-4 my-8"
            >
                <!-- Dos cajas en la primera fila en móvil, todas en línea en desktop -->
                <div class="flex justify-center">
                    <BoxComponent
                        :box-number="1"
                        :selected="selectedBox === 1"
                        :is-winner="selectedBox === 1 && 1 === winningBox"
                        :is-loser="selectedBox === 1 && 1 !== winningBox"
                        :game-over="gameState === 'won' || gameState === 'lost'"
                        :disabled="!canSelectBox() || selectedBox === 1"
                        @select="selectBox(1)"
                    />
                </div>
                <div class="flex justify-center">
                    <BoxComponent
                        :box-number="2"
                        :selected="selectedBox === 2"
                        :is-winner="selectedBox === 2 && 2 === winningBox"
                        :is-loser="selectedBox === 2 && 2 !== winningBox"
                        :game-over="gameState === 'won' || gameState === 'lost'"
                        :disabled="!canSelectBox() || selectedBox === 2"
                        @select="selectBox(2)"
                    />
                </div>
                <div
                    class="flex justify-center col-span-2 min-[1035px]:col-span-1"
                >
                    <BoxComponent
                        :box-number="3"
                        :selected="selectedBox === 3"
                        :is-winner="selectedBox === 3 && 3 === winningBox"
                        :is-loser="selectedBox === 3 && 3 !== winningBox"
                        :game-over="gameState === 'won' || gameState === 'lost'"
                        :disabled="!canSelectBox() || selectedBox === 3"
                        @select="selectBox(3)"
                    />
                </div>
            </div>

            <!-- Controles del juego -->
            <div class="flex justify-center mt-8">
                <button
                    v-if="gameState === 'initial'"
                    @click="startGame"
                    class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-full shadow-md transition-all transform hover:scale-105"
                >
                    Comenzar Juego
                </button>

                <button
                    v-else-if="gameState === 'won' || gameState === 'lost'"
                    @click="resetGame"
                    class="px-6 py-3 bg-secondary-600 hover:bg-secondary-700 text-white font-bold rounded-full shadow-md transition-all transform hover:scale-105"
                >
                    Jugar de Nuevo
                </button>
            </div>
        </div>

        <!-- Pie de página -->
        <footer class="bg-white border-t py-8 mt-8">
            <div class="container mx-auto px-4">
                <div class="text-center">
                    <div class="mb-4">
                        <div class="flex justify-center">
                            <img
                                class="h-20"
                                src="/images/logomypoint.click.png"
                                alt=""
                            />
                        </div>

                        <h3 class="text-xl font-bold text-primary-700 mb-2">
                            MyPoint.Click
                        </h3>
                        <p class="text-gray-600">
                            Desarrollo de Software Innovador
                        </p>
                    </div>

                    <div class="flex justify-center space-x-6 mb-6">
                        <a
                            href="https://facebook.com/mypointclick"
                            target="_blank"
                            class="text-gray-600 hover:text-primary-600 transition-colors"
                        >
                            <i class="fab fa-facebook text-2xl"></i>
                        </a>
                        <a
                            href="https://twitter.com/mypointclick"
                            target="_blank"
                            class="text-gray-600 hover:text-primary-600 transition-colors"
                        >
                            <i class="fab fa-twitter text-2xl"></i>
                        </a>
                        <a
                            href="https://linkedin.com/company/mypointclick"
                            target="_blank"
                            class="text-gray-600 hover:text-primary-600 transition-colors"
                        >
                            <i class="fab fa-linkedin text-2xl"></i>
                        </a>
                        <a
                            href="https://instagram.com/mypointclick"
                            target="_blank"
                            class="text-gray-600 hover:text-primary-600 transition-colors"
                        >
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                    </div>

                    <p class="text-sm text-gray-500">
                        © {{ new Date().getFullYear() }} MyPoint.Click. Todos
                        los derechos reservados.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
