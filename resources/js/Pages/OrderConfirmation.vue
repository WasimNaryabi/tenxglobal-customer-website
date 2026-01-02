<template>
  <div class="min-h-screen bg-black flex items-center justify-center p-4">
    <!-- Main Content Container -->
    <div class="bg-gray-900 border-2 border-orange-500 rounded-2xl max-w-lg w-full p-8 shadow-2xl">
      
      <!-- Success Icon -->
      <div class="flex justify-center mb-6">
        <div class="w-20 h-20 rounded-full bg-green-500 flex items-center justify-center shadow-lg shadow-green-900/20">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
      </div>

      <!-- Title & Order ID -->
      <div class="text-center mb-8">
        <h3 class="text-3xl font-black text-white mb-2">Order Confirmed!</h3>
        <p class="text-gray-400">Order #{{ order.order_number }}</p>
      </div>

      <!-- Order Summary Card -->
      <div class="bg-black border border-gray-800 rounded-xl p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
          <span class="text-gray-400">Order Type</span>
          <span class="px-3 py-1 bg-orange-500/10 text-orange-500 rounded-full text-sm font-bold uppercase tracking-wider">
            {{ order.type }}
          </span>
        </div>
        
        <div class="flex justify-between items-center mb-4">
          <span class="text-gray-400">Status</span>
          <span class="text-white font-semibold flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
            {{ order.status }}
          </span>
        </div>

        <div class="flex justify-between items-center mb-4 pt-4 border-t border-gray-800">
          <span class="text-gray-400">Customer</span>
          <span class="text-white font-semibold">{{ order.customer_name }}</span>
        </div>

        <div class="flex justify-between items-center pt-4 mt-4 border-t border-gray-800">
          <span class="text-gray-400 text-lg">Total Amount</span>
          <span class="text-orange-500 font-bold text-2xl">£{{ order.total }}</span>
        </div>
      </div>

      <!-- Messaging -->
      <div class="text-center mb-8 px-4">
        <p class="text-gray-400 text-sm leading-relaxed">
          Thank you for your order! We'll start preparing it right away. 
          {{ order.type === 'delivery' ? 'Your delivery will arrive in approximately 30-45 minutes.' : 'Your order will be ready for collection in approximately 15-20 minutes.' }}
        </p>
      </div>

      <!-- Actions -->
      <div class="flex flex-col gap-3">
        <Link
          :href="`/track-order?orderNumber=${order.order_number}`"
          class="w-full bg-orange-500 text-white py-4 rounded-xl font-bold hover:bg-orange-600 transition shadow-lg shadow-orange-900/20 text-center uppercase tracking-wider"
        >
          Track Your Order
        </Link>
        <Link
          href="/menu"
          class="w-full bg-white/5 border border-white/10 text-white py-4 rounded-xl font-bold hover:bg-white/10 transition text-center"
        >
          Back to Menu
        </Link>
        <Link
          href="/"
          class="w-full text-gray-500 py-3 rounded-xl font-medium hover:text-white transition text-center"
        >
          Go to Homepage
        </Link>
      </div>

    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  order: {
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