<template>
  <MainLayout>
    <div class="min-h-screen bg-black py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
          <Link href="/menu" class="text-orange-500 hover:text-orange-600 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Menu
          </Link>
          <h1 class="text-4xl font-bold text-white">Checkout</h1>
          <p class="text-gray-400 mt-2">Complete your order</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          
          <!-- Left Side - Checkout Form -->
          <div class="lg:col-span-2 space-y-6">
            
            <!-- Order Type Selection -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
              <h2 class="text-xl font-bold text-white mb-4">Order Type</h2>
              <div class="grid grid-cols-2 gap-4">
                <button
                  @click="orderType = 'delivery'"
                  :class="[
                    'p-4 rounded-lg border-2 transition',
                    orderType === 'delivery' 
                      ? 'border-orange-500 bg-orange-500 bg-opacity-10' 
                      : 'border-gray-800 hover:border-gray-700'
                  ]"
                >
                  <svg class="w-8 h-8 mx-auto mb-2" :class="orderType === 'delivery' ? 'text-orange-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                  </svg>
                  <p :class="orderType === 'delivery' ? 'text-orange-500 font-bold' : 'text-gray-300'">Delivery</p>
                </button>

                <button
                  @click="orderType = 'pickup'"
                  :class="[
                    'p-4 rounded-lg border-2 transition',
                    orderType === 'pickup' 
                      ? 'border-orange-500 bg-orange-500 bg-opacity-10' 
                      : 'border-gray-800 hover:border-gray-700'
                  ]"
                >
                  <svg class="w-8 h-8 mx-auto mb-2" :class="orderType === 'pickup' ? 'text-orange-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                  <p :class="orderType === 'pickup' ? 'text-orange-500 font-bold' : 'text-gray-300'">Pickup</p>
                </button>
              </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
              <h2 class="text-xl font-bold text-white mb-4">Contact Information</h2>
              
              <div v-if="!auth.user" class="space-y-4">
                <!-- Guest Form -->
                <div>
                  <label class="block text-sm font-semibold text-gray-300 mb-2">Full Name *</label>
                  <input 
                    v-model="form.name"
                    type="text" 
                    class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                    placeholder="John Doe"
                  >
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-300 mb-2">UK Phone Number *</label>
                  <input 
                    v-model="form.phone"
                    type="tel" 
                    class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                    placeholder="07123456789"
                  >
                  <p class="text-xs text-gray-500 mt-1">We'll send you order updates via SMS</p>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-300 mb-2">Email (Optional)</label>
                  <input 
                    v-model="form.email"
                    type="email" 
                    class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                    placeholder="john@example.com"
                  >
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
                  <p class="text-sm text-gray-300">
                    <Link href="/login" class="text-orange-500 hover:text-orange-600 font-semibold">Sign in</Link> 
                    to save your details for faster checkout next time
                  </p>
                </div>
              </div>

              <div v-else class="space-y-4">
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-white font-semibold">{{ auth.user.name }}</p>
                      <p class="text-sm text-gray-400" v-if="auth.user.phone">{{ auth.user.phone }}</p>
                      <p class="text-xs text-red-500 mt-1" v-else>Phone number required</p>
                    </div>
                    <Link href="/portal/profile" class="text-orange-500 hover:text-orange-600 text-sm">Edit Profile</Link>
                  </div>
                </div>

                <!-- Show phone input if missing -->
                <div v-if="!auth.user.phone">
                  <label class="block text-sm font-semibold text-gray-300 mb-2">UK Phone Number *</label>
                  <input 
                    v-model="form.phone"
                    type="tel" 
                    class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                    placeholder="07123456789"
                  >
                  <p class="text-xs text-gray-500 mt-1">We need this for delivery/pickup updates</p>
                </div>
              </div>
            </div>

            <!-- Delivery Address (if delivery selected) -->
            <div v-if="orderType === 'delivery'" class="bg-gray-900 border border-gray-800 rounded-xl p-6">
              <h2 class="text-xl font-bold text-white mb-4">Delivery Address</h2>
              
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-300 mb-2">Address Line 1 *</label>
                  <input 
                    v-model="form.address1"
                    type="text" 
                    class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                    placeholder="123 High Street"
                  >
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-300 mb-2">Address Line 2</label>
                  <input 
                    v-model="form.address2"
                    type="text" 
                    class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                    placeholder="Apartment, suite, etc."
                  >
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">City *</label>
                    <input 
                      v-model="form.city"
                      type="text" 
                      class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                      placeholder="London"
                    >
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Postcode *</label>
                    <input 
                      v-model="form.postcode"
                      type="text" 
                      class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                      placeholder="SW1A 1AA"
                    >
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-300 mb-2">Delivery Instructions</label>
                  <textarea 
                    v-model="form.instructions"
                    rows="3"
                    class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                    placeholder="Ring the doorbell, leave at door, etc."
                  ></textarea>
                </div>
              </div>
            </div>

            <!-- Pickup Details (if pickup selected) -->
            <div v-if="orderType === 'pickup'" class="bg-gray-900 border border-gray-800 rounded-xl p-6">
              <h2 class="text-xl font-bold text-white mb-4">Pickup Details</h2>
              
              <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-4">
                <div class="flex items-start gap-3">
                  <svg class="w-6 h-6 text-orange-500 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  <div>
                    <p class="text-white font-semibold">Smash N Grub</p>
                    <p class="text-sm text-gray-400">204 Melbourne Road, Leicester LE2 0DT</p>
                    <p class="text-sm text-gray-400 mt-1">Open: 11:00 AM - 10:00 PM</p>
                  </div>
                </div>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Pickup Time</label>
                <select 
                  v-model="form.pickupTime"
                  class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                >
                  <option value="asap">As soon as possible (15-20 mins)</option>
                  <option value="30">In 30 minutes</option>
                  <option value="60">In 1 hour</option>
                  <option value="90">In 1.5 hours</option>
                  <option value="120">In 2 hours</option>
                </select>
              </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
              <h2 class="text-xl font-bold text-white mb-4">Payment Method</h2>
              
              <div class="space-y-3">
                <button
                  @click="paymentMethod = 'card'"
                  :class="[
                    'w-full p-4 rounded-lg border-2 transition flex items-center gap-3',
                    paymentMethod === 'card' 
                      ? 'border-orange-500 bg-orange-500 bg-opacity-10' 
                      : 'border-gray-800 hover:border-gray-700'
                  ]"
                >
                  <svg class="w-6 h-6" :class="paymentMethod === 'card' ? 'text-orange-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                  </svg>
                  <span :class="paymentMethod === 'card' ? 'text-orange-500 font-bold' : 'text-gray-300'">Credit / Debit Card</span>
                </button>

                <button
                  @click="paymentMethod = 'cash'"
                  :class="[
                    'w-full p-4 rounded-lg border-2 transition flex items-center gap-3',
                    paymentMethod === 'cash' 
                      ? 'border-orange-500 bg-orange-500 bg-opacity-10' 
                      : 'border-gray-800 hover:border-gray-700'
                  ]"
                >
                  <svg class="w-6 h-6" :class="paymentMethod === 'cash' ? 'text-orange-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                  <span :class="paymentMethod === 'cash' ? 'text-orange-500 font-bold' : 'text-gray-300'">Cash on {{ orderType === 'delivery' ? 'Delivery' : 'Pickup' }}</span>
                </button>
              </div>

              <!-- Stripe Payment Element -->
              <div v-if="paymentMethod === 'card'" class="mt-6">
                <div v-if="loadingPayment" class="flex flex-col items-center justify-center p-8 bg-gray-800 rounded-lg animate-pulse">
                  <svg class="animate-spin w-8 h-8 text-orange-500 mb-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <p class="text-gray-400 text-sm">Initializing secure payment...</p>
                </div>
                
                <StripePayment
                  v-if="clientSecret && !loadingPayment"
                  ref="stripeRef"
                  :client-secret="clientSecret"
                  :public-key="stripePublicKey"
                  @ready="isStripeReady = true"
                  @processing="processing = $event"
                  @error="handleStripeError"
                />
              </div>
            </div>

            <!-- Special Instructions -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
              <h2 class="text-xl font-bold text-white mb-4">Special Instructions</h2>
              <textarea 
                v-model="form.specialInstructions"
                rows="3"
                class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                placeholder="Any special requests for your order?"
              ></textarea>
            </div>

          </div>

          <!-- Right Side - Order Summary -->
          <div class="lg:col-span-1">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 sticky top-24">
              <h2 class="text-xl font-bold text-white mb-4">Order Summary</h2>

              <!-- Cart Items -->
              <div class="space-y-3 mb-4 max-h-64 overflow-y-auto custom-scrollbar">
                <div 
                  v-for="item in cartStore.items" 
                  :key="item.id"
                  class="flex gap-3 pb-3 border-b border-gray-800"
                >
                  <img :src="item.image" :alt="item.name" class="w-16 h-16 object-cover rounded-lg">
                  <div class="flex-1">
                    <p class="text-white font-semibold text-sm">{{ item.name }}</p>
                    <p class="text-xs text-gray-400">Qty: {{ item.quantity }}</p>
                    <p class="text-orange-500 font-bold text-sm mt-1">£{{ (item.price * item.quantity).toFixed(2) }}</p>
                  </div>
                </div>
              </div>

              <!-- Price Breakdown -->
              <div class="space-y-2 py-4 border-t border-gray-800">
                <div class="flex justify-between text-gray-400">
                  <span>Subtotal</span>
                  <span>£{{ cartStore.total.toFixed(2) }}</span>
                </div>

                <div v-if="orderType === 'delivery'" class="flex justify-between text-gray-400">
                  <span>Delivery Fee</span>
                  <span>£2.50</span>
                </div>

                <div class="flex justify-between text-gray-400">
                  <span>VAT (20%)</span>
                  <span>£{{ (calculateTotal() * 0.2).toFixed(2) }}</span>
                </div>

                <div class="flex justify-between text-xl font-bold text-white pt-2 border-t border-gray-800">
                  <span>Total</span>
                  <span class="text-orange-500">£{{ (calculateTotal() * 1.2).toFixed(2) }}</span>
                </div>
              </div>

              <!-- Place Order Button -->
              <button
                @click="placeOrder"
                :disabled="!isFormValid || processing"
                class="w-full bg-orange-500 text-white py-4 rounded-full font-bold hover:bg-orange-600 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-lg"
              >
                <span v-if="!processing">Place Order</span>
                <span v-else class="flex items-center justify-center gap-2">
                  <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Processing...
                </span>
              </button>

              <p class="text-xs text-gray-500 text-center mt-4">
                By placing this order, you agree to our Terms & Conditions
              </p>
            </div>
          </div>

        </div>
      </div>
    </div>
    
    <!-- Order Confirmation Modal -->
    <OrderConfirmationModal 
      :is-open="showConfirmModal" 
      :order-details="orderDetails"
      @close="showConfirmModal = false"
      @confirm="confirmOrder"
    />

    <StoreStatusModal 
      :is-open="showStatusModal"
      :message="storeStatusMessage"
      @confirm="redirectToMenu"
    />
  </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import axios from 'axios';
