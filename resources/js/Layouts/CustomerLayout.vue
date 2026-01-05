<template>
  <div class="min-h-screen bg-black text-white selection:bg-orange-500/30">
    <!-- Sidebar -->
    <aside 
      class="fixed left-0 top-0 h-full w-64 bg-gray-900 border-r border-gray-800 z-50 transition-transform duration-300 lg:translate-x-0"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <div class="p-6">
        <Link href="/" class="flex items-center gap-3">
          <span class="text-2xl font-bold bg-gradient-to-r from-orange-400 to-red-600 bg-clip-text text-transparent">
            Smash N Grub
          </span>
        </Link>
      </div>

      <nav class="mt-6 px-4 space-y-2">
        <NavLink 
          href="/portal/dashboard" 
          icon="dashboard"
          :active="$page.component === 'Portal/Dashboard'"
        >
          Dashboard
        </NavLink>
        <NavLink 
          href="/portal/profile" 
          icon="profile"
          :active="$page.component === 'Portal/Profile'"
        >
          My Profile
        </NavLink>
        <NavLink 
          href="/portal/orders" 
          icon="orders"
          :active="$page.component === 'Portal/Orders'"
        >
          My Orders
        </NavLink>
      </nav>

      <div class="absolute bottom-0 w-full p-4 border-t border-gray-800">
        <div class="flex items-center gap-3 px-2 py-3">
          <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center font-bold text-lg">
            {{ userInitials }}
          </div>
          <div class="flex-1 overflow-hidden">
            <p class="text-sm font-bold truncate">{{ user?.name }}</p>
            <p class="text-xs text-gray-500 truncate">{{ user?.email }}</p>
          </div>
        </div>
        <button 
          @click="logout"
          class="w-full mt-2 flex items-center gap-2 px-3 py-2 text-red-500 hover:bg-red-500/10 rounded-lg transition-colors text-sm font-medium"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Logout
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
      <!-- Top Header (Mobile Only) -->
      <header class="lg:hidden h-16 bg-gray-900 border-b border-gray-800 flex items-center justify-between px-4 sticky top-0 z-40">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-400 hover:text-white">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
        </button>
        <span class="font-bold text-orange-500">Smash N Grub</span>
        <div class="w-10"></div>
      </header>

      <!-- Page Content -->
      <div class="p-4 lg:p-8">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/Portal/NavLink.vue';

const sidebarOpen = ref(false);
const page = usePage();
const user = computed(() => page.props.auth.user);

const userInitials = computed(() => {
  if (!user.value?.name) return '??';
  return user.value.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
});

const logout = () => {
    router.post('/logout');
};
</script>

<style scoped>
.selection-orange {
  --tw-selection-bg: rgba(249, 115, 22, 0.3);
}
</style>
