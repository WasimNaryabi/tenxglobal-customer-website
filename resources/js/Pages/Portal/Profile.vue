<template>
  <CustomerLayout>
    <div class="max-w-4xl mx-auto">
      <header class="mb-10">
        <h1 class="text-4xl font-extrabold text-white tracking-tight">Profile Settings</h1>
        <p class="text-gray-400 mt-2">Update your personal information and security settings.</p>
      </header>

      <div class="space-y-8">
        <!-- Personal Information -->
        <div class="bg-gray-900 border border-gray-800 rounded-3xl shadow-2xl overflow-hidden">
          <div class="p-8 border-b border-gray-800 bg-gray-900/50">
            <h2 class="text-xl font-bold">Personal Information</h2>
          </div>
          <form @submit.prevent="updateProfile" class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Full Name</label>
                <input 
                  v-model="profileForm.name"
                  type="text" 
                  class="block w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all"
                >
                <p v-if="profileForm.errors.name" class="mt-1 text-sm text-red-500">{{ profileForm.errors.name }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                <input 
                  v-model="profileForm.email"
                  type="email" 
                  class="block w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all"
                >
                <p v-if="profileForm.errors.email" class="mt-1 text-sm text-red-500">{{ profileForm.errors.email }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Phone Number</label>
                <input 
                  v-model="profileForm.phone"
                  type="tel" 
                  class="block w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all"
                  placeholder="+44 7123 456789"
                >
                <p v-if="profileForm.errors.phone" class="mt-1 text-sm text-red-500">{{ profileForm.errors.phone }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Company Name (Optional)</label>
                <input 
                  v-model="profileForm.company_name"
                  type="text" 
                  class="block w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all"
                >
              </div>
            </div>

            <div class="flex justify-end pt-4">
              <button 
                type="submit" 
                :disabled="profileForm.processing"
                class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-10 rounded-xl transition-all disabled:opacity-50"
              >
                {{ profileForm.processing ? 'Saving...' : 'Save Changes' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Security / Password -->
        <div class="bg-gray-900 border border-gray-800 rounded-3xl shadow-2xl overflow-hidden">
          <div class="p-8 border-b border-gray-800 bg-gray-900/50">
            <h2 class="text-xl font-bold">Update Password</h2>
          </div>
          <form @submit.prevent="updatePassword" class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-400 mb-2">Current Password</label>
                <input 
                  v-model="passwordForm.current_password"
                  type="password" 
                  class="block w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all"
                  autocomplete="current-password"
                >
                <p v-if="passwordForm.errors.current_password" class="mt-1 text-sm text-red-500">{{ passwordForm.errors.current_password }}</p>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">New Password</label>
                <input 
                  v-model="passwordForm.password"
                  type="password" 
                  class="block w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all"
                  autocomplete="new-password"
                >
                <p v-if="passwordForm.errors.password" class="mt-1 text-sm text-red-500">{{ passwordForm.errors.password }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Confirm New Password</label>
                <input 
                  v-model="passwordForm.password_confirmation"
                  type="password" 
                  class="block w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all"
                  autocomplete="new-password"
                >
              </div>
            </div>

            <div class="flex justify-end pt-4">
              <button 
                type="submit" 
                :disabled="passwordForm.processing"
                class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 px-10 rounded-xl transition-all disabled:opacity-50"
              >
                {{ passwordForm.processing ? 'Updating...' : 'Change Password' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const profileForm = useForm({
    name: user.value?.name || '',
    email: user.value?.email || '',
    phone: user.value?.phone || '',
    company_name: user.value?.company_name || '',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updateProfile = () => {
    profileForm.put('/portal/profile', {
        preserveScroll: true,
        onSuccess: () => {
            alert('Profile updated successfully!');
        }
    });
};

const updatePassword = () => {
    passwordForm.put('/portal/update-password', {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            alert('Password updated successfully!');
        },
        onError: () => {
            if (passwordForm.errors.password) {
                passwordForm.reset('password', 'password_confirmation');
            }
            if (passwordForm.errors.current_password) {
                passwordForm.reset('current_password');
            }
        }
    });
};
</script>
