<script setup>
const benefits = [
  {
    title: 'Increased Customer Engagement',
    description: 'Interactive games create memorable experiences that keep customers coming back for more.',
    icon: 'M13 10V3L4 14h7v7l9-11h-7z'
  },
  {
    title: 'Data-Driven Insights',
    description: 'Gather valuable customer behavior data to optimize your marketing strategies.',
    icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
  },
  {
    title: 'Brand Loyalty',
    description: 'Build stronger relationships with customers through rewarding experiences.',
    icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'
  }
]
</script>

<template>
  <section id="why" class="section-spacing py-20 bg-slate-900">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-4xl md:text-5xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-cyan-400">
          Why Choose Our Platform
        </h2>
        <p class="text-xl text-gray-300 max-w-3xl mx-auto">
          Transform your business with engaging gamification strategies
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
        <div 
          v-for="(benefit, index) in benefits" 
          :key="index"
          class="glass-card p-8"
          data-aos="fade-up"
          :data-aos-delay="index * 100"
        >
          <div class="w-16 h-16 rounded-full bg-slate-700/50 flex items-center justify-center mb-6 mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="benefit.icon" />
            </svg>
          </div>
          <h3 class="text-xl font-bold mb-4 text-center">{{ benefit.title }}</h3>
          <p class="text-gray-300 text-center">{{ benefit.description }}</p>
        </div>
      </div>
    </div>
  </section>
</template>