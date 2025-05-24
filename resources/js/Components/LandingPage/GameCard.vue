<script setup>
import { ref } from "vue";

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
});

const tiltStyles = ref({
    transform:
        "perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)",
});

const handleMouseMove = (e) => {
    const card = e.currentTarget;
    const cardRect = card.getBoundingClientRect();
    const cardCenterX = cardRect.left + cardRect.width / 2;
    const cardCenterY = cardRect.top + cardRect.height / 2;
    const mouseX = e.clientX - cardCenterX;
    const mouseY = e.clientY - cardCenterY;

    // Calculate rotation based on mouse position (max 10 degrees)
    const rotateY = (mouseX / cardRect.width) * 10;
    const rotateX = -((mouseY / cardRect.height) * 10);

    tiltStyles.value.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
};

const resetTilt = () => {
    tiltStyles.value.transform =
        "perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)";
};
</script>

<template>
    <div
        class="game-card glass-card overflow-hidden transition-all duration-300 h-full"
        @mousemove="handleMouseMove"
        @mouseleave="resetTilt"
        :style="tiltStyles"
    >
        <div class="relative h-60 overflow-hidden">
            <img
                :src="game.imageUrl"
                :alt="game.title"
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
            />
            <div class="absolute top-0 right-0 m-4">
                <span
                    class="bg-purple-600 text-white text-xs px-2 py-1 rounded-full"
                    >{{ game.genre }}</span
                >
            </div>
        </div>

        <div class="p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xl font-bold text-white">{{ game.title }}</h3>
                <div class="flex items-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-yellow-400"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                        />
                    </svg>
                    <span class="text-white ml-1">{{ game.rating }}</span>
                </div>
            </div>

            <p class="text-gray-300 mb-4">{{ game.description }}</p>

            <button
                class="w-full py-2 rounded-lg bg-gradient-to-r from-purple-500 to-cyan-400 text-white font-bold hover:shadow-lg hover:from-purple-600 hover:to-cyan-500 transition-all"
            >
                ¡Prueba Ahora!
            </button>
        </div>
    </div>
</template>
