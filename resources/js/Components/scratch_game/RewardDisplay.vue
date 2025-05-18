<script setup>
import { ref } from "vue";
import { Howl } from "howler";
import confetti from "canvas-confetti";

const props = defineProps({
    code: {
        type: String,
        required: true,
    },
    businessName: {
        type: String,
        default: "Negocio",
    },
});

const emit = defineEmits(["reset"]);

// Sound effect for copying code
const copiedSound = new Howl({
    src: ["/sounds/click.mp3"],
    volume: 0.5,
});

const isCopied = ref(false);

// Copy code to clipboard
const copyCode = async () => {
    try {
        await navigator.clipboard.writeText(props.code);
        copiedSound.play();
        isCopied.value = true;

        // Reset copy status after 2 seconds
        setTimeout(() => {
            isCopied.value = false;
        }, 2000);

        // Small confetti burst on copy
        confetti({
            particleCount: 30,
            spread: 50,
            origin: { y: 0.6 },
        });
    } catch (err) {
        console.error("Failed to copy code:", err);
    }
};
</script>

<template>
    <div class="w-full max-w-md">
        <div
            class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg shadow-lg p-6 text-center"
        >
            <div class="animate-bounce mb-4">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-12 w-12 mx-auto text-white"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>
            </div>

            <h3 class="text-white text-2xl font-bold mb-2">¡Felicidades!</h3>
            <p class="text-white text-sm mb-4">
                Has ganado un premio especial.
            </p>

            <div class="bg-white rounded-md p-4 mb-4">
                <h4 class="text-gray-700 text-sm font-medium mb-2">
                    TU CÓDIGO DE PREMIO
                </h4>
                <div class="flex items-center justify-center gap-2">
                    <span
                        class="text-2xl font-bold text-gray-800 tracking-wider"
                        >{{ props.code }}</span
                    >
                    <button
                        @click="copyCode"
                        class="p-1 text-gray-500 hover:text-gray-700 transition-colors"
                        :title="isCopied ? 'Copiado' : 'Copiar código'"
                    >
                        <svg
                            v-if="!isCopied"
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"
                            />
                        </svg>
                        <svg
                            v-else
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-green-500"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <p class="text-white text-sm">
                Presenta este código en {{ businessName }} para reclamar tu
                premio.
            </p>

            <div class="mt-6">
                <button
                    @click="emit('reset')"
                    class="px-4 py-2 bg-white text-primary-600 rounded-md font-medium hover:bg-gray-100 transition-colors"
                >
                    Volver a jugar
                </button>
            </div>
        </div>
    </div>
</template>
