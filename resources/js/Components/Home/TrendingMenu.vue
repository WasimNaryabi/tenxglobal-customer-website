<template>
  <section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Trending Food Menu</h2>
        <p class="text-gray-600">Most popular dishes this week</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div 
          v-for="item in menuItems" 
          :key="item.id"
          class="bg-white rounded-xl p-6 shadow-sm hover:shadow-xl transition-shadow duration-300 cursor-pointer group"
        >
          <div class="flex justify-between items-start mb-3">
            <div class="flex-1">
              <h3 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-red-600 transition">
                {{ item.name }}
              </h3>
              <p class="text-sm text-red-600 font-semibold mb-2">{{ item.category }}</p>
            </div>
            <div class="text-2xl font-bold text-gray-900">${{ item.price }}</div>
          </div>
          
          <p class="text-gray-600 mb-4">{{ item.description }}</p>
          
          <button class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition opacity-0 group-hover:opacity-100">
            Order Now
          </button>
        </div>
      </div>

      <div class="text-center mt-12">
        <Link href="/menu" class="inline-block bg-red-600 text-white px-8 py-3 rounded-full font-bold hover:bg-red-700 transition">
          View All Menu
        </Link>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  menuItems: {
    type: Array,
    required: true
  }
});
</script>