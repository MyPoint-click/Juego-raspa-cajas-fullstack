<script setup>
import { onMounted, ref, onBeforeUnmount } from "vue";
import { Howl } from "howler";
import confetti from "canvas-confetti";

const props = defineProps({
    sectors: {
        type: Array,
        required: true,
    },
    wheelColor: {
        type: String,
        default: "#333",
    },
});

const emit = defineEmits(["revealed"]);

// Sound effects
const spinningSound = new Howl({
    src: ["/sounds/wheel-game/spin-wheel.mp3"], // Add your spinning sound
    volume: 0.5,
    loop: true,
});

const winSound = new Howl({
    src: ["/sounds/tada.mp3"],
    volume: 0.7,
});
const loseSound = new Howl({
    src: ["/sounds/game-over.mp3"],
    volume: 0.7,
});

const canvas = ref(null);
const isSpinning = ref(false);
const angle = ref(0);
const angularVelocity = ref(0);
const friction = 0.988; // Friction to slow down the wheel
const sector = ref(false);

// Draw the wheel on the canvas
const drawWheel = () => {
    const ctx = canvas.value.getContext("2d");
    const diameter = canvas.value.width;
    const radius = diameter / 2;
    const arcAngle = (2 * Math.PI) / props.sectors.length;

    props.sectors.forEach((sector, index) => {
        const startAngle = arcAngle * index;

        // Draw sector
        ctx.save();
        ctx.beginPath();
        ctx.fillStyle = sector.color;
        ctx.moveTo(radius, radius);
        ctx.arc(radius, radius, radius, startAngle, startAngle + arcAngle);
        ctx.lineTo(radius, radius);
        ctx.fill();

        // Add label
        ctx.translate(radius, radius);
        ctx.rotate(startAngle + arcAngle / 2);
        ctx.textAlign = "right";
        ctx.fillStyle = sector.textColor;
        // ctx.font = 'bold 30px "Rajdhani", sans-serif';
        // Reducir tamaño de fuente y ajustar posición
        ctx.font = 'bold 20px "Rajdhani", sans-serif'; // Cambio de 30px a 20px
        // Ajustar posición del texto según el largo del label
        const textDistance = radius - 30; // Más cerca del centro
        ctx.fillText(sector.label, textDistance, 5); // Ajustado Y de 10 a 6
        // ctx.fillText(sector.label, radius - 10, 10);
        ctx.restore();
    });
};

const triggerWin = (currentSector) => {
    if (currentSector.isWinner) {
        // Play win sound
        winSound.play();

        // Launch confetti
        const canvasElement = canvas.value;
        const rect = canvasElement.getBoundingClientRect();
        const x = rect.left + rect.width / 2;
        const y = rect.top + rect.height / 2;

        const windowWidth = window.innerWidth;
        const windowHeight = window.innerHeight;

        // Convert to relative coordinates for confetti (0-1)
        const xPos = x / windowWidth;
        const yPos = y / windowHeight;

        // Create confetti
        confetti({
            particleCount: 150,
            spread: 100,
            origin: { x: xPos, y: yPos },
            colors: ["#f97316", "#0ea5e9", "#14b8a6", "#8b5cf6", "#ef4444"],
        });
    }
};

const spin = () => {
    if (isSpinning.value) return;

    isSpinning.value = true;
    angularVelocity.value = 0.25 + Math.random() * 0.2;

    // Start spinning sound
    spinningSound.play();
};

const updateFrame = () => {
    if (isSpinning.value) {
        angularVelocity.value *= friction;

        if (angularVelocity.value < 0.002) {
            isSpinning.value = false;
            angularVelocity.value = 0;

            // Stop spinning sound
            spinningSound.stop();

            const currentIndex =
                Math.floor(
                    props.sectors.length -
                        (angle.value / (2 * Math.PI)) * props.sectors.length
                ) % props.sectors.length;
            emit("revealed", props.sectors[currentIndex]);

            // Get the current sector
            const currentSector = props.sectors[currentIndex];

            // Trigger effects based on whether it's a winning sector
            if (currentSector.isWinner) {
                triggerWin(currentSector);
                // Emit after effects for winners
                setTimeout(() => {
                    emit("revealed", currentSector);
                }, 1000);
            } else {
                // Play lose sound and emit immediately for losers
                loseSound.play();
                emit("revealed", currentSector);
            }
        }

        angle.value += angularVelocity.value;
        angle.value %= 2 * Math.PI;

        canvas.value.style.transform = `rotate(${
            angle.value - Math.PI / 2
        }rad)`;
    }
    requestAnimationFrame(updateFrame);
};

// Cleanup sounds on component unmount
onBeforeUnmount(() => {
    spinningSound.stop();
});

onMounted(() => {
    drawWheel();
    updateFrame();
});
</script>

<template>
    <div class="wheel-container relative">
        <div class="wheel-pointer"></div>
        <div class="wheel-wrapper">
            <canvas
                ref="canvas"
                width="400"
                height="400"
                class="w-full max-w-[400px] md:max-w-[400px] sm:max-w-[300px] xs:max-w-[250px]"
            />
            <button
                @click="spin"
                :disabled="isSpinning"
                class="spin-button"
                :class="{
                    'sm:w-16 sm:h-16': true, // Botón más pequeño en móvil
                    'xs:w-12 xs:h-12': true, // Aún más pequeño en pantallas muy pequeñas
                }"
            >
                {{ isSpinning ? "Girando..." : "Girar" }}
            </button>
        </div>
    </div>
</template>

<style scoped>
.wheel-container {
    display: inline-block;
    position: relative;
}

.wheel-pointer {
    position: absolute;
    top: 0;
    left: calc(50% - 10px);
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 20px solid var(--primary-color);
    filter: drop-shadow(1px 2px 2px rgba(0, 0, 0, 0.5));
    z-index: 10;
}

.wheel-wrapper {
    position: relative;
}

.spin-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--secondary-color);
    color: white;
    border: none;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
}

.spin-button:hover:not(:disabled) {
    transform: translate(-50%, -50%) scale(1.1);
}

.spin-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>
