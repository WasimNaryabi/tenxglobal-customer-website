<template>
  <Transition name="modal">
    <div v-if="isOpen" class="fixed inset-0 z-[100] overflow-hidden flex items-center justify-center p-4">
      <!-- Overlay -->
      <div 
        class="absolute inset-0 bg-black bg-opacity-80 backdrop-blur-sm transition-opacity"
      ></div>

      <!-- Modal -->
      <div class="relative bg-gray-900 border-2 border-orange-500 rounded-2xl max-w-sm w-full p-8 shadow-2xl text-center">
        
        <!-- Animated Icon -->
        <div class="flex justify-center mb-6">
          <div class="w-20 h-20 rounded-full bg-orange-500 bg-opacity-10 flex items-center justify-center animate-pulse">
            <svg class="w-12 h-12 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
        </div>

        <!-- Title -->
        <h3 class="text-2xl font-bold text-white mb-2">
          Ordering Unavailable
        </h3>

        <!-- Message -->
        <p class="text-gray-400 mb-8 leading-relaxed">
          {{ message || 'We are currently not accepting online orders. Please check back later during our business hours.' }}
        </p>

        <!-- Button -->
        <button
          @click="$emit('confirm')"
          class="w-full bg-orange-500 text-white py-4 rounded-full font-bold hover:bg-orange-600 transition shadow-lg flex items-center justify-center gap-2 group"
        >
          <span>Back to Menu</span>
          <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
          </svg>
        </button>

      </div>
    </div>
  </Transition>
</template>

<script setup>
defineProps({
  isOpen: Boolean,
  message: String,
});

defineEmits(['confirm']);
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .relative,
.modal-leave-to .relative {
  transform: scale(0.9) translateY(20px);
}
</style>
