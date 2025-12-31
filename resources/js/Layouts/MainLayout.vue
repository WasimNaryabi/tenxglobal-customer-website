<template>
  <div class="min-h-screen bg-black">
    <header class="bg-black shadow-sm sticky top-0 z-50">
      <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo -->
          <div class="flex items-center">
            <Link href="/" class="text-2xl font-bold flex items-center">
              <span v-if="business.logoImage" class="mr-2">
                <img :src="business.logoImage" :alt="business.name" class="logo-img">
              </span>
              <span v-else class="text-3xl mr-2">{{ business.logo }}</span>
              <span class="text-white">{{ business.name }}</span>
            </Link>
          </div>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center space-x-6">
            <Link 
              v-for="item in navigation.main" 
              :key="item.name"
              :href="item.href" 
              class="font-medium transition-colors"
              :class="isCurrentPage(item.href) ? 'text-orange-500' : 'text-white hover:text-orange-500'"
            >
              {{ item.name }}
            </Link>
          </div>
          
          <!-- Desktop Login/User Section -->
          <div class="hidden md:flex items-center space-x-4">
            <template v-if="$page.props.auth?.user">
              <!-- Cart Button -->
              <button 
                @click="openCart"
                class="relative text-white hover:text-orange-500 transition"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span 
                  v-if="cartStore.itemCount > 0"
                  class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"
                >
                  {{ cartStore.itemCount }}
                </span>
              </button>

              <!-- User Menu -->
              <Link 
                href="/profile" 
                class="flex items-center gap-2 text-white hover:text-orange-500 transition"
              >
                <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold">
                  {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                </div>
                <span class="font-medium">{{ $page.props.auth.user.name }}</span>
              </Link>
              
              <button 
                @click="logout"
                class="text-gray-400 hover:text-white transition"
                title="Logout"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
              </button>
            </template>
            
            <template v-else>
              <!-- Cart Button (Guest) -->
              <button 
                @click="openCart"
                class="relative text-white hover:text-orange-500 transition"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span 
                  v-if="cartStore.itemCount > 0"
                  class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"
                >
                  {{ cartStore.itemCount }}
                </span>
              </button>

              <!-- Login Button -->
              <Link 
                href="/login" 
                class="border border-gray-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-gray-800 hover:border-orange-500 transition"
              >
                {{ buttons.login }}
              </Link>
            </template>
          </div>

          <!-- Mobile Menu Button & Cart -->
          <div class="md:hidden flex items-center gap-3">
            <!-- Mobile Cart Button -->
            <button 
              @click="openCart"
              class="relative text-white hover:text-orange-500 transition p-2"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
              <span 
                v-if="cartStore.itemCount > 0"
                class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"
              >
                {{ cartStore.itemCount }}
              </span>
            </button>

            <!-- Mobile Menu Toggle -->
            <button 
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="text-white p-2"
            >
              <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
              <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile Menu -->
        <div 
          v-if="mobileMenuOpen"
          class="md:hidden border-t border-gray-800 py-4"
        >
          <div class="flex flex-col space-y-4">
            <Link 
              v-for="item in navigation.main" 
              :key="item.name"
              :href="item.href" 
              @click="mobileMenuOpen = false"
              class="font-medium transition-colors px-4 py-2"
              :class="isCurrentPage(item.href) ? 'text-orange-500 bg-gray-900' : 'text-white hover:text-orange-500 hover:bg-gray-900'"
            >
              {{ item.name }}
            </Link>

            <!-- Mobile User Section -->
            <div class="border-t border-gray-800 pt-4 px-4 space-y-3">
              <template v-if="$page.props.auth?.user">
                <Link 
                  href="/profile" 
                  @click="mobileMenuOpen = false"
                  class="flex items-center gap-3 text-white hover:text-orange-500 transition py-2"
                >
                  <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold">
                    {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                  </div>
                  <div>
                    <p class="font-semibold">{{ $page.props.auth.user.name }}</p>
                    <p class="text-xs text-gray-400">View Profile</p>
                  </div>
                </Link>
                
                <button 
                  @click="logout"
                  class="w-full border border-gray-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-gray-800 transition"
                >
                  Logout
                </button>
              </template>
              
              <template v-else>
                <Link 
                  href="/login"
                  @click="mobileMenuOpen = false"
                  class="block border border-gray-600 text-center text-white px-6 py-2 rounded-full font-semibold hover:bg-gray-800 transition"
                >
                  {{ buttons.login }}
                </Link>
              </template>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <main><slot /></main>

    <footer class="text-white py-16" :style="{ backgroundColor: getColor('primary') }">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
          <!-- Business Info -->
          <div class="lg:col-span-2">
            <span v-if="business.logoImage" class="mr-2 inline-block mb-4">
              <img :src="business.logoImage" :alt="business.name" class="h-10">
            </span>
            <h3 v-else class="text-2xl font-bold mb-4">{{ business.logo }} {{ business.name }}</h3>
            <p class="mb-6 opacity-90">{{ business.description }}</p>
            <div class="space-y-3">
              <p class="flex items-center">
                <span class="font-semibold mr-2">📍</span>{{ contact.address }}
              </p>
              <p class="flex items-center">
                <span class="font-semibold mr-2">📧</span>{{ contact.email }}
              </p>
              <p class="flex items-center">
                <span class="font-semibold mr-2">📞</span>{{ contact.phone }}
              </p>
            </div>
          </div>

          <!-- About Links -->
          <div>
            <h4 class="text-lg font-bold mb-4">About</h4>
            <ul class="space-y-2 opacity-90">
              <li v-for="link in navigation.footer.about" :key="link.name">
                <Link :href="link.href" class="hover:opacity-100 transition">
                  ❯ {{ link.name }}
                </Link>
              </li>
            </ul>
          </div>

          <!-- Menu Links -->
          <div>
            <h4 class="text-lg font-bold mb-4">Menu</h4>
            <ul class="space-y-2 opacity-90">
              <li v-for="link in navigation.footer.menu" :key="link.name">
                <Link :href="link.href" class="hover:opacity-100 transition">
                  ❯ {{ link.name }}
                </Link>
              </li>
            </ul>
          </div>

          <!-- Newsletter -->
          <div>
            <h4 class="text-lg font-bold mb-4">{{ newsletter.title }}</h4>
            <p class="mb-3 opacity-90 text-sm">{{ newsletter.description }}</p>
            <form @submit.prevent="subscribe" class="space-y-3">
              <input 
                v-model="email" 
                type="email" 
                :placeholder="newsletter.placeholder"
                required 
                class="w-full px-4 py-3 rounded-full text-gray-900 focus:outline-none"
              >
              <button 
                type="submit" 
                class="w-full py-3 rounded-full font-semibold transition"
                :style="{ 
                  backgroundColor: '#fff',
                  color: getColor('primary')
                }"
              >
                {{ newsletter.buttonText }}
              </button>
            </form>
          </div>
        </div>

        <div class="border-t border-white border-opacity-30 pt-8 text-center opacity-90">
          <p>&copy; {{ new Date().getFullYear() }} {{ business.name }}. All rights reserved.</p>
        </div>
      </div>
    </footer>

    <CartSidebar />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import CartSidebar from '@/Components/CartSidebar.vue';
import { useWebsiteConfig } from '@/Composables/useWebsiteConfig';
import { useCartStore } from '@/Stores/cart';

const { 
  business, 
  contact, 
  navigation, 
  buttons, 
  newsletter,
  getColor,
  getMessage 
} = useWebsiteConfig();

const cartStore = useCartStore();

const email = ref('');
const mobileMenuOpen = ref(false);

// Get current page URL
const page = usePage();
const currentUrl = computed(() => page.url);

// Check if link is current page
const isCurrentPage = (href) => {
  // Handle home page
  if (href === '/' && currentUrl.value === '/') {
    return true;
  }
  
  // Handle other pages
  if (href !== '/' && currentUrl.value.startsWith(href)) {
    return true;
  }
  
  return false;
};

const subscribe = () => {
  router.post('/newsletter/subscribe', { email: email.value }, {
    onSuccess: () => { 
      email.value = ''; 
      alert(newsletter.value.successMessage);
    }
  });
};

const logout = () => {
  if (confirm('Are you sure you want to logout?')) {
    router.post('/logout');
  }
};

const openCart = () => {
  cartStore.openCart();
};
</script>

<style>
.logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  text-decoration: none;
}

.logo-img {
  height: 60px;
  width: auto;
  object-fit: contain;
}
</style>