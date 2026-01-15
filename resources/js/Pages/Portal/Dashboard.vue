<template>
  <CustomerLayout>
    <div class="max-w-6xl mx-auto">
      <header class="mb-10">
        <h1 class="text-4xl font-extrabold text-white tracking-tight">Welcome back, <span class="text-orange-500">{{ user.name }}</span>!</h1>
        <p class="text-gray-400 mt-2 text-lg">Manage your orders and account settings from your premium dashboard.</p>
      </header>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-xl hover:border-orange-500/50 transition-colors">
          <p class="text-gray-500 text-sm font-bold uppercase tracking-widest">Total Orders</p>
          <div class="flex items-baseline gap-2 mt-2">
            <span class="text-4xl font-black text-white">{{ orders_count }}</span>
          </div>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-xl hover:border-orange-500/50 transition-colors">
          <p class="text-gray-500 text-sm font-bold uppercase tracking-widest">Saved Addresses</p>
          <div class="flex items-baseline gap-2 mt-2">
            <span class="text-4xl font-black text-white">0</span>
          </div>
        </div>
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-xl hover:border-orange-500/50 transition-colors">
          <p class="text-gray-500 text-sm font-bold uppercase tracking-widest">Member Since</p>
          <div class="flex items-baseline gap-2 mt-2">
            <span class="text-xl font-black text-white">{{ formattedDate }}</span>
          </div>
        </div>
      </div>

      <!-- Recent Orders section -->
      <div class="bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden shadow-2xl">
        <div class="p-8 border-b border-gray-800 flex items-center justify-between">
          <h2 class="text-2xl font-bold">Recent Orders</h2>
          <Link href="/portal/orders" class="text-orange-500 font-bold hover:text-orange-400">View All</Link>
        </div>
        
        <div v-if="recent_orders.length > 0">
           <div v-for="order in recent_orders" :key="order.id" class="p-6 border-b border-gray-800 hover:bg-gray-800/30 transition-colors flex items-center justify-between">
              <div class="flex items-center gap-4">
                 <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-orange-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                 </div>
                 <div>
                    <p class="text-white font-bold">Order #{{ order.order_number }}</p>
                    <p class="text-xs text-gray-500">{{ formatDate(order.created_at) }}</p>
                 </div>
              </div>
              <div class="flex items-center gap-6">
                 <span class="text-sm font-bold text-gray-400">£{{ order.total }}</span>
                 <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-orange-500/10 text-orange-500 border border-orange-500/20">
                    {{ order.status }}
                 </span>
              </div>
           </div>
        </div>
        
        <div v-else class="p-12 text-center">
          <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
             <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
             </svg>
          </div>
          <p class="text-gray-500 text-lg">You haven't placed any orders yet.</p>
          <Link href="/menu" class="mt-6 inline-block bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-full transition-all shadow-lg shadow-orange-500/20">
            Order Now
          </Link>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
  user: Object,
  orders_count: Number,
  recent_orders: Array
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-GB', {
    day: 'numeric',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const formattedDate = computed(() => {
  if (!user.value?.created_at) return 'N/A';
  return new Date(user.value.created_at).toLocaleDateString('en-GB', {
    month: 'long',
    year: 'numeric'
  });
});
</script>
