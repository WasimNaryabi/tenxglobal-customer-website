<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  items: { type: Array, required: true }
})
defineEmits(['add-to-cart'])
</script>

<template>
  <section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <div class="flex items-center justify-between mb-10">
        <div>
          <h2 class="text-4xl font-bold text-white">Popular Items</h2>
          <p class="text-gray-200">Top selling items</p>
        </div>

        <!-- ✅ Go to menu page -->
        <Link
          href="/menu"
          class="px-5 py-2 rounded-full text-sm font-semibold bg-orange-500 text-white hover:bg-orange-600 transition"
        >
          View All Menu
        </Link>
      </div>

      <!-- ✅ Show only 5 items -->
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
        <div
          v-for="item in items.slice(0, 5)"
          :key="item.id"
          class="flex flex-col items-center group cursor-pointer"
        >
          <div class="w-40 h-40 mb-4 overflow-hidden rounded-full transition-transform duration-300 group-hover:scale-110">
            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
          </div>

          <h3 class="font-bold text-gray-100 text-center mb-1">{{ item.name }}</h3>
          <p class="text-sm text-gray-200 mb-2">{{ item.description }}</p>

          <button
            @click="$emit('add-to-cart', item)"
            class="mt-3 text-white px-4 py-2 rounded-full text-sm font-semibold bg-orange-500 hover:bg-orange-600 transition opacity-0 group-hover:opacity-100"
          >
            Add to cart
          </button>
        </div>
      </div>

    </div>
  </section>
</template>
