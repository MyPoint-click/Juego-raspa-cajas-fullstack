<script setup>
import { ref, computed } from 'vue'
import GameCard from './GameCard.vue'

const games = ref([
  {
    id: 1,
    title: 'Mystery Box Game',
    description: 'An exciting game where users can win amazing prizes and discounts by selecting and opening virtual boxes.',
    imageUrl: 'https://images.pexels.com/photos/821651/pexels-photo-821651.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    genre: 'Interactive',
    rating: 4.8
  },
  {
    id: 2,
    title: 'Scratch & Win',
    description: 'Scratch virtual cards to reveal instant prizes, discounts, and special offers.',
    imageUrl: 'https://images.pexels.com/photos/7376/startup-photos.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    genre: 'Instant Win',
    rating: 4.9
  }
])

const genres = ref(['All', 'Interactive', 'Instant Win'])
const selectedGenre = ref('All')

const filteredGames = computed(() => {
  if (selectedGenre.value === 'All') {
    return games.value
  } else {
    return games.value.filter(game => game.genre === selectedGenre.value)
  }
})
</script>

<template>
  <section id="games" class="section-spacing py-20 bg-slate-900">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12" data-aos="fade-up">
        <h2 class="text-4xl md:text-5xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-cyan-400">
          Our Engagement Games
        </h2>
        <p class="text-xl text-gray-300 max-w-3xl mx-auto">
          Interactive games designed to boost customer engagement and loyalty through exciting rewards
        </p>
      </div>

      <!-- Games Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <GameCard 
          v-for="game in filteredGames" 
          :key="game.id" 
          :game="game" 
          data-aos="fade-up"
          :data-aos-delay="game.id * 100"
        />
      </div>
    </div>
  </section>
</template>