<template>
  <MainLayout>
    <!-- Page Header -->
    <section class="bg-gray-900 text-white py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-bold mb-4">About Us</h1>
        <p class="text-xl text-gray-400">Discover our story and passion for food</p>
      </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-16 bg-black">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div>
            <h2 class="text-4xl font-bold text-white mb-6">Our Story</h2>
            <p class="text-gray-400 mb-4 text-lg leading-relaxed">
              At Smash N Grub, we believe in crafting more than just meals, we create experiences. Nestled in the heart of the UK, our journey started with a love for bold flavors and a commitment to freshness. We source local ingredients to bring you smash burgers and comfort bites that pack a punch. Our team, passionate and dedicated, thrives on innovation and sustainability. Come savor our unique twist on classic favoruites and join a community that celebrates food, laughter, and good vibes. Smash your hunger. Grub with love.
            </p>
            
          </div>
          <div class="relative">
            <img 
              src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&h=400&fit=crop" 
              alt="Our Restaurant" 
              class="w-full rounded-2xl shadow-2xl"
            >
            <div class="absolute -bottom-6 -left-6 bg-orange-500 text-white p-6 rounded-2xl shadow-xl">
              <p class="text-4xl font-bold">{{ config.business.yearsInBusiness }}</p>
              <p class="text-sm">Years of Excellence</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-16 bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Mission -->
          <div class="bg-black border border-gray-800 p-8 rounded-2xl hover:border-orange-500 transition-all">
            <div class="w-16 h-16 bg-orange-500 bg-opacity-20 rounded-full flex items-center justify-center mb-6">
              <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4">Our Mission</h3>
            <p class="text-gray-400 leading-relaxed">
              To provide our customers with exceptional dining experiences through delicious food, 
              outstanding service, and a welcoming atmosphere. We strive to bring people together 
              over great meals and create lasting memories.
            </p>
          </div>

          <!-- Vision -->
          <div class="bg-black border border-gray-800 p-8 rounded-2xl hover:border-orange-500 transition-all">
            <div class="w-16 h-16 bg-orange-500 bg-opacity-20 rounded-full flex items-center justify-center mb-6">
              <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4">Our Vision</h3>
            <p class="text-gray-400 leading-relaxed">
              To become the most trusted and beloved food destination in our region, recognized for 
              our quality, innovation, and commitment to customer satisfaction. We aim to set new 
              standards in the food industry.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Statistics -->
    <section class="py-16 bg-orange-500 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
          <div>
            <p class="text-5xl font-bold mb-2">{{ config.business.yearsInBusiness }}</p>
            <p class="text-orange-100">Years Experience</p>
          </div>
          <div>
            <p class="text-5xl font-bold mb-2">{{ config.business.totalCategories }}</p>
            <p class="text-orange-100">Food Categories</p>
          </div>
          <div>
            <p class="text-5xl font-bold mb-2">{{ config.business.totalItems }}</p>
            <p class="text-orange-100">Menu Items</p>
          </div>
          <div>
            <p class="text-5xl font-bold mb-2">10K+</p>
            <p class="text-orange-100">Happy Customers</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Our Values -->
    <section class="py-16 bg-black">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-white mb-4">Our Core Values</h2>
          <p class="text-gray-400">The principles that guide everything we do</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Quality -->
          <div class="text-center p-8 bg-gray-900 rounded-2xl border border-gray-800 hover:border-orange-500 transition-all group">
            <div class="w-20 h-20 bg-orange-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-opacity-30 transition-all">
              <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Quality First</h3>
            <p class="text-gray-400">We never compromise on the quality of our ingredients or preparation.</p>
          </div>

          <!-- Customer Focus -->
          <div class="text-center p-8 bg-gray-900 rounded-2xl border border-gray-800 hover:border-orange-500 transition-all group">
            <div class="w-20 h-20 bg-orange-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-opacity-30 transition-all">
              <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Customer Focus</h3>
            <p class="text-gray-400">Your satisfaction is our top priority in everything we do.</p>
          </div>

          <!-- Innovation -->
          <div class="text-center p-8 bg-gray-900 rounded-2xl border border-gray-800 hover:border-orange-500 transition-all group">
            <div class="w-20 h-20 bg-orange-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-opacity-30 transition-all">
              <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Innovation</h3>
            <p class="text-gray-400">We continuously evolve and improve to serve you better.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16  text-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold mb-6">Ready to Experience Great Food?</h2>
        <p class="text-xl mb-8 text-orange-100">Join thousands of satisfied customers who trust us for their meals</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link href="/menu" class="bg-white text-orange-500 px-8 py-4 rounded-full font-bold hover:bg-gray-100 transition inline-block">
            View Our Menu
          </Link>
          <Link href="/contact" class="border-2 border-white text-white px-8 py-4 rounded-full font-bold hover:bg-white hover:text-orange-500 transition inline-block">
            Contact Us
          </Link>
        </div>
      </div>
    </section>
  </MainLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useWebsiteConfig } from '@/Composables/useWebsiteConfig';

const { config } = useWebsiteConfig();
</script>