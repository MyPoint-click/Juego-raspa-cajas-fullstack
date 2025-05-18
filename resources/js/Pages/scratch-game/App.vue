<script setup>
import { ref, computed } from "vue";
// import ScratchCard from "../../Components/scratch_game/ScratchCard";
// import BusinessConfig from "../../Components/scratch_game/BusinessConfig";
// import RewardDisplay from "../../Components/scratch_game/RewardDisplay.vue";
import ScratchCard from "@/Components/scratch_game/ScratchCard.vue";
import BusinessConfig from "@/Components/scratch_game/BusinessConfig.vue";
import RewardDisplay from "@/Components/scratch_game/RewardDisplay.vue";
import { generateCode } from "../../utils/rewardUtils.js";

// App state
const businessConfig = ref({
    name: "Mi Negocio",
    type: "tienda",
    logo: "https://via.placeholder.com/150",
    primaryColor: "#0ea5e9",
    secondaryColor: "#14b8a6",
    instruction: "Raspa la tarjeta para descubrir tu premio",
    footerText: "© 2025 Mi Negocio. Todos los derechos reservados.",
});

const isRevealed = ref(false);
const rewardCode = ref(generateCode());
const isConfigOpen = ref(false);

// Computed properties
const containerStyle = computed(() => ({
    "--primary-color": businessConfig.value.primaryColor,
    "--secondary-color": businessConfig.value.secondaryColor,
}));

// Methods
const onCardRevealed = () => {
    isRevealed.value = true;
};

const resetGame = () => {
    isRevealed.value = false;
    rewardCode.value = generateCode();
};

const toggleConfig = () => {
    isConfigOpen.value = !isConfigOpen.value;
};

const updateConfig = (newConfig) => {
    businessConfig.value = { ...businessConfig.value, ...newConfig };
    isConfigOpen.value = false;
};
</script>

<template>
    <div
        class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 flex flex-col"
        :style="containerStyle"
    >
        <!-- Header -->
        <header class="w-full bg-white shadow-sm py-4 px-6">
            <div class="container mx-auto flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img
                        :src="businessConfig.logo"
                        alt="Logo"
                        class="h-10 w-auto rounded"
                    />
                    <h1 class="text-xl md:text-2xl font-bold text-gray-800">
                        {{ businessConfig.name }}
                    </h1>
                </div>
                <button
                    @click="toggleConfig"
                    class="p-2 text-gray-600 hover:text-gray-800 transition-colors"
                    aria-label="Configuración"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main
            class="flex-grow flex flex-col items-center justify-center py-8 px-4 md:px-8"
        >
            <div class="container mx-auto max-w-4xl">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 md:p-8">
                        <h2
                            class="text-2xl md:text-3xl font-bold text-center mb-6 text-gray-800"
                        >
                            Programa de Fidelización
                        </h2>
                        <p class="text-center text-gray-600 mb-8">
                            {{ businessConfig.instruction }}
                        </p>

                        <div
                            class="flex flex-col md:flex-row gap-8 items-center justify-center"
                        >
                            <div class="w-full md:w-1/2 flex justify-center">
                                <ScratchCard
                                    v-if="!isRevealed"
                                    @revealed="onCardRevealed"
                                    :cardColor="businessConfig.primaryColor"
                                />
                                <RewardDisplay
                                    v-else
                                    :code="rewardCode"
                                    :businessName="businessConfig.name"
                                    @reset="resetGame"
                                />
                            </div>

                            <div
                                v-if="!isRevealed"
                                class="w-full md:w-1/2 p-4 bg-gray-50 rounded-lg"
                            >
                                <h3
                                    class="text-xl font-semibold mb-4 text-gray-800"
                                >
                                    Instrucciones
                                </h3>
                                <ol
                                    class="space-y-2 text-gray-600 list-decimal list-inside"
                                >
                                    <li>
                                        Raspa la tarjeta usando tu dedo o mouse
                                    </li>
                                    <li>Descubre tu código de premio</li>
                                    <li>
                                        Muestra el código en
                                        {{ businessConfig.name }} para reclamar
                                        tu premio
                                    </li>
                                </ol>
                                <p class="mt-4 text-sm text-gray-500">
                                    Promoción válida hasta agotar existencias.
                                    Consulta términos y condiciones.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full bg-gray-800 text-white py-6 px-4">
            <div class="container mx-auto text-center">
                <p>{{ businessConfig.footerText }}</p>
            </div>
        </footer>

        <!-- Configuration Modal -->
        <BusinessConfig
            v-if="isConfigOpen"
            :config="businessConfig"
            @update="updateConfig"
            @close="isConfigOpen = false"
        />
    </div>
</template>

<style>
:root {
    --primary-color: #0ea5e9;
    --secondary-color: #14b8a6;
}
</style>
