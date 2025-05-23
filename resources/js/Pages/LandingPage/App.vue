<script setup>
import { ref, onMounted } from 'vue'
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'
import AudioPlayer from './components/AudioPlayer.vue'

const isLoading = ref(true)

onMounted(() => {
  // Simulate loading for a smoother experience
  setTimeout(() => {
    isLoading.value = false
  }, 1500)
})
</script>

<template>
  <div class="app-container relative">
    <!-- Loading Screen -->
    <div v-if="isLoading" class="fixed inset-0 flex items-center justify-center z-50 bg-slate-900">
      <div class="text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-4 glow-text text-cyan-400">NEXUS GAMING</h1>
        <div class="w-64 h-2 bg-slate-700 rounded-full overflow-hidden">
          <div class="h-full bg-gradient-to-r from-purple-500 to-cyan-400 animate-pulse"></div>
        </div>
      </div>
    </div>
    
    <!-- Main Content -->
    <div v-else>
      <Navbar />
      <main>
        <router-view />
      </main>
      <AudioPlayer />
      <Footer />
    </div>
  </div>
</template>

<style>
.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
}
</style>