<template>
  <Head title="Menu Sync" />

  <div class="min-h-screen bg-gray-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">Menu Sync Dashboard</h1>
        <p class="text-gray-400">Manually sync menu items from POS API to website</p>
      </div>

      <!-- Success/Error Messages -->
      <div v-if="$page.props.flash?.success" class="mb-6 bg-green-900 border border-green-500 text-green-100 px-6 py-4 rounded-lg">
        {{ $page.props.flash.success }}
      </div>
      
      <div v-if="$page.props.flash?.error" class="mb-6 bg-red-900 border border-red-500 text-red-100 px-6 py-4 rounded-lg">
        {{ $page.props.flash.error }}
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Stats Cards -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-400 text-sm mb-1">Total Items</p>
              <p class="text-3xl font-bold text-white">{{ stats.totalItems }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-500 bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </div>
          </div>
          <p class="text-sm text-gray-500 mt-2">{{ stats.activeItems }} active</p>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-400 text-sm mb-1">Categories</p>
              <p class="text-3xl font-bold text-white">{{ stats.totalCategories }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-500 bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
              </svg>
            </div>
          </div>
          <p class="text-sm text-gray-500 mt-2">In menu</p>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-400 text-sm mb-1">Last Sync</p>
              <p class="text-lg font-bold text-white">
                {{ lastSync ? formatDate(lastSync.synced_at) : 'Never' }}
              </p>
            </div>
            <div :class="[
              'w-12 h-12 rounded-full flex items-center justify-center',
              lastSync && lastSync.status === 'success' ? 'bg-green-500 bg-opacity-20' : 'bg-gray-700'
            ]">
              <svg v-if="lastSync && lastSync.status === 'success'" class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              <svg v-else class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
          <p v-if="lastSync" class="text-sm text-gray-500 mt-2">
            {{ lastSync.items_synced }} items synced
          </p>
        </div>
      </div>

      <!-- Sync Button -->
      <div class="bg-gray-800 border border-gray-700 rounded-xl p-8 mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold text-white mb-2">Sync Menu from API</h2>
            <p class="text-gray-400">
              Click the button to fetch the latest menu data from your POS system.
              This will update all items and categories.
            </p>
          </div>
          
          <button 
            @click="syncMenu"
            :disabled="syncing"
            class="bg-orange-500 text-white px-8 py-4 rounded-lg font-bold hover:bg-orange-600 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-3 whitespace-nowrap"
          >
            <svg 
              :class="['w-5 h-5', syncing ? 'animate-spin' : '']" 
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            {{ syncing ? 'Syncing...' : 'Sync Now' }}
          </button>
        </div>

        <!-- Last Sync Details -->
        <div v-if="lastSync" class="mt-6 pt-6 border-t border-gray-700">
          <h3 class="text-lg font-semibold text-white mb-4">Last Sync Details</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-900 rounded-lg p-4">
              <p class="text-gray-400 text-sm mb-1">Status</p>
              <p :class="[
                'font-semibold text-lg capitalize',
                lastSync.status === 'success' ? 'text-green-500' : 
                lastSync.status === 'failed' ? 'text-red-500' : 'text-yellow-500'
              ]">
                {{ lastSync.status }}
              </p>
            </div>
            
            <div class="bg-gray-900 rounded-lg p-4">
              <p class="text-gray-400 text-sm mb-1">Items Synced</p>
              <p class="font-semibold text-lg text-white">{{ lastSync.items_synced }}</p>
            </div>
            
            <div class="bg-gray-900 rounded-lg p-4">
              <p class="text-gray-400 text-sm mb-1">Categories Synced</p>
              <p class="font-semibold text-lg text-white">{{ lastSync.categories_synced }}</p>
            </div>
          </div>
          
          <p v-if="lastSync.message" class="mt-4 text-gray-400 text-sm">
            {{ lastSync.message }}
          </p>
        </div>
      </div>

      <!-- Instructions -->
      <div class="bg-gray-800 border border-gray-700 rounded-xl p-8">
        <h2 class="text-2xl font-bold text-white mb-4">How It Works</h2>
        <div class="space-y-4 text-gray-300">
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
              <span class="text-white font-bold">1</span>
            </div>
            <div>
              <h3 class="text-white font-semibold mb-1">Your website uses LOCAL data</h3>
              <p class="text-gray-400">The menu page shows items from your database, not directly from the API. This makes it super fast!</p>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
              <span class="text-white font-bold">2</span>
            </div>
            <div>
              <h3 class="text-white font-semibold mb-1">When you update menu in POS</h3>
              <p class="text-gray-400">Come to this page and click "Sync Now" to pull the latest changes.</p>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
              <span class="text-white font-bold">3</span>
            </div>
            <div>
              <h3 class="text-white font-semibold mb-1">Or use the command line</h3>
              <div class="bg-gray-900 rounded-lg p-4 mt-2 font-mono text-sm">
                <code class="text-green-400">php artisan menu:sync</code>
              </div>
            </div>
          </div>
          
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
              <span class="text-white font-bold">4</span>
            </div>
            <div>
              <h3 class="text-white font-semibold mb-1">Or call the API endpoint</h3>
              <div class="bg-gray-900 rounded-lg p-4 mt-2 font-mono text-sm">
                <code class="text-green-400">POST /api/menu/sync</code><br>
                <code class="text-gray-500">Header: X-Sync-Token: your-token</code>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
  lastSync: Object,
  stats: Object,
});

const syncing = ref(false);

const syncMenu = () => {
  if (syncing.value) return;
  
  syncing.value = true;
  
  router.post('/admin/menu/sync', {}, {
    onFinish: () => {
      syncing.value = false;
    }
  });
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  const now = new Date();
  const diffMs = now - date;
  const diffMins = Math.floor(diffMs / 60000);
  const diffHours = Math.floor(diffMs / 3600000);
  const diffDays = Math.floor(diffMs / 86400000);
  
  if (diffMins < 1) return 'Just now';
  if (diffMins < 60) return `${diffMins} minute${diffMins > 1 ? 's' : ''} ago`;
  if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
  if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
  
  return date.toLocaleDateString('en-GB', { 
    day: 'numeric', 
    month: 'short', 
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};
</script>