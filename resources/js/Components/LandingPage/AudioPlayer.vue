<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const isPlaying = ref(false)
const audioElement = ref(null)
const volume = ref(50)

onMounted(() => {
  audioElement.value = new Audio('https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3')
  audioElement.value.loop = true
  audioElement.value.volume = volume.value / 100
  
  // Listen for browser policy changes (autoplay blocking)
  audioElement.value.addEventListener('play', () => {
    isPlaying.value = true
  })
  
  audioElement.value.addEventListener('pause', () => {
    isPlaying.value = false
  })
})

onUnmounted(() => {
  if (audioElement.value) {
    audioElement.value.pause()
    audioElement.value = null
  }
})

const togglePlay = () => {
  if (audioElement.value) {
    if (isPlaying.value) {
      audioElement.value.pause()
    } else {
      audioElement.value.play()
    }
    isPlaying.value = !isPlaying.value
  }
}

const updateVolume = () => {
  if (audioElement.value) {
    audioElement.value.volume = volume.value / 100
  }
}
</script>

<template>
  <div class="fixed bottom-4 right-4 z-40">
    <div class="glass-card p-4 flex items-center space-x-4">
      <button 
        @click="togglePlay" 
        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-purple-500 to-cyan-400 text-white"
      >
        <svg v-if="!isPlaying" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </button>
      
      <div class="flex flex-col">
        <span class="text-xs text-gray-300 mb-1">Background Music</span>
        <input 
          type="range" 
          min="0" 
          max="100" 
          v-model="volume" 
          @input="updateVolume" 
          class="w-24 accent-purple-500"
        />
      </div>
    </div>
  </div>
</template>