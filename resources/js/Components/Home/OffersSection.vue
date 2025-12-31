<template>
  <section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Special Offers</h2>
        <p class="text-gray-600">Don't miss out on our exclusive deals</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="offer in offers" 
          :key="offer.id"
          :class="[
            'offer-card relative overflow-hidden cursor-pointer group',
            offer.size === 'large' ? 'md:col-span-2' : ''
          ]"
        >
          <img 
            :src="offer.image" 
            :alt="offer.title"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
            :class="offer.size === 'large' ? 'h-[500px]' : 'h-[500px]'"
          >
          
          <!-- Overlay for large card -->
          <div 
            v-if="offer.overlay"
            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"
          ></div>

          <!-- Content -->
          <div 
            :class="[
              'absolute',
              offer.size === 'large' ? 'bottom-10 left-10' : 'top-6 left-6'
            ]"
          >
            <span 
              v-if="offer.badge"
              :class="[
                'inline-block px-4 py-2 rounded-full text-sm font-bold mb-3',
                offer.badgeColor || 'bg-red-600 text-white'
              ]"
            >
              {{ offer.badge }}
            </span>

            <h3 
              :class="[
                'font-bold mb-2',
                offer.size === 'large' ? 'text-4xl text-white' : 'text-2xl text-white'
              ]"
            >
              {{ offer.title }}
            </h3>
            
            <p v-if="offer.subtitle" class="text-white text-lg mb-2">{{ offer.subtitle }}</p>
            <p v-if="offer.discount" class="text-yellow-400 text-2xl font-bold">{{ offer.discount }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
defineProps({
  offers: {
    type: Array,
    required: true
  }
});
</script>