<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const isScrolled = ref(false);
const isMobileMenuOpen = ref(false);

const checkScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

onMounted(() => {
    window.addEventListener("scroll", checkScroll);
});

onUnmounted(() => {
    window.removeEventListener("scroll", checkScroll);
});

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};
</script>

<template>
    <header
        :class="[
            'fixed top-0 left-0 right-0 z-40 transition-all duration-300',
            isScrolled
                ? 'bg-slate-900/90 backdrop-blur-md py-2 shadow-lg'
                : 'bg-transparent py-4',
        ]"
    >
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="#" class="text-2xl font-bold tracking-wider">
                    <span class="text-purple-500">Game</span>
                    <span class="text-cyan-400">Point</span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a
                        href="#hero"
                        class="nav-link text-gray-300 hover:text-white"
                        >Inicio</a
                    >
                    <a
                        href="#games"
                        class="nav-link text-gray-300 hover:text-white"
                        >Juegos</a
                    >

                    <a
                        href="#how-it-works"
                        class="nav-link text-gray-300 hover:text-white"
                        >¿Cómo funciona?</a
                    >
                    <a
                        href="#why"
                        class="nav-link text-gray-300 hover:text-white"
                        >¿Por qué elegirnos?</a
                    >
                </nav>

                <!-- Mobile Menu Button -->
                <button
                    class="md:hidden text-white focus:outline-none"
                    @click="toggleMobileMenu"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            v-if="!isMobileMenuOpen"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            v-else
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <div
                v-if="isMobileMenuOpen"
                class="md:hidden mt-4 bg-slate-800/95 backdrop-blur-md rounded-lg py-4 px-2 transition-all duration-300 ease-in-out"
            >
                <nav class="flex flex-col space-y-4">
                    <a
                        href="#hero"
                        class="nav-link text-gray-300 hover:text-white px-4 py-2"
                        @click="isMobileMenuOpen = false"
                        >Inicio</a
                    >
                    <a
                        href="#games"
                        class="nav-link text-gray-300 hover:text-white px-4 py-2"
                        @click="isMobileMenuOpen = false"
                        >Juegos</a
                    >
                    <a
                        href="#how-it-works"
                        class="nav-link text-gray-300 hover:text-white px-4 py-2"
                        @click="isMobileMenuOpen = false"
                        >¿Cómo funciona?</a
                    >
                    <a
                        href="#why"
                        class="nav-link text-gray-300 hover:text-white px-4 py-2"
                        @click="isMobileMenuOpen = false"
                        >¿Por qué elegirnos?</a
                    >
                </nav>
            </div>
        </div>
    </header>
</template>
