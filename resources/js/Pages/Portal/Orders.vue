<template>
  <CustomerLayout>
    <div class="max-w-6xl mx-auto">
      <header class="mb-10">
        <h1 class="text-4xl font-extrabold text-white tracking-tight">My Orders</h1>
        <p class="text-gray-400 mt-2">Track your recent orders and view your order history.</p>
      </header>

      <!-- Orders List -->
      <div v-if="orders.length > 0" class="space-y-6">
        <div v-for="order in orders" :key="order.id" class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-xl hover:border-gray-700 transition-all group">
          <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div class="flex items-center gap-4">
               <div class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center text-orange-500">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                  </svg>
               </div>
               <div>
                 <p class="text-sm text-gray-500 font-bold uppercase tracking-widest">Order #{{ order.order_number }}</p>
                 <p class="text-white font-bold">{{ formatDate(order.created_at) }}</p>
               </div>
            </div>
            <div class="flex items-center gap-4">
               <span :class="statusClass(order.status)" class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-tighter shadow-lg">
                 {{ order.status }}
               </span>
               <span class="text-2xl font-black text-white">£{{ order.total }}</span>
            </div>
          </div>
          <div class="pt-4 border-t border-gray-800 flex justify-end">
             <button class="text-orange-500 font-bold hover:text-orange-400 flex items-center gap-2 group-hover:translate-x-1 transition-transform">
               View Details
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
               </svg>
             </button>
          </div>
        </div>
      </div>

      <div v-else class="bg-gray-900 border border-gray-800 rounded-3xl p-20 text-center shadow-2xl">
         <div class="w-24 h-24 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
         </div>
         <h3 class="text-2xl font-bold text-white mb-2">No orders found</h3>
         <p class="text-gray-500 mb-8 max-w-sm mx-auto">Hungry? Our premium ingredients are waiting for you. Treat yourself to something delicious today!</p>
         <Link href="/menu" class="bg-gradient-to-r from-orange-500 to-red-600 text-white font-black py-4 px-10 rounded-full shadow-xl shadow-orange-500/20 hover:scale-105 transition-all">
           Explore Menu
         </Link>
      </div>
    </div>
  </CustomerLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

defineProps({
  orders: Array
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-GB', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const statusClass = (status) => {
  const base = 'px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-tighter shadow-lg ';
  switch (status.toLowerCase()) {
    case 'completed':
    case 'delivered':
      return base + 'bg-green-500/10 text-green-500 border border-green-500/20';
    case 'pending':
    case 'processing':
      return base + 'bg-orange-500/10 text-orange-500 border border-orange-500/20';
    case 'cancelled':
      return base + 'bg-red-500/10 text-red-500 border border-red-500/20';
    default:
      return base + 'bg-gray-800 text-gray-400';
  }
};
</script>
