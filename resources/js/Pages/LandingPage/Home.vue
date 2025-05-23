<script setup>
import { Head } from "@inertiajs/vue3";
import HeroSection from "@/Components/LandingPage/HeroSection.vue";
import GamesSection from "@/Components/LandingPage/GamesSection.vue";
import HowItWorksSection from "@/Components/LandingPage/HowItWorksSection.vue";
import WhySection from "@/Components/LandingPage/WhySection.vue";
import Navbar from "@/Components/LandingPage/Navbar.vue";
import Footer from "@/Components/LandingPage/Footer.vue";
import AudioPlayer from "@/Components/LandingPage/AudioPlayer.vue";
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-slate-900">
        <Navbar />
        <div class="home">
            <HeroSection />
            <GamesSection />
            <HowItWorksSection />
            <WhySection />
        </div>
        <AudioPlayer />
        <Footer />
    </div>
</template>
