<template>
  <section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <!-- Image Side -->
        <div class="relative">
          <img 
            src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&h=600&fit=crop" 
            alt="Delicious Burger" 
            class="w-full rounded-3xl shadow-2xl border border-gray-800"
          >
          <div class="absolute -bottom-6 -right-6 bg-orange-500 text-white p-6 rounded-2xl shadow-xl">
            <p class="text-4xl font-bold">10+</p>
            <p class="text-sm">Years Experience</p>
          </div>
        </div>

        <!-- Content Side -->
        <div>
          <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
            Good Food. Great Taste.<br>Made By Us For You.
          </h2>
          <p class="text-gray-400 mb-8 text-lg leading-relaxed">
            We believe in serving only the finest quality food made with fresh ingredients. 
            Our experienced chefs prepare each dish with passion and care, ensuring that 
            every bite is a delightful experience.
          </p>

          <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="text-center bg-gray-900 border border-gray-800 rounded-xl p-4 hover:border-orange-500 transition">
              <p class="text-4xl font-bold text-orange-500 mb-2">10+</p>
              <p class="text-gray-400 text-sm">Years</p>
            </div>
            <div class="text-center bg-gray-900 border border-gray-800 rounded-xl p-4 hover:border-orange-500 transition">
              <p class="text-4xl font-bold text-orange-500 mb-2">15+</p>
              <p class="text-gray-400 text-sm">Categories</p>
            </div>
            <div class="text-center bg-gray-900 border border-gray-800 rounded-xl p-4 hover:border-orange-500 transition">
              <p class="text-4xl font-bold text-orange-500 mb-2">60+</p>
              <p class="text-gray-400 text-sm">Items</p>
            </div>
          </div>

          <Link 
            href="/about" 
            class="inline-block bg-orange-500 text-white px-8 py-4 rounded-full font-bold hover:bg-orange-600 transition shadow-lg hover:scale-105"
          >
            Read More
          </Link>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>