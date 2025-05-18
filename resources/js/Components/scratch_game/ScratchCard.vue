<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from "vue";
import confetti from "canvas-confetti";
import { Howl } from "howler";
import { throttle } from "../../utils/generalUtils.js";

const props = defineProps({
    cardColor: {
        type: String,
        default: "#0ea5e9",
    },
});

const emit = defineEmits(["revealed"]);

// State variables
const canvasRef = ref(null);
const ctx = ref(null);
const isDrawing = ref(false);
const lastX = ref(0);
const lastY = ref(0);
const scratchPercentage = ref(0);
const hasRevealed = ref(false);
const isMobile = ref(false);

// Sound effects
const scratchSound = new Howl({
    src: ["/sounds/plop.mp3"],
    volume: 0.5,
    loop: true,
});

const winSound = new Howl({
    src: ["/sounds/tada.mp3"],
    volume: 0.7,
});

// Initialize the canvas
onMounted(() => {
    const canvas = canvasRef.value;
    ctx.value = canvas.getContext("2d");

    // Set canvas dimensions based on its container
    resizeCanvas();

    // Fill canvas with scratch-off layer
    fillCanvas();

    // Check if device is mobile
    isMobile.value = "ontouchstart" in window;

    // Add event listeners based on device type
    if (isMobile.value) {
        canvas.addEventListener("touchstart", handleTouchStart);
        canvas.addEventListener("touchmove", handleTouchMove);
        canvas.addEventListener("touchend", handleTouchEnd);
    } else {
        canvas.addEventListener("mousedown", handleMouseDown);
        canvas.addEventListener("mousemove", handleMouseMove);
        canvas.addEventListener("mouseup", handleMouseUp);
        canvas.addEventListener("mouseleave", handleMouseUp);
    }

    // Add resize listener for responsive canvas
    window.addEventListener("resize", resizeCanvas);
});

// Clean up event listeners
onBeforeUnmount(() => {
    const canvas = canvasRef.value;

    if (isMobile.value) {
        canvas.removeEventListener("touchstart", handleTouchStart);
        canvas.removeEventListener("touchmove", handleTouchMove);
        canvas.removeEventListener("touchend", handleTouchEnd);
    } else {
        canvas.removeEventListener("mousedown", handleMouseDown);
        canvas.removeEventListener("mousemove", handleMouseMove);
        canvas.removeEventListener("mouseup", handleMouseUp);
        canvas.removeEventListener("mouseleave", handleMouseUp);
    }

    window.removeEventListener("resize", resizeCanvas);

    // Stop any playing sounds
    scratchSound.stop();
});

// Handlers for mouse events
const handleMouseDown = (e) => {
    isDrawing.value = true;
    const rect = canvasRef.value.getBoundingClientRect();
    lastX.value = e.clientX - rect.left;
    lastY.value = e.clientY - rect.top;
    scratchSound.play();
};

const handleMouseMove = (e) => {
    if (!isDrawing.value) return;

    const rect = canvasRef.value.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    scratch(x, y);
};

const handleMouseUp = () => {
    isDrawing.value = false;
    scratchSound.stop();
    checkRevealPercentage();
};

// Handlers for touch events
const handleTouchStart = (e) => {
    e.preventDefault();
    isDrawing.value = true;
    const rect = canvasRef.value.getBoundingClientRect();
    const touch = e.touches[0];
    lastX.value = touch.clientX - rect.left;
    lastY.value = touch.clientY - rect.top;
    scratchSound.play();
};

const handleTouchMove = (e) => {
    e.preventDefault();
    if (!isDrawing.value) return;

    const rect = canvasRef.value.getBoundingClientRect();
    const touch = e.touches[0];
    const x = touch.clientX - rect.left;
    const y = touch.clientY - rect.top;

    scratch(x, y);
};

const handleTouchEnd = (e) => {
    e.preventDefault();
    isDrawing.value = false;
    scratchSound.stop();
    checkRevealPercentage();
};

