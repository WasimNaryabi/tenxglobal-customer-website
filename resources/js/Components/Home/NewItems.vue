<template>
  <section class="py-16 bg-teal-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold mb-4">New Items</h2>
        <p class="text-teal-100">Fresh additions to our menu</p>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
        <div 
          v-for="item in items" 
          :key="item.id"
          class="flex flex-col items-center group cursor-pointer relative"
        >
          <div class="relative">
            <div class="item-circle w-40 h-40 mb-4 overflow-hidden transition-transform duration-300 group-hover:scale-110">
              <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
            </div>
            <span class="badge-green absolute top-2 right-2">NEW</span>
          </div>
          
          <h3 class="font-bold text-white text-center mb-1">{{ item.name }}</h3>
          <p class="text-sm text-teal-100 mb-2">{{ item.description }}</p>
          <p class="text-yellow-400 font-bold text-lg">${{ item.priceRange }}</p>
          
          <button 
            @click="$emit('add-to-cart', item)"
            class="mt-3 bg-white text-teal-700 px-4 py-2 rounded-full text-sm font-semibold hover:bg-gray-100 transition opacity-0 group-hover:opacity-100"
          >
            Add to Cart
          </button>
        </div>
      </div>

      <div class="text-center mt-12">
        <Link href="/menu?filter=new" class="inline-block bg-white text-teal-700 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition">
          View More
        </Link>
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