import MainLayout from '@/Layouts/MainLayout.vue';
import OrderConfirmationModal from '@/Components/OrderConfirmationModal.vue';
import StoreStatusModal from '@/Components/StoreStatusModal.vue';
import StripePayment from '@/Components/StripePayment.vue';
import { useCartStore } from '@/Stores/cart';

const props = defineProps({
  auth: Object,
  stripePublicKey: String,
});

const cartStore = useCartStore();
const orderType = ref('delivery');
const paymentMethod = ref('card');
const processing = ref(false);
const showConfirmModal = ref(false);
const showStatusModal = ref(false);
const isStoreOpen = ref(true);
const storeStatusMessage = ref('');

const stripeRef = ref(null);
const clientSecret = ref(null);
const loadingPayment = ref(false);
const isStripeReady = ref(false);
const paymentIntentId = ref(null);

const form = ref({
  name: '',
  phone: '',
  email: '',
  address1: '',
  address2: '',
  city: '',
  postcode: '',
  instructions: '',
  pickupTime: 'asap',
  specialInstructions: '',
});

const calculateTotal = () => {
  const subtotal = cartStore.total;
  const deliveryFee = orderType.value === 'delivery' ? 2.50 : 0;
  return subtotal + deliveryFee;
};

const initStripe = async () => {
  if (clientSecret.value || loadingPayment.value) return;
  
  loadingPayment.value = true;
  try {
    const totalAmount = calculateTotal() * 1.2; // Including VAT
    const response = await axios.post('/checkout/create-payment-intent', {
      amount: totalAmount.toFixed(2)
    });
    clientSecret.value = response.data.clientSecret;
  } catch (error) {
    console.error('Failed to create PaymentIntent:', error);
    alert('Could not initialize card payment. Please try cash or refresh the page.');
  } finally {
    loadingPayment.value = false;
  }
};

