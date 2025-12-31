<template>
  <section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Popular Fast Food</h2>
        <p class="text-gray-600">Quick bites, big flavors</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="item in items" 
          :key="item.id"
          class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group"
        >
          <div class="relative">
            <img 
              :src="item.image" 
              :alt="item.name" 
              class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500"
            >
            <span 
              v-if="item.discount" 
              class="absolute top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-full font-bold text-sm shadow-lg"
            >
              {{ item.discount }}% OFF
            </span>
          </div>

          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ item.name }}</h3>
            <p class="text-gray-600 mb-4">{{ item.description }}</p>
            
            <div class="flex justify-between items-center">
              <div>
                <span class="text-2xl font-bold text-gray-900">${{ calculateDiscountedPrice(item.price, item.discount) }}</span>
                <span v-if="item.discount" class="text-sm text-gray-500 line-through ml-2">${{ item.price.toFixed(2) }}</span>
              </div>
              
              <button 
                @click="$emit('order-now', item)"
                class="bg-red-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-red-700 transition"
              >
                Order Now
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-12">
        <Link href="/menu?category=fast-food" class="inline-block bg-red-600 text-white px-8 py-3 rounded-full font-bold hover:bg-red-700 transition">
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

defineEmits(['order-now']);

const calculateDiscountedPrice = (price, discount) => {
  if (discount) {
    return (price * (1 - discount / 100)).toFixed(2);
  }
  return price.toFixed(2);
};
</script>