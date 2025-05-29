<script setup>
import { ref, computed, onMounted } from "vue";
import WheelComponent from "@/Components/wheel_game/WheelComponent.vue";
import BusinessConfig from "@/Components/scratch_game/BusinessConfig.vue";
import RewardDisplay from "@/Components/scratch_game/RewardDisplay.vue";
import axios from "axios";

// Definir las props
const props = defineProps({
    errors: Object,
    auth: Object,
    reservedCode: {
        type: String,
        default: null,
    },
});

const isConfigOpen = ref(false);
const isRevealed = ref(false);
const rewardCode = ref(""); // Añadimos esta línea
const isWinner = ref(false);

const isLoading = ref(false);
const error = ref(null);

// Obtener código desde el servidor
const getCode = async () => {
    try {
        isLoading.value = true;
        error.value = null;
        const response = await axios.post(route("wheel-game.get-code"));
        rewardCode.value = response.data.code;
        return true;
    } catch (err) {
        error.value = err.response?.data?.error || "Error al obtener el código";
        console.error("Error al obtener el código:", error.value);
        return false;
    } finally {
        isLoading.value = false;
    }
};

const businessConfig = ref({
    name: "Mi Negocio",
    type: "tienda",
    logo: "/public/images/logomypoint.click.png",
    primaryColor: "#0ea5e9",
    secondaryColor: "#14b8a6",
    instruction: "Gira la ruleta para obtener tu premio",
    footerText: "© 2025 Mi Negocio. Todos los derechos reservados.",
});

const toggleConfig = () => {
    isConfigOpen.value = !isConfigOpen.value;
};

const updateConfig = (newConfig) => {
    businessConfig.value = { ...businessConfig.value, ...newConfig };
    isConfigOpen.value = false;
};

const wheelSectors = [
    {
        color: "#ffcd01",
        textColor: "#b20e12",
        label: "¡Premio!",
        isWinner: true,
    },
    {
        color: "#685ca2",
        textColor: "#ffffff",
        label: "¡Continua!",
        isWinner: false,
    },
    {
        color: "#029ede",
        textColor: "#ffffff",
        label: "¡Premio!",
        isWinner: true,
    },
    {
        color: "#a7d02a",
        textColor: "#ffffff",
        label: "¡Casi!",
        isWinner: false,
    },
    {
        color: "#26cda2",
        textColor: "#ffffff",
        label: "¡Tú puedes!",
        isWinner: false,
    },
    {
        color: "#8f3389",
        textColor: "#fcce03",
        label: "¡Falta poco!",
        isWinner: false,
    },
];

const handleReveal = async (sector) => {
    if (sector.isWinner) {
        const codeObtained = await getCode();

        if (codeObtained) {
            isWinner.value = true;
            isRevealed.value = true;
        } else {
            // Si no hay código disponible, tratar como perdedor
            sector.isWinner = false;
            isWinner.value = false;
            setTimeout(() => {
                isRevealed.value = false;
                isWinner.value = false;
            }, 2000);
        }
    } else {
        isWinner.value = false;
        setTimeout(() => {
            isRevealed.value = false;
            isWinner.value = false;
        }, 2000);
    }
};

const resetGame = () => {
    isRevealed.value = false;
    isWinner.value = false;
    rewardCode.value = "";
};

// Computed properties
const containerStyle = computed(() => ({
    "--primary-color": businessConfig.value.primaryColor,
    "--secondary-color": businessConfig.value.secondaryColor,
}));

onMounted(() => {
    if (props.reservedCode) {
        rewardCode.value = props.reservedCode;
        isRevealed.value = true;
        isWinner.value = true;
    }
});
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
        <main
            class="flex-grow flex flex-col items-center justify-center py-8 px-4 md:px-8"
        >
            <!-- Mensaje de error -->
            <div
                v-if="error"
                class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg"
            >
                {{ error }}
            </div>
            <div class="container mx-auto max-w-4xl">
                <!-- Estado de carga -->
                <div v-if="isLoading" class="text-center p-8">
                    <div
                        class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-primary-500 border-t-transparent"
                    ></div>
                    <p class="mt-2 text-gray-600">Cargando...</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 md:p-8">
                        <h2
                            class="text-2xl md:text-3xl font-bold text-center mb-6 text-gray-800"
                        >
                            Ruleta de Premios
                        </h2>

                        <div
                            class="flex flex-col md:flex-row gap-4 items-center justify-center"
                        >
                            <!-- Añadimos el mismo wrapper que tiene scratch-game -->
                            <div class="w-full md:w-1/2 flex justify-center">
                                <div v-if="isRevealed && isWinner">
                                    <RewardDisplay
                                        :code="rewardCode"
                                        :businessName="businessConfig.name"
                                        @reset="resetGame"
                                    />
                                </div>
                                <WheelComponent
                                    v-else
                                    :sectors="wheelSectors"
                                    @revealed="handleReveal"
                                />
                            </div>

                            <!-- Instructions section -->
                            <div
                                v-if="!isRevealed || !isWinner"
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
                                        Haz clic en el botón central para girar
                                        la ruleta
                                    </li>
                                    <li>Espera a que la ruleta se detenga</li>
                                    <li>¡Descubre tu premio!</li>
                                </ol>
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
