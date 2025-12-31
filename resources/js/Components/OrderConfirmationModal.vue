<template>
  <Transition name="modal">
    <div v-if="isOpen" class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center p-4">
      <!-- Overlay -->
      <div 
        class="absolute inset-0 bg-black bg-opacity-75 transition-opacity"
        @click="$emit('close')"
      ></div>

      <!-- Modal -->
      <div class="relative bg-gray-900 border-2 border-orange-500 rounded-2xl max-w-md w-full p-6 shadow-2xl">
        
        <!-- Icon -->
        <div class="flex justify-center mb-4">
          <div class="w-16 h-16 rounded-full bg-orange-500 flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
        </div>

        <!-- Title -->
        <h3 class="text-2xl font-bold text-white text-center mb-4">
          Confirm Your Order
        </h3>

        <!-- Order Details -->
        <div class="space-y-3 mb-6">
          <div class="bg-black border border-gray-800 rounded-lg p-4">
            <div class="flex justify-between mb-2">
              <span class="text-gray-400">Order Type:</span>
              <span class="text-white font-semibold">{{ orderDetails.type }}</span>
            </div>
            <div class="flex justify-between mb-2">
              <span class="text-gray-400">Items:</span>
              <span class="text-white font-semibold">{{ orderDetails.itemCount }} items</span>
            </div>
            <div class="flex justify-between mb-2">
              <span class="text-gray-400">Payment:</span>
              <span class="text-white font-semibold">{{ orderDetails.payment }}</span>
            </div>
            <div class="flex justify-between pt-3 mt-3 border-t border-gray-800">
              <span class="text-gray-400 text-lg">Total:</span>
              <span class="text-orange-500 font-bold text-xl">£{{ orderDetails.total }}</span>
            </div>
          </div>

          <p class="text-center text-gray-400 text-sm">
            Your order will be {{ orderDetails.type === 'Delivery' ? 'delivered in 30-45 mins' : 'ready for pickup in 15-20 mins' }}
          </p>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3">
          <button
            @click="$emit('close')"
            class="flex-1 border-2 border-gray-800 text-white py-3 rounded-full font-bold hover:bg-gray-800 transition"
          >
            Cancel
          </button>
          <button
            @click="$emit('confirm')"
            class="flex-1 bg-orange-500 text-white py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-lg"
          >
            Place Order
          </button>
        </div>

      </div>
    </div>
  </Transition>
</template>

<script setup>
defineProps({
  isOpen: Boolean,
  orderDetails: {
    type: Object,
    required: true,
  },
});

defineEmits(['close', 'confirm']);
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .relative,
.modal-leave-to .relative {
  transform: scale(0.9);
}
</style>