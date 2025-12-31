<template>
  <MainLayout>
    <!-- Hero Section with Delivery/Pickup Options -->
    <section class="relative min-h-screen bg-gray-900 text-white overflow-hidden">
      <!-- Background Burger Image -->
      <div class="absolute inset-0">
        <img 
          src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=1920&h=1080&fit=crop" 
          alt="Burger" 
          class="w-full h-full object-cover opacity-40"
        >
        <div class="absolute inset-0 bg-gradient-to-b from-gray-900/80 via-gray-900/60 to-gray-900"></div>
      </div>

      <!-- Halal Badge -->
      <div class="absolute top-8 right-8 z-10">
        <div class="bg-white rounded-full p-3 w-20 h-20 flex items-center justify-center">
          <span class="text-2xl">🥩</span>
        </div>
        <p class="text-center text-xs mt-2 font-semibold">HALAL<br>MONITORING</p>
      </div>

      <!-- Content -->
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20">
        <div class="max-w-2xl">
          <!-- Offer Badge -->
          <div class="inline-flex items-center bg-black/80 backdrop-blur-sm px-6 py-3 rounded-full mb-8">
            <svg class="w-5 h-5 mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
              <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
              <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
            </svg>
            <span class="text-white font-semibold">20% off your 1st online order</span>
          </div>

          <!-- Hero Text -->
          <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
            Smashed Fresh<br>Every Time
          </h1>
          <p class="text-xl text-gray-300 mb-12">
            100% British beef, smashed to perfection with our secret sauce
          </p>

          <!-- Delivery/Pickup Toggle & Order Section -->
          <div class="bg-black/80 backdrop-blur-sm rounded-2xl p-6 max-w-3xl">
            <!-- Toggle Buttons -->
            <div class="flex gap-3 mb-6">
              <button 
                @click="orderType = 'delivery'"
                :class="[
                  'flex-1 flex items-center justify-center gap-2 px-6 py-3 rounded-full font-semibold transition-all',
                  orderType === 'delivery' 
                    ? 'bg-orange-500 text-white' 
                    : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
                ]"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Deliver
              </button>
              <button 
                @click="orderType = 'collect'"
                :class="[
                  'flex-1 flex items-center justify-center gap-2 px-6 py-3 rounded-full font-semibold transition-all',
                  orderType === 'collect' 
                    ? 'bg-orange-500 text-white' 
                    : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
                ]"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Collect
              </button>
            </div>

            <!-- Time Badge (for Collect) -->
            <div v-if="orderType === 'collect' && selectedStore" class="flex items-center gap-2 mb-4 text-sm text-gray-300">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span>{{ collectionTime }}</span>
            </div>

            <!-- Delivery Address Input -->
            <div v-if="orderType === 'delivery' && !showLocationSearch">
              <div class="relative mb-4">
                <input 
                  v-model="address"
                  @click="showLocationSearch = true"
                  type="text" 
                  placeholder="Enter your postcode or address"
                  class="w-full px-5 py-4 bg-gray-900 border-2 border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-orange-500 transition"
                  readonly
                >
                <button class="absolute right-4 top-1/2 transform -translate-y-1/2">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </button>
              </div>
              <button 
                @click="startOrder"
                class="w-full bg-orange-500 hover:bg-orange-600 text-white py-4 rounded-xl font-bold text-lg transition"
              >
                Order Now
              </button>
            </div>

            <!-- Collection Store Display -->
            <div v-if="orderType === 'collect' && !showStoreSelector">
              <div @click="showStoreSelector = true" class="bg-gray-900 border-2 border-gray-700 rounded-xl p-4 mb-4 cursor-pointer hover:border-orange-500 transition">
                <div class="flex items-start gap-3">
                  <svg class="w-5 h-5 text-orange-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                  </svg>
                  <div class="flex-1">
                    <p class="text-sm text-gray-400 mb-1">Collect</p>
                    <p class="text-white font-bold">{{ selectedStore.name }}</p>
                    <p class="text-sm text-gray-400">{{ selectedStore.address }}</p>
                  </div>
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </div>
              </div>
              
              <div class="bg-red-900/30 border border-red-500/50 rounded-xl p-3 mb-4 flex items-start gap-2">
                <svg class="w-5 h-5 text-red-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                  <p class="text-red-200 text-sm font-semibold">Preorder for collection or come back at {{ selectedStore.opensAt }}</p>
                </div>
              </div>

              <button 
                @click="preorderForCollection"
                class="w-full bg-orange-500 hover:bg-orange-600 text-white py-4 rounded-xl font-bold text-lg transition"
              >
                Preorder for collection
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Location Search Modal -->
    <Teleport to="body">
      <div v-if="showLocationSearch" class="fixed inset-0 bg-black/90 z-50 flex items-start justify-center pt-20 px-4">
        <div class="bg-gray-900 rounded-2xl w-full max-w-2xl max-h-[80vh] overflow-hidden">
          <!-- Header -->
          <div class="p-6 border-b border-gray-800">
            <div class="flex items-center gap-4">
              <button @click="showLocationSearch = false" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
              </button>
              <input 
                v-model="searchAddress"
                type="text" 
                placeholder="Enter your postcode or address"
                class="flex-1 bg-transparent border-b-2 border-orange-500 text-white text-lg py-2 focus:outline-none"
                autofocus
              >
            </div>
          </div>

          <!-- Current Location Option -->
          <div class="p-6">
            <button 
              @click="useCurrentLocation"
              class="flex items-center gap-3 text-white hover:text-orange-500 transition"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <span class="font-semibold">Use current location</span>
            </button>

            <!-- Search Results (mock) -->
            <div v-if="searchAddress" class="mt-6 space-y-2">
              <button 
                v-for="result in locationResults" 
                :key="result.id"
                @click="selectAddress(result)"
                class="w-full text-left p-4 bg-gray-800 hover:bg-gray-700 rounded-xl transition"
              >
                <p class="text-white font-semibold">{{ result.address }}</p>
                <p class="text-sm text-gray-400">{{ result.city }}, {{ result.postcode }}</p>
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Store Selector Modal -->
    <Teleport to="body">
      <div v-if="showStoreSelector" class="fixed inset-0 bg-black/90 z-50 flex items-start justify-center pt-20 px-4">
        <div class="bg-gray-900 rounded-2xl w-full max-w-2xl">
          <!-- Header -->
          <div class="p-6 border-b border-gray-800">
            <div class="flex items-center justify-between mb-4">
              <button @click="showStoreSelector = false" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
              <div class="flex gap-3">
                <button class="bg-gray-800 text-gray-300 px-4 py-2 rounded-full text-sm font-semibold">
                  <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                  </svg>
                  Deliver
                </button>
                <button class="bg-orange-500 text-white px-4 py-2 rounded-full text-sm font-semibold">
                  <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                  </svg>
                  Collect
                </button>
              </div>
            </div>

            <div class="flex items-center gap-2 text-gray-300">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
                <p class="font-bold text-white">Preorder for collection</p>
                <p class="text-sm">Collect from 16:00 - edit collection time at checkout</p>
              </div>
            </div>
          </div>

          <!-- Stores List -->
          <div class="p-6">
            <p class="text-gray-400 mb-4">Showing stores near you</p>
            
            <div 
              v-for="store in nearbyStores" 
              :key="store.id"
              @click="selectStore(store)"
              class="bg-white/5 hover:bg-white/10 rounded-xl p-4 mb-3 cursor-pointer transition"
            >
              <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-orange-500 mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                  <p class="text-white font-bold">{{ store.name }}</p>
                  <p class="text-sm text-gray-400">{{ store.address }}</p>
                  <p class="text-sm text-orange-500 mt-1">{{ store.status }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Rest of sections... -->
    <PopularItems :items="popularItems" @add-to-cart="handleAddToCart" />
    <!-- <OffersSection :offers="offers" />
    <NewItems :items="newItems" @add-to-cart="handleAddToCart" />
    <FeaturesSection />
    <TrendingMenu :menuItems="trendingMenu" />
    <TopTrending :items="topTrending" @add-to-cart="handleAddToCart" />
    <PopularFastFood :items="fastFood" @order-now="handleOrderNow" /> -->
    <AboutSection />

    <!-- Floating Cart Button -->
    <button @click="cartStore.toggleCart" class="fixed bottom-8 right-8 bg-orange-500 text-white w-16 h-16 rounded-full shadow-2xl hover:bg-orange-600 transition flex items-center justify-center z-40">
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
      </svg>
      <span v-if="cartStore.itemCount > 0" class="absolute -top-2 -right-2 bg-white text-orange-500 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">
        {{ cartStore.itemCount }}
      </span>
    </button>
  </MainLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import PopularItems from '@/Components/Home/PopularItems.vue';
import OffersSection from '@/Components/Home/OffersSection.vue';
import NewItems from '@/Components/Home/NewItems.vue';
import FeaturesSection from '@/Components/Home/FeaturesSection.vue';
import TrendingMenu from '@/Components/Home/TrendingMenu.vue';
import TopTrending from '@/Components/Home/TopTrending.vue';
import PopularFastFood from '@/Components/Home/PopularFastFood.vue';
import AboutSection from '@/Components/Home/AboutSection.vue';
import { useCartStore } from '@/Stores/cart';

const props = defineProps({
  popularItems: Array,
  offers: Array,
  newItems: Array,
  trendingMenu: Array,
  topTrending: Array,
  fastFood: Array,
});

const cartStore = useCartStore();

// Order Type
const orderType = ref('delivery'); // 'delivery' or 'collect'

// Location/Address
const address = ref('');
const searchAddress = ref('');
const showLocationSearch = ref(false);

// Store Selection
const showStoreSelector = ref(false);
const selectedStore = ref({
  id: 1,
  name: 'Smash N Grub',
  address: '204 Melbourne Road, Leicester LE2 0DT',
  opensAt: '16:00',
  status: 'Preorder now'
});

const collectionTime = ref('10-20 mins');

// Mock location results
const locationResults = ref([
  { id: 1, address: '123 High Street', city: 'Leicester', postcode: 'LE1 1AA' },
  { id: 2, address: '456 London Road', city: 'Leicester', postcode: 'LE2 2BB' },
  { id: 3, address: '789 King Street', city: 'Leicester', postcode: 'LE3 3CC' },
]);

// Mock nearby stores
const nearbyStores = ref([
  {
    id: 1,
    name: 'Smash N Grub',
    address: '204 Melbourne Road, Leicester LE2 0DT',
    status: 'Preorder now Opens at 16:00'
  },
  {
    id: 2,
    name: 'Smash N Grub - City Centre',
    address: '15 Market Street, Leicester LE1 5GF',
    status: 'Preorder now Opens at 16:00'
  },
]);

const useCurrentLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
      // Handle location
      address.value = 'Current Location';
      showLocationSearch.value = false;
    });
  }
};

const selectAddress = (result) => {
  address.value = `${result.address}, ${result.postcode}`;
  showLocationSearch.value = false;
};

const selectStore = (store) => {
  selectedStore.value = store;
  showStoreSelector.value = false;
};

const startOrder = () => {
  if (orderType.value === 'delivery' && address.value) {
    router.visit('/menu', { data: { address: address.value, type: 'delivery' } });
  } else if (orderType.value === 'collect' && selectedStore.value) {
    router.visit('/menu', { data: { storeId: selectedStore.value.id, type: 'collect' } });
  }
};

const preorderForCollection = () => {
  router.visit('/menu', { data: { storeId: selectedStore.value.id, type: 'collect', preorder: true } });
};

const handleAddToCart = (item) => {
  cartStore.addItem({ ...item, quantity: 1 });
  cartStore.openCart();
};

const handleOrderNow = (item) => handleAddToCart(item);
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>