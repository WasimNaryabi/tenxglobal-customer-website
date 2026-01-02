<template>
  <MainLayout>
    <div class="min-h-screen pt-32 pb-20 bg-black text-white selection:bg-orange-500 selection:text-white">
      <div class="max-w-4xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-16">
          <h1 class="text-5xl font-black mb-4 bg-gradient-to-r from-white to-gray-500 bg-clip-text text-transparent italic">
            TRACK YOUR FEAST
          </h1>
          <p class="text-gray-400 text-lg font-medium">Real-time updates from our kitchen to your doorstep.</p>
        </div>

        <!-- Search Box -->
        <div v-if="!currentOrder || (!currentOrder.cancelled_at && currentPosStatus?.status !== 'Cancelled' && currentPosStatus?.pos_status !== 'cancelled')" class="glass-card p-8 rounded-3xl border border-white/10 mb-12 relative overflow-hidden group">
          <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          
          <div class="relative z-10">
            <label class="block text-sm font-bold uppercase tracking-widest text-gray-500 mb-4 ml-1">Order ID</label>
            <div class="flex flex-col md:flex-row gap-4">
              <div class="relative flex-1">
                <input 
                  v-model="orderNumber" 
                  type="text" 
                  placeholder="e.g. ORD-12345678"
                  class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-xl font-bold focus:outline-none focus:border-orange-500/50 focus:ring-4 focus:ring-orange-500/10 transition-all placeholder:text-gray-700"
                  @keyup.enter="handleTrack"
                />
                <div v-if="loading" class="absolute right-4 top-1/2 -translate-y-1/2">
                  <div class="w-6 h-6 border-2 border-orange-500/30 border-t-orange-500 rounded-full animate-spin"></div>
                </div>
              </div>
              <button 
                @click="handleTrack"
                :disabled="loading || !orderNumber"
                class="bg-orange-500 hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed text-black font-black px-10 py-4 rounded-2xl transition-all active:scale-95 shadow-lg shadow-orange-500/20"
              >
                TRACK NOW
              </button>
            </div>
          </div>
        </div>

        <!-- Cancelled State -->
        <div v-if="currentOrder && (currentOrder.cancelled_at || currentPosStatus?.status === 'Cancelled' || currentPosStatus?.pos_status === 'cancelled')" class="glass-card p-10 rounded-3xl border border-red-500/20 bg-red-500/5 mb-12 text-center">
          <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-500/20 border border-red-500/30 mb-6">
            <Icon name="x-circle" :size="40" class="text-red-500" />
          </div>
          <h2 class="text-4xl font-black italic text-red-500 mb-4 uppercase">Order Cancelled</h2>
          <p class="text-gray-300 text-lg mb-6">We're sorry, but your order has been cancelled.</p>
          
          <div class="bg-white/5 p-6 rounded-2xl border border-white/5 max-w-lg mx-auto">
            <p class="text-[0.6rem] text-red-500/70 font-black uppercase tracking-[0.2em] mb-2">Reason provided</p>
            <p class="text-lg italic text-white">"{{ currentPosStatus?.cancellation_reason || 'Rejected by restaurant' }}"</p>
          </div>

          <button @click="currentOrder = null; currentPosStatus = null; orderNumber = ''" class="mt-10 text-gray-500 hover:text-white font-bold transition-colors underline underline-offset-8">
            Track another order
          </button>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="mb-8 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-500 text-center font-bold">
          {{ error }}
        </div>

        <!-- Tracking Results -->
        <transition 
          enter-active-class="transition duration-500 ease-out"
          enter-from-class="transform translate-y-8 opacity-0"
          enter-to-class="transform translate-y-0 opacity-100"
        >
          <div v-if="currentOrder && !currentOrder.cancelled_at && currentPosStatus?.status !== 'Cancelled' && currentPosStatus?.pos_status !== 'cancelled'" class="space-y-8">
            <!-- Status Overview -->
            <div class="glass-card p-10 rounded-3xl border border-white/10 overflow-hidden relative">
              <div class="text-center mb-12">
                <p class="text-orange-500 font-bold uppercase tracking-widest text-xs mb-2">Live Status</p>
                <h2 class="text-4xl font-black italic mb-6">{{ statusText }}</h2>
                <div class="inline-block px-6 py-2 bg-white/5 rounded-2xl border border-white/5">
                  <p class="text-gray-500 font-bold uppercase tracking-widest text-[0.6rem] mb-1">Estimated Arrival</p>
                  <p class="text-xl font-bold">{{ estimatedTime }}</p>
                </div>
              </div>

              <!-- Progress Tracker -->
              <div class="relative py-8">
                <!-- Progress Line -->
                <div class="absolute top-[3.25rem] left-5 right-5 h-1 bg-white/5 rounded-full overflow-hidden">
                  <div 
                    class="h-full bg-orange-500 shadow-[0_0_20px_rgba(249,115,22,0.5)] transition-all duration-1000 ease-out"
                    :style="{ width: progressPercentage + '%' }"
                  ></div>
                </div>

                <!-- Steps -->
                <div class="relative flex justify-between">
                  <div v-for="(step, index) in trackingSteps" :key="index" class="flex flex-col items-center">
                    <div 
                      class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 z-10"
                      :class="[
                        index <= currentStepIndex 
                          ? 'bg-orange-500 text-black scale-110 shadow-lg shadow-orange-500/30' 
                          : 'bg-zinc-900 text-gray-600 border border-white/10'
                      ]"
                    >
                      <Icon :name="step.icon" :size="20" />
                    </div>
                    <span 
                      class="mt-4 text-xs font-black uppercase tracking-widest transition-colors duration-500"
                      :class="index <= currentStepIndex ? 'text-white' : 'text-gray-600'"
                    >
                      {{ step.label }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Order Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div class="glass-card p-8 rounded-3xl border border-white/10">
                <h3 class="text-xl font-bold mb-6 italic border-b border-white/5 pb-4">ORDER DETAILS</h3>
                <div class="space-y-4">
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-500 uppercase tracking-widest font-bold">Number</span>
                    <span class="font-bold">#{{ currentOrder.order_number }}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-500 uppercase tracking-widest font-bold">Type</span>
                    <span class="font-bold uppercase">{{ currentOrder.type }}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-500 uppercase tracking-widest font-bold">Placed At</span>
                    <span class="font-bold">{{ formatDate(currentOrder.created_at) }}</span>
                  </div>
                  <div class="flex justify-between text-sm border-t border-white/5 pt-4 mt-2">
                    <span class="text-gray-500 uppercase tracking-widest font-bold">Total Paid</span>
                    <span class="text-orange-500 font-black text-lg">£{{ currentOrder.total }}</span>
                  </div>
                </div>
              </div>

              <div class="glass-card p-8 rounded-3xl border border-white/10 overflow-hidden relative">
                <div v-if="currentOrder.type === 'delivery'" class="relative z-10">
                  <h3 class="text-xl font-bold mb-6 italic border-b border-white/5 pb-4">DELIVERY INFO</h3>
                  <p class="text-white font-medium mb-2">{{ currentOrder.customer_name }}</p>
                  <p class="text-gray-400 text-sm leading-relaxed mb-4">{{ currentOrder.delivery_address }}</p>
                  <div class="bg-white/5 p-4 rounded-2xl border border-white/5">
                    <p class="text-[0.6rem] text-orange-500/50 font-black uppercase tracking-[0.2em] mb-1">Note from you</p>
                    <p class="text-sm italic text-gray-300">"{{ currentOrder.special_instructions || 'No special instructions' }}"</p>
                  </div>
                </div>
                <div v-else class="relative z-10">
                  <h3 class="text-xl font-bold mb-6 italic border-b border-white/5 pb-4">COLLECTION INFO</h3>
                  <p class="text-white font-medium mb-2">Pick-up Location</p>
                  <p class="text-gray-400 text-sm leading-relaxed">Our main branch at Central Square.</p>
                  <p class="text-orange-500 font-bold mt-4">Order will be ready for you at the counter.</p>
                </div>
              </div>
            </div>
          </div>
        </transition>

        <!-- Empty State -->
        <div v-if="!currentOrder && !loading && !error" class="text-center py-20">
          <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-white/5 border border-white/10 mb-8">
            <Icon name="search" :size="40" class="text-gray-700" />
          </div>
          <h3 class="text-2xl font-bold text-gray-700">Waiting for your Order ID...</h3>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import Icon from '@/Components/Shared/Icon.vue';
import axios from 'axios';

const props = defineProps({
  initialOrderNumber: String,
  order: Object,
  posStatus: Object
});

const orderNumber = ref(props.initialOrderNumber || '');
const currentOrder = ref(props.order);
const currentPosStatus = ref(props.posStatus);
const loading = ref(false);
const error = ref(null);

const trackingSteps = [
  { label: 'Received', icon: 'receipt' },
  { label: 'Accepted', icon: 'check' },
  { label: 'Preparing', icon: 'settings' },
  { label: 'Ready', icon: 'package' },
];

const currentStepIndex = computed(() => {
  if (!currentPosStatus.value) return 0;
  
  const kitchenStatus = currentPosStatus.value.status;
  const posStatus = currentPosStatus.value.pos_status;
  
  // 0: Received (sitting in inbox)
  if (posStatus === 'unpaid') return 0;
  
  // 1: Accepted (processed from inbox)
  if (posStatus === 'paid' && kitchenStatus === 'Waiting') return 1;
  
  // 2: Preparing
  if (kitchenStatus === 'In Progress') return 2;
  
  // 3: Ready
  if (kitchenStatus === 'Done' || kitchenStatus === 'Completed') return 3;
  
  return 0;
});

const progressPercentage = computed(() => {
  return (currentStepIndex.value / (trackingSteps.length - 1)) * 100;
});

const statusText = computed(() => {
  if (currentStepIndex.value === 0) return "We've got your order!";
  if (currentStepIndex.value === 1) return "Order accepted, prepping soon...";
  if (currentStepIndex.value === 2) return "Chef is working their magic...";
  if (currentStepIndex.value === 3) return "Your order is ready!";
  return "Processing...";
});

const estimatedTime = computed(() => {
  const step = currentStepIndex.value;
  if (step === 3) return currentOrder.value?.type === 'delivery' ? "Arriving soon" : "Ready";
  if (step === 2) return "10-15 mins";
  if (step === 1) return "20-25 mins";
  return "25-35 mins";
});

const handleTrack = async () => {
  if (!orderNumber.value) return;
  
  loading.value = true;
  error.value = null;
  
  try {
    const response = await axios.get(`/api/track-order/${orderNumber.value}`);
    currentOrder.value = response.data.order;
    currentPosStatus.value = response.data.posStatus;
    
    if (!currentOrder.value) {
      error.value = "We couldn't find an order with that number. Please check and try again.";
    }
  } catch (err) {
    error.value = "An error occurred while tracking. Please try again later.";
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleString('en-GB', { 
    day: 'numeric', 
    month: 'short', 
    year: 'numeric', 
    hour: '2-digit', 
    minute: '2-digit' 
  });
};

onMounted(() => {
  if (props.initialOrderNumber && !props.order) {
    handleTrack();
  }
  
  // Auto-refresh every 30 seconds if order found and not completed
  setInterval(() => {
    if (currentOrder.value && currentStepIndex.value < 4) {
      handleTrack();
    }
  }, 30000);
});
</script>

<style scoped>
.glass-card {
  background: rgba(255, 255, 255, 0.02);
  backdrop-filter: blur(20px);
}

@keyframes pulse-orange {
  0% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4); }
  70% { box-shadow: 0 0 0 15px rgba(249, 115, 22, 0); }
  100% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0); }
}

.active-pulse {
  animation: pulse-orange 2s infinite;
}
</style>
