<template>
  <div @click="$emit('open-customization', item)"
    class="bg-gray-900 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-orange-500/20 transition-all duration-300 group cursor-pointer border border-gray-800 hover:border-orange-500/50">
    <!-- Image - Reduced height -->
    <div class="relative overflow-hidden h-36">
      <img :src="item.image" :alt="item.name"
        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

      <!-- Badges - Smaller -->
      <div class="absolute top-2 left-2 flex flex-col gap-1.5">
        <span v-if="item.isNew"
          class="bg-green-500 text-white px-2 py-0.5 rounded-full text-[10px] font-bold shadow-lg">
          NEW
        </span>
        <span v-if="item.discount"
          class="bg-orange-500 text-white px-2 py-0.5 rounded-full text-[10px] font-bold shadow-lg">
          {{ item.discount }}% OFF
        </span>
      </div>

      <!-- Quick Add Button - Smaller, Always visible on mobile -->
      <button @click.stop="$emit('open-customization', item)"
        class="absolute bottom-2 right-2 bg-orange-500 text-white w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center md:opacity-0 md:group-hover:opacity-100 transition-all shadow-lg hover:scale-110">
        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
      </button>
    </div>

    <!-- Content - Compact padding -->
    <div class="p-3">
      <!-- Category Badge - Smaller -->
      <span class="text-[10px] font-semibold text-orange-500 mb-1.5 inline-block uppercase tracking-wider">
        {{ item.category }}
      </span>

      <!-- Name - Smaller, show more -->
      <h3 class="text-sm font-bold text-white mb-1.5 group-hover:text-orange-500 transition line-clamp-2">
        {{ item.name }}
      </h3>

      <!-- Description - Smaller, show more lines -->
      <p class="text-xs text-gray-400 mb-3 line-clamp-3">{{ item.description }}</p>

      <!-- Price & Add to Cart - Compact -->
      <div class="flex items-center justify-between gap-2">
        <div>
          <div v-if="item.discount" class="flex items-center gap-1.5">
            <span class="text-xs text-gray-500 line-through">
              £{{ item.originalPrice.toFixed(2) }}
            </span>
          </div>
          <span class="text-lg font-bold text-white">
            £{{ item.price.toFixed(2) }}
          </span>
        </div>

        <button @click.stop="$emit('open-customization', item)"
          class="bg-orange-500 text-white px-4 py-2 rounded-full text-xs font-bold hover:bg-orange-600 transition-all hover:scale-105 shadow-lg">
          Add
        </button>
      </div>

      <!-- Rating - Smaller -->
      <!-- <div class="flex items-center mt-3 pt-3 border-t border-gray-800">
        <div class="flex text-yellow-400">
          <svg v-for="i in 5" :key="i" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
        </div>
        <span class="ml-1.5 text-xs text-gray-400">({{ item.reviews || 0 }})</span>
      </div> -->
    </div>
  </div>
</template>

<script setup>
defineProps({
  item: {
    type: Object,
    required: true
  }
});

defineEmits(['open-customization']);
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>