const checkStoreStatus = async () => {
  try {
    const response = await axios.get('/checkout/status');
    const data = response.data;
    if (!data.can_order) {
      isStoreOpen.value = false;
      storeStatusMessage.value = data.message;
      return false; // Not open
    } else {
        isStoreOpen.value = true;
        storeStatusMessage.value = '';
        return true; // Open
    }
  } catch (error) {
    console.error('Store status check failed:', error);
    // If check fails, we assume closed/error per user requirement ("if server is down... show nice message")
    isStoreOpen.value = false;
    storeStatusMessage.value = 'Store server is not responding. Please try again later.';
    return false;
  }
};

// Check if cart is empty on mount
onMounted(async () => {
  // Check store status immediately
  await checkStoreStatus();
  if (!isStoreOpen.value) {
      showStatusModal.value = true;
  }

  if (cartStore.items.length === 0) {
    alert('Your cart is empty! Please add items before checkout.');
    router.visit('/menu');
    return;
  }
  
  if (paymentMethod.value === 'card') {
    initStripe();
  }
});

watch(paymentMethod, (newVal) => {
  if (newVal === 'card' && !clientSecret.value) {
    initStripe();
  }
});

const handleStripeError = (msg) => {
  console.error('Stripe Error:', msg);
};

const isFormValid = computed(() => {
  if (!form.value.name || !form.value.phone) return false;
  
  if (orderType.value === 'delivery') {
    if (!form.value.address1 || !form.value.city || !form.value.postcode) {
      return false;
    }
  }

  if (paymentMethod.value === 'card' && !isStripeReady.value) {
    return false;
  }
  
  return true;
});

