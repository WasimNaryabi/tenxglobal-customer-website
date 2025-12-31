<template>
  <section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Top Trending</h2>
        <p class="text-gray-600">Customer favorites</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Large Featured Card -->
        <div class="trending-card rounded-3xl p-10 text-white flex flex-col justify-between min-h-[500px]">
          <div>
            <h3 class="text-5xl font-bold mb-4">Butter Chicken Rice</h3>
            <p class="text-xl mb-8 text-red-100">A classic Indian dish with rich, creamy sauce</p>
          </div>
          <Link href="/menu" class="inline-block bg-white text-red-600 px-8 py-4 rounded-full font-bold hover:bg-gray-100 transition text-center">
            Explore Menu
          </Link>
        </div>

        <!-- Grid of 6 Items -->
        <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-3 gap-4">
          <div 
            v-for="item in items" 
            :key="item.id"
            class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer"
          >
            <div class="relative">
              <img :src="item.image" :alt="item.name" class="w-full h-32 object-cover group-hover:scale-110 transition-transform duration-300">
            </div>
            <div class="p-4">
              <h4 class="font-bold text-gray-900 mb-1 text-sm">{{ item.name }}</h4>
              <p class="text-xs text-gray-600 mb-2">{{ item.description }}</p>
              <div class="flex justify-between items-center">
                <span class="text-red-600 font-bold">${{ item.price }}</span>
                <button 
                  @click="$emit('add-to-cart', item)"
                  class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold hover:bg-red-700 transition"
                >
                  Add
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  items: {
    type: Array,
    required: true
  }
});

defineEmits(['add-to-cart']);
</script>