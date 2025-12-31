<template>
  <MainLayout>
    <div class="min-h-screen bg-black py-12">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-4xl font-bold text-white mb-2">My Profile</h1>
          <p class="text-gray-400">Manage your account details and preferences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- Sidebar Navigation -->
          <div class="lg:col-span-1">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 sticky top-24">
              <nav class="space-y-2">
                <button
                  @click="activeTab = 'profile'"
                  :class="[
                    'w-full text-left px-4 py-3 rounded-lg transition flex items-center gap-3',
                    activeTab === 'profile' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:bg-gray-800'
                  ]"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  Profile Details
                </button>

                <button
                  @click="activeTab = 'addresses'"
                  :class="[
                    'w-full text-left px-4 py-3 rounded-lg transition flex items-center gap-3',
                    activeTab === 'addresses' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:bg-gray-800'
                  ]"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  Saved Addresses
                </button>

                <button
                  @click="activeTab = 'orders'"
                  :class="[
                    'w-full text-left px-4 py-3 rounded-lg transition flex items-center gap-3',
                    activeTab === 'orders' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:bg-gray-800'
                  ]"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                  </svg>
                  Order History
                </button>

                <button
                  @click="logout"
                  class="w-full text-left px-4 py-3 rounded-lg transition flex items-center gap-3 text-red-400 hover:bg-gray-800 mt-4 border-t border-gray-800 pt-6"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                  </svg>
                  Logout
                </button>
              </nav>
            </div>
          </div>

          <!-- Main Content -->
          <div class="lg:col-span-2">
            
            <!-- Profile Details Tab -->
            <div v-if="activeTab === 'profile'" class="space-y-6">
              <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Personal Information</h2>
                
                <form @submit.prevent="updateProfile" class="space-y-4">
                  <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Full Name</label>
                    <input 
                      v-model="form.name"
                      type="text" 
                      class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                    >
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Phone Number</label>
                    <input 
                      :value="user.phone"
                      type="tel" 
                      disabled
                      class="w-full px-4 py-3 bg-gray-800 border border-gray-700 text-gray-400 rounded-lg cursor-not-allowed"
                    >
                    <p class="text-xs text-gray-500 mt-1">Phone number cannot be changed</p>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Email</label>
                    <input 
                      v-model="form.email"
                      type="email" 
                      class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                    >
                  </div>

                  <div v-if="message.profile" class="p-3 bg-green-900 bg-opacity-20 border border-green-500 rounded-lg">
                    <p class="text-green-400 text-sm">{{ message.profile }}</p>
                  </div>

                  <button
                    type="submit"
                    :disabled="saving.profile"
                    class="bg-orange-500 text-white px-6 py-3 rounded-lg font-bold hover:bg-orange-600 transition disabled:opacity-50"
                  >
                    {{ saving.profile ? 'Saving...' : 'Save Changes' }}
                  </button>
                </form>
              </div>
            </div>

            <!-- Saved Addresses Tab -->
            <div v-if="activeTab === 'addresses'" class="space-y-6">
              <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                  <h2 class="text-2xl font-bold text-white">Saved Addresses</h2>
                  <button
                    @click="showAddressForm = true"
                    class="bg-orange-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-orange-600 transition text-sm"
                  >
                    + Add Address
                  </button>
                </div>

                <!-- Address List -->
                <div v-if="addresses.length > 0" class="space-y-3">
                  <div 
                    v-for="address in addresses" 
                    :key="address.id"
                    class="bg-black border border-gray-800 rounded-lg p-4 hover:border-orange-500 transition"
                  >
                    <div class="flex items-start justify-between">
                      <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                          <h3 class="text-white font-semibold">{{ address.label }}</h3>
                          <span v-if="address.is_default" class="text-xs bg-orange-500 text-white px-2 py-0.5 rounded-full">Default</span>
                        </div>
                        <p class="text-gray-400 text-sm">{{ address.address_line1 }}</p>
                        <p v-if="address.address_line2" class="text-gray-400 text-sm">{{ address.address_line2 }}</p>
                        <p class="text-gray-400 text-sm">{{ address.city }}, {{ address.postcode }}</p>
                      </div>
                      <div class="flex gap-2">
                        <button 
                          @click="editAddress(address)"
                          class="text-gray-400 hover:text-orange-500"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                          </svg>
                        </button>
                        <button 
                          @click="deleteAddress(address.id)"
                          class="text-gray-400 hover:text-red-500"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                  <svg class="w-16 h-16 mx-auto text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  <p class="text-gray-400">No saved addresses</p>
                </div>

                <!-- Add/Edit Address Form -->
                <div v-if="showAddressForm" class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
                  <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-6">
                      <h3 class="text-xl font-bold text-white">{{ editingAddress ? 'Edit' : 'Add' }} Address</h3>
                      <button @click="closeAddressForm" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                      </button>
                    </div>

                    <form @submit.prevent="saveAddress" class="space-y-4">
                      <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Label *</label>
                        <input 
                          v-model="addressForm.label"
                          type="text" 
                          class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                          placeholder="Home, Work, etc."
                        >
                      </div>

                      <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Address Line 1 *</label>
                        <input 
                          v-model="addressForm.address_line1"
                          type="text" 
                          class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                        >
                      </div>

                      <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Address Line 2</label>
                        <input 
                          v-model="addressForm.address_line2"
                          type="text" 
                          class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                        >
                      </div>

                      <div class="grid grid-cols-2 gap-4">
                        <div>
                          <label class="block text-sm font-semibold text-gray-300 mb-2">City *</label>
                          <input 
                            v-model="addressForm.city"
                            type="text" 
                            class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                          >
                        </div>

                        <div>
                          <label class="block text-sm font-semibold text-gray-300 mb-2">Postcode *</label>
                          <input 
                            v-model="addressForm.postcode"
                            type="text" 
                            class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                          >
                        </div>
                      </div>

                      <div class="flex items-center gap-2">
                        <input 
                          v-model="addressForm.is_default"
                          type="checkbox" 
                          id="default"
                          class="w-4 h-4 rounded border-gray-800 text-orange-500 focus:ring-orange-500"
                        >
                        <label for="default" class="text-sm text-gray-300">Set as default address</label>
                      </div>

                      <div class="flex gap-3">
                        <button
                          type="submit"
                          :disabled="saving.address"
                          class="flex-1 bg-orange-500 text-white py-3 rounded-lg font-bold hover:bg-orange-600 transition disabled:opacity-50"
                        >
                          {{ saving.address ? 'Saving...' : 'Save Address' }}
                        </button>
                        <button
                          type="button"
                          @click="closeAddressForm"
                          class="flex-1 border border-gray-800 text-gray-300 py-3 rounded-lg font-semibold hover:bg-gray-800 transition"
                        >
                          Cancel
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- Order History Tab -->
            <div v-if="activeTab === 'orders'" class="space-y-6">
              <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-2xl font-bold text-white mb-6">Order History</h2>

                <!-- Order List -->
                <div v-if="orders.length > 0" class="space-y-4">
                  <div 
                    v-for="order in orders" 
                    :key="order.id"
                    class="bg-black border border-gray-800 rounded-lg p-5 hover:border-orange-500 transition"
                  >
                    <div class="flex items-start justify-between mb-4">
                      <div>
                        <p class="text-white font-bold mb-1">Order #{{ order.id }}</p>
                        <p class="text-sm text-gray-400">{{ formatDate(order.created_at) }}</p>
                      </div>
                      <span :class="[
                        'px-3 py-1 rounded-full text-xs font-semibold',
                        order.status === 'delivered' ? 'bg-green-900 text-green-400' :
                        order.status === 'cancelled' ? 'bg-red-900 text-red-400' :
                        'bg-orange-900 text-orange-400'
                      ]">
                        {{ order.status }}
                      </span>
                    </div>

                    <div class="space-y-2 mb-4">
                      <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm">
                        <span class="text-gray-400">{{ item.quantity }}x {{ item.name }}</span>
                        <span class="text-gray-300">£{{ (item.price * item.quantity).toFixed(2) }}</span>
                      </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                      <span class="text-white font-semibold">Total</span>
                      <span class="text-orange-500 font-bold text-lg">£{{ order.total.toFixed(2) }}</span>
                    </div>

                    <button class="w-full mt-4 border border-gray-800 text-gray-300 py-2 rounded-lg hover:bg-gray-800 transition text-sm font-semibold">
                      Reorder
                    </button>
                  </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                  <svg class="w-16 h-16 mx-auto text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                  </svg>
                  <p class="text-gray-400 mb-4">No orders yet</p>
                  <Link href="/menu" class="text-orange-500 hover:text-orange-600 font-semibold">
                    Start Shopping →
                  </Link>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
  user: Object,
  addresses: Array,
  orders: Array,
});