// Throttled function to check how much has been scratched
const checkRevealPercentage = throttle(() => {
    const canvas = canvasRef.value;
    const context = ctx.value;
    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
    const pixelData = imageData.data;

    let transparentPixels = 0;
    const totalPixels = canvas.width * canvas.height;

    // Count transparent pixels (alpha < 50)
    for (let i = 3; i < pixelData.length; i += 4) {
        if (pixelData[i] < 50) {
            transparentPixels++;
        }
    }

    // Calculate percentage scratched
    scratchPercentage.value = Math.floor(
        (transparentPixels / totalPixels) * 100
    );

    // If 70% scratched and not yet revealed, trigger confetti and emit revealed event
    if (scratchPercentage.value >= 70 && !hasRevealed.value) {
        hasRevealed.value = true;
        triggerWin();

        // Give a moment for the confetti to show before emitting
        setTimeout(() => {
            emit("revealed");
        }, 1500);
    }
}, 200);

// Scratch effect function
const scratch = (x, y) => {
    const context = ctx.value;
    const radius = 70; // Size of scratch "eraser"

    context.globalCompositeOperation = "destination-out";

    context.beginPath();
    context.lineWidth = radius * 2;
    context.lineCap = "round";
    context.lineJoin = "round";
    context.moveTo(lastX.value, lastY.value);
    context.lineTo(x, y);
    context.stroke();

    // Also add some "dust" circles for a more natural scratching effect
    for (let i = 0; i < 5; i++) {
        const offsetX = (Math.random() - 0.5) * radius;
        const offsetY = (Math.random() - 0.5) * radius;
        context.beginPath();
        context.arc(
            x + offsetX,
            y + offsetY,
            Math.random() * 5,
            0,
            Math.PI * 2
        );
        context.fill();
    }

    lastX.value = x;
    lastY.value = y;
};

// Resize canvas to match container
const resizeCanvas = () => {
    const canvas = canvasRef.value;
    const container = canvas.parentElement;

    canvas.width = container.offsetWidth;
    canvas.height = container.offsetHeight;

    // Refill canvas after resize
    fillCanvas();
};

// Fill canvas with scratch-off layer
const fillCanvas = () => {
    const canvas = canvasRef.value;
    const context = ctx.value;

    if (!context) return;

    // Create a pattern that looks like a scratch card
    context.fillStyle = props.cardColor;
    context.fillRect(0, 0, canvas.width, canvas.height);

    // Add some texture to make it look more like a scratch card
    for (let i = 0; i < (canvas.width * canvas.height) / 100; i++) {
        const x = Math.random() * canvas.width;
        const y = Math.random() * canvas.height;
        const radius = Math.random() * 2;

        context.globalCompositeOperation = "source-over";
        context.beginPath();
        context.arc(x, y, radius, 0, Math.PI * 2);
        context.fillStyle = "rgba(255, 255, 255, 0.1)";
        context.fill();
    }

    // Draw some lines for texture
    context.globalCompositeOperation = "source-over";
    for (let i = 0; i < 20; i++) {
        context.beginPath();
        context.moveTo(
            Math.random() * canvas.width,
            Math.random() * canvas.height
        );
        context.lineTo(
            Math.random() * canvas.width,
            Math.random() * canvas.height
        );
        context.strokeStyle = "rgba(255, 255, 255, 0.1)";
        context.lineWidth = Math.random() * 2;
        context.stroke();
    }

    // Reset to original operation
    context.globalCompositeOperation = "source-over";
};

// Trigger win animation and sound
const triggerWin = () => {
    // Play win sound
    winSound.play();

    // Launch confetti
    const canvasElement = canvasRef.value;
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
};
</script>

<template>
    <div
        class="relative w-full max-w-md aspect-[3/2] rounded-lg shadow-lg overflow-hidden"
    >
        <!-- Canvas for scratch card -->
        <canvas
            ref="canvasRef"
            class="absolute inset-0 cursor-pointer z-20 rounded-lg"
            width="300"
            height="200"
        ></canvas>

        <!-- Content beneath the scratch layer -->
        <div
            class="absolute inset-0 flex items-center justify-center z-10 bg-gradient-to-r from-secondary-500 to-secondary-600"
        >
            <div class="text-center p-4">
                <h3 class="text-white text-xl md:text-2xl font-bold mb-2">
                    ¡PREMIO!
                </h3>
                <p class="text-white text-sm md:text-base">
                    Raspa para ver tu código
                </p>
            </div>
        </div>

        <!-- Progress indicator -->
        <div
            class="absolute bottom-2 right-2 z-30 bg-white bg-opacity-80 py-1 px-2 rounded text-xs font-medium"
        >
            {{ scratchPercentage }}% rascado
        </div>
    </div>
</template>
