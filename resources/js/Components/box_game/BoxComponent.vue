<script>
import { ref, computed } from "vue";

export default {
    props: {
        boxNumber: Number,
        selected: Boolean,
        isWinner: Boolean,
        isLoser: Boolean,
        gameOver: Boolean,
        disabled: Boolean,
        prizeCode: String,
    },
    emits: ["select"],
    setup(props, { emit }) {
        const showPromoModal = ref(false);
        const isOpened = computed(() => {
            if (props.selected && props.isWinner) {
                setTimeout(() => {
                    showPromoModal.value = true;
                }, 2500);
            }
            return props.selected && (props.isWinner || props.isLoser);
        });

        const boxState = computed(() => {
            if (props.isWinner) return "winner";
            if (props.isLoser) return "loser";
            if (props.selected) return "selected";
            return "default";
        });

        const boxColors = ref([
            "bg-primary-100 border-primary-300",
            "bg-secondary-100 border-secondary-300",
            "bg-accent-100 border-accent-300",
        ]);

        const boxColor = computed(() => {
            return boxColors.value[
                (props.boxNumber - 1) % boxColors.value.length
            ];
        });

        const handleClick = () => {
            if (!props.disabled) {
                emit("select", props.boxNumber);
            }
        };

        const ribbonColor = computed(() => {
            const colors = [
                "text-primary-500",
                "text-secondary-500",
                "text-accent-500",
            ];
            return colors[(props.boxNumber - 1) % colors.length];
        });

        return {
            showPromoModal,
            isOpened,
            boxState,
            boxColor,
            handleClick,
            ribbonColor,
        };
    },
};
</script>

<template>
    <div
        class="relative perspective box-container"
        :class="{ 'cursor-pointer': !disabled, 'cursor-default': disabled }"
    >
        <div
            class="box relative w-36 h-36 md:w-64 md:h-64"
            :class="{
                selected: selected && !isOpened,
                opened: isOpened,
            }"
            @click="handleClick"
        >
            <!-- Frente de la caja (cerrada) -->
            <div
                class="box-front flex flex-col items-center justify-center border-4 rounded-lg shadow-lg transform transition-all duration-500"
                :class="[boxColor, { 'animate-float': !selected && !gameOver }]"
            >
                <!-- Cinta y moño -->
                <div
                    class="absolute top-0 left-0 w-full h-full flex items-center justify-center"
                >
                    <div :class="ribbonColor" class="text-4xl md:text-6xl">
                        🎁
                    </div>
                </div>

                <!-- Número de la caja -->
                <div class="absolute bottom-2 md:bottom-4 w-full text-center">
                    <span
                        class="inline-block bg-white px-3 py-1 rounded-full text-gray-700 font-bold shadow-sm text-sm md:text-base"
                    >
                        Caja {{ boxNumber }}
                    </span>
                </div>
            </div>

            <!-- Ajustar el contenido del reverso también -->
            <div
                class="box-back rounded-lg shadow-lg border-4 flex items-center justify-center"
                :class="[
                    isWinner
                        ? 'bg-gradient-to-br from-yellow-200 to-yellow-400 border-yellow-500'
                        : 'bg-gray-200 border-gray-300',
                ]"
            >
                <div class="text-center p-4 md:p-6">
                    <div v-if="isWinner" class="animate-bounce-slow">
                        <div class="text-5xl md:text-7xl mb-0 md:mb-2">
                            <img src="/images/zapatos-mujer.png" alt="" />
                        </div>
                        <!-- <h3
              class="text-lg md:text-xl font-bold text-primary-800 mb-1 md:mb-2"
            >
              ¡Ganaste!
            </h3> -->
                        <p
                            class="text-sm md:text-base text-primary-600 font-semibold"
                        >
                            Código: {{ prizeCode }}
                        </p>
                    </div>
                    <div v-else>
                        <div class="text-5xl md:text-7xl mb-2 md:mb-4">😢</div>
                        <h3
                            class="text-lg md:text-xl font-bold text-gray-700 mb-1 md:mb-2"
                        >
                            Caja Vacía
                        </h3>
                        <p class="text-sm md:text-base text-gray-600">
                            ¡Inténtalo de nuevo!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal promocional -->
    <div
        v-if="showPromoModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
    >
        <div class="bg-white rounded-lg p-8 max-w-md mx-4 shadow-xl">
            <div class="text-center">
                <div class="text-5xl mb-4">
                    <img
                        class="h-36 mx-auto mb-4"
                        src="/images/MyPoint.click_vectorizado.png"
                        alt=""
                    />
                </div>
                <h2 class="text-2xl font-bold text-primary-800 mb-4">
                    ¡Conoce MyPoint.Click!
                </h2>
                <p class="text-gray-700 mb-4">
                    ¡Felicitaciones por tu premio! Mientras disfrutas de tu
                    descuento, queremos presentarte a MyPoint.Click, expertos en
                    desarrollo de software.
                </p>
                <p class="text-gray-600 mb-6">
                    Transformamos ideas en soluciones digitales innovadoras.
                </p>
                <div class="space-y-3">
                    <a
                        href="https://mypoint.click"
                        target="_blank"
                        class="block bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        Visitar nuestro sitio web
                    </a>
                    <button
                        @click="showPromoModal = false"
                        class="block w-full bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition-colors"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
