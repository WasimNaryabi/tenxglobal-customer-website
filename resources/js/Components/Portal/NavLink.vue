<template>
  <Link 
    :href="href"
    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
    :class="active 
      ? 'bg-orange-500 text-white shadow-[0_0_15px_rgba(249,115,22,0.4)]' 
      : 'text-gray-400 hover:bg-gray-800 hover:text-white'"
  >
    <div :class="active ? 'text-white' : 'text-gray-500 group-hover:text-orange-500 transition-colors'">
      <!-- Dashboard Icon -->
      <svg v-if="icon === 'dashboard'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
      </svg>
      <!-- Profile Icon -->
      <svg v-if="icon === 'profile'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
      </svg>
      <!-- Orders Icon -->
      <svg v-if="icon === 'orders'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
      </svg>
    </div>
    <span class="font-semibold text-sm tracking-wide"><slot /></span>
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  href: String,
  active: Boolean,
  icon: String
});
</script>
