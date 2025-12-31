<template>
  <Transition name="slide">
    <div 
      v-if="cartStore.isOpen" 
      class="fixed inset-0 z-50 overflow-hidden"
    >
      <!-- Overlay -->
      <div 
        class="absolute inset-0 bg-black bg-opacity-75 transition-opacity backdrop-blur-sm"
        @click="cartStore.closeCart"
      ></div>

      <!-- Sidebar -->
      <div class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-gray-900 shadow-2xl flex flex-col border-l border-gray-800">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-800 flex justify-between items-center bg-black">
          <div>
            <h2 class="text-m font-bold text-white">Shopping Cart</h2>
            <p class="text-sm text-gray-400 mt-1">{{ cartStore.items.length }} {{ cartStore.items.length === 1 ? 'item' : 'items' }}</p>
          </div>
          <button 
            @click="cartStore.closeCart"
            class="text-gray-400 hover:text-white transition w-10 h-10 rounded-full bg-gray-800 hover:bg-gray-700 flex items-center justify-center"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto px-6 py-4 custom-scrollbar">
          <!-- Empty State -->
          <div v-if="cartStore.items.length === 0" class="flex flex-col items-center justify-center h-full py-12">
            <div class="w-24 h-24 rounded-full bg-gray-800 flex items-center justify-center mb-4">
              <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </div>
            <p class="text-gray-400 text-lg font-semibold mb-2">Your cart is empty</p>
            <p class="text-gray-500 text-sm">Add some delicious items to get started!</p>
          </div>

          <!-- Cart Items List -->
          <div v-else class="space-y-3">
            <div 
              v-for="item in cartStore.items" 
              :key="item.id"
              class="flex gap-4 bg-black border border-gray-800 p-4 rounded-xl hover:border-orange-500 transition group"
            >
              <!-- Item Image -->
              <div class="relative">
                <img 
                  :src="item.image" 
                  :alt="item.name" 
                  class="w-20 h-20 object-cover rounded-lg"
                >
                <div v-if="item.quantity > 1" class="absolute -top-2 -right-2 bg-orange-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">
                  {{ item.quantity }}
                </div>
              </div>
              
              <!-- Item Details -->
              <div class="flex-1 min-w-0">
                <h3 class="font-bold text-white mb-1 line-clamp-1 group-hover:text-orange-500 transition">{{ item.name }}</h3>
                <p class="text-xs text-gray-400 mb-2 line-clamp-1">{{ item.description }}</p>
                
                <!-- Customizations -->
                <div v-if="item.customizations" class="mb-2">
                  <p v-if="item.customizations.addOns?.length" class="text-xs text-gray-500">
                    + {{ item.customizations.addOns.join(', ') }}
                  </p>
                  <p v-if="item.customizations.removed?.length" class="text-xs text-gray-500">
                    - {{ item.customizations.removed.join(', ') }}
                  </p>
                </div>

                <!-- Price and Quantity -->
                <div class="flex items-center justify-between">
                  <p class="text-orange-500 font-bold">£{{ (item.price * item.quantity).toFixed(2) }}</p>
                  
                  <!-- Quantity Controls -->
                  <div class="flex items-center gap-2 bg-gray-800 rounded-full px-2 py-1 border border-gray-700">
                    <button 
                      @click="cartStore.decreaseQuantity(item.id)"
                      class="text-gray-400 hover:text-white transition w-6 h-6 flex items-center justify-center"
                    >
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="font-semibold text-white min-w-[20px] text-center text-sm">{{ item.quantity }}</span>
                    <button 
                      @click="cartStore.increaseQuantity(item.id)"
                      class="text-gray-400 hover:text-orange-500 transition w-6 h-6 flex items-center justify-center"
                    >
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Remove Button -->
              <button 
                @click="cartStore.removeItem(item.id)"
                class="text-gray-500 hover:text-red-500 transition w-8 h-8 rounded-lg hover:bg-gray-800 flex items-center justify-center flex-shrink-0"
                title="Remove item"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div v-if="cartStore.items.length > 0" class="border-t border-gray-800 px-6 py-5 bg-black space-y-4">
          <!-- Subtotal -->
          <div class="flex justify-between items-center text-gray-400 text-sm">
            <span>Subtotal</span>
            <span>£{{ cartStore.total.toFixed(2) }}</span>
          </div>

          <!-- Delivery Fee -->
          <div class="flex justify-between items-center text-gray-400 text-sm">
            <span>Delivery Fee</span>
            <span>£2.50</span>
          </div>

          <!-- Total -->
          <div class="flex justify-between items-center pt-3 border-t border-gray-800">
            <span class="text-l font-semibold text-white">Total:</span>
            <span class="text-l font-bold text-orange-500">£{{ (cartStore.total + 2.50).toFixed(2) }}</span>
          </div>

          <!-- Checkout Button -->
          <div class="flex space-x-4"> 
    <Link 
      href="/checkout" 
      @click="cartStore.closeCart()"
      class="flex-1 block bg-orange-500 text-white text-center py-2 rounded-full font-bold hover:bg-orange-600 transition shadow-lg hover:scale-105"
    >
      Proceed to Checkout
    </Link>

    <button 
      @click="cartStore.clearCart"
      class="flex-1 block border-2 border-gray-800 text-gray-400 text-center py-2 rounded-full font-semibold hover:bg-gray-800 hover:text-white transition"
    >
      Clear Cart
    </button>
</div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { useCartStore } from '@/Stores/cart';

const cartStore = useCartStore();
</script>

<style scoped>
/* Slide animation */
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.slide-enter-from,
.slide-leave-to {
  transform: translateX(100%);
  opacity: 0;
}

.slide-enter-to,
.slide-leave-from {
  transform: translateX(0);
  opacity: 1;
}

/* Overlay animation */
.slide-enter-active .absolute.inset-0,
.slide-leave-active .absolute.inset-0 {
  transition: opacity 0.3s ease;
}

/* Custom scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #1f2937;
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #374151;
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #4b5563;
}

/* Line clamp */
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>