// Prepare order details for confirmation modal
const orderDetails = computed(() => ({
  type: orderType.value === 'delivery' ? 'Delivery' : 'Pickup',
  itemCount: cartStore.items.reduce((sum, item) => sum + item.quantity, 0),
  payment: paymentMethod.value === 'card' ? 'Card' : `Cash on ${orderType.value === 'delivery' ? 'Delivery' : 'Pickup'}`,
  total: (calculateTotal() * 1.2).toFixed(2), // Including VAT
}));

const placeOrder = async () => {
  // Final status check before proceeding
  const isOpen = await checkStoreStatus();
  if (!isOpen) {
      showStatusModal.value = true;
      return;
  }

  // Check if cart is empty
  if (cartStore.items.length === 0) {
    alert('Your cart is empty! Please add items before placing an order.');
    router.visit('/menu');
    return;
  }
  
  // Validate form
  if (!isFormValid.value) {
    alert('Please fill in all required fields.');
    return;
  }
  
  // Show confirmation modal
  showConfirmModal.value = true;
};

const confirmOrder = async () => {
  showConfirmModal.value = false;
  processing.value = true;

  try {
    // 1. If Card payment, confirm with Stripe first
    if (paymentMethod.value === 'card' && stripeRef.value) {
      const result = await stripeRef.value.confirmPayment();
      
      if (result.error) {
        alert(result.error);
        processing.value = false;
        return;
      }
      
      paymentIntentId.value = result.paymentIntentId;
    }

    // 2. Submit order to backend
    router.post('/checkout', {
      orderType: orderType.value,
      paymentMethod: paymentMethod.value,
      paymentIntentId: paymentIntentId.value,
      items: cartStore.items.map(item => ({
        id: item.id,
        name: item.name,
        description: item.description,
        price: item.price,
        quantity: item.quantity,
        customizations: item.customizations || null,
      })),
      name: form.value.name,
      phone: form.value.phone,
      email: form.value.email,
      address1: form.value.address1,
      address2: form.value.address2,
      city: form.value.city,
      postcode: form.value.postcode,
      instructions: form.value.instructions,
      pickupTime: form.value.pickupTime,
      specialInstructions: form.value.specialInstructions,
    }, {
      preserveScroll: true,
      onSuccess: (page) => {
        cartStore.clearCart();
      },
      onError: (errors) => {
        console.error('Order errors:', errors);
        let errorMessage = 'Order failed. Please check:\n';
        Object.values(errors).forEach(err => errorMessage += `- ${err}\n`);
        alert(errorMessage);
      },
      onFinish: () => {
        processing.value = false;
      }
    });
  } catch (err) {
    console.error('Confirmation failed:', err);
    alert('An unexpected error occurred. Please try again.');
    processing.value = false;
  }
};
const redirectToMenu = () => {
  showStatusModal.value = false;
  router.visit('/menu');
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #1f2937;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #374151;
  border-radius: 3px;
}
</style>