const activeTab = ref('profile');
const showAddressForm = ref(false);
const editingAddress = ref(null);

const form = reactive({
  name: props.user.name,
  email: props.user.email,
});

const addressForm = reactive({
  label: '',
  address_line1: '',
  address_line2: '',
  city: '',
  postcode: '',
  is_default: false,
});

const saving = reactive({
  profile: false,
  address: false,
});

const message = reactive({
  profile: '',
});

const updateProfile = async () => {
  saving.profile = true;
  message.profile = '';

  router.put('/profile', form, {
    onSuccess: () => {
      message.profile = 'Profile updated successfully!';
      setTimeout(() => message.profile = '', 3000);
    },
    onFinish: () => {
      saving.profile = false;
    },
  });
};

const editAddress = (address) => {
  editingAddress.value = address;
  Object.assign(addressForm, address);
  showAddressForm.value = true;
};

const closeAddressForm = () => {
  showAddressForm.value = false;
  editingAddress.value = null;
  Object.keys(addressForm).forEach(key => {
    addressForm[key] = key === 'is_default' ? false : '';
  });
};

const saveAddress = async () => {
  saving.address = true;

  const url = editingAddress.value 
    ? `/profile/addresses/${editingAddress.value.id}` 
    : '/profile/addresses';
  
  const method = editingAddress.value ? 'put' : 'post';

  router[method](url, addressForm, {
    onSuccess: () => {
      closeAddressForm();
    },
    onFinish: () => {
      saving.address = false;
    },
  });
};

const deleteAddress = (id) => {
  if (confirm('Are you sure you want to delete this address?')) {
    router.delete(`/profile/addresses/${id}`);
  }
};

const logout = () => {
  if (confirm('Are you sure you want to logout?')) {
    router.post('/logout');
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-GB', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  });
};
</script>