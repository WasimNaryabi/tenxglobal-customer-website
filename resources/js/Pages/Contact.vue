<template>
  <MainLayout>
    <!-- Page Header -->
    <section class="bg-gray-900 text-white py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-bold mb-4">Contact Us</h1>
        <p class="text-xl text-gray-400">We'd love to hear from you</p>
      </div>
    </section>

    <!-- Contact Form & Info -->
    <section class="py-16 bg-black">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
          <!-- Contact Form -->
          <div>
            <h2 class="text-3xl font-bold text-white mb-6">Send Us a Message</h2>
            <p class="text-gray-400 mb-8">Have a question or feedback? Fill out the form below and we'll get back to you as soon as possible.</p>

            <form @submit.prevent="submitForm" class="space-y-6">
              <!-- Name -->
              <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Full Name *</label>
                <input 
                  v-model="form.name"
                  type="text" 
                  required
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  placeholder="John Doe"
                >
              </div>

              <!-- Email -->
              <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Email Address *</label>
                <input 
                  v-model="form.email"
                  type="email" 
                  required
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  placeholder="john@example.com"
                >
              </div>

              <!-- Phone -->
              <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Phone Number</label>
                <input 
                  v-model="form.phone"
                  type="tel"
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  placeholder="+44 1234 567890"
                >
              </div>

              <!-- Subject -->
              <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Subject *</label>
                <select 
                  v-model="form.subject"
                  required
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
                  <option value="">Select a subject</option>
                  <option value="general">General Inquiry</option>
                  <option value="order">Order Related</option>
                  <option value="feedback">Feedback</option>
                  <option value="complaint">Complaint</option>
                  <option value="partnership">Partnership Opportunity</option>
                </select>
              </div>

              <!-- Message -->
              <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Message *</label>
                <textarea 
                  v-model="form.message"
                  required
                  rows="5"
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none"
                  placeholder="Tell us how we can help you..."
                ></textarea>
              </div>

              <!-- Submit Button -->
              <button 
                type="submit"
                :disabled="processing"
                class="w-full bg-orange-500 text-white py-4 rounded-lg font-bold hover:bg-orange-600 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-lg"
              >
                {{ processing ? 'Sending...' : 'Send Message' }}
              </button>

              <!-- Success/Error Messages -->
              <div v-if="success" class="bg-green-900 border border-green-500 text-green-100 px-4 py-3 rounded-lg">
                Thank you! Your message has been sent successfully. We'll get back to you soon.
              </div>
              <div v-if="error" class="bg-red-900 border border-red-500 text-red-100 px-4 py-3 rounded-lg">
                {{ error }}
              </div>
            </form>
          </div>

          <!-- Contact Information -->
          <div>
            <h2 class="text-3xl font-bold text-white mb-6">Get in Touch</h2>
            <p class="text-gray-400 mb-8">Feel free to reach out through any of these channels</p>

            <!-- Contact Cards -->
            <div class="space-y-6">
              <!-- Phone -->
              <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl hover:border-orange-500 transition-all">
                <div class="flex items-start">
                  <div class="w-12 h-12 bg-orange-500 bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-lg font-bold text-white mb-1">Phone</h3>
                    <p class="text-gray-300">{{ config.contact.phone }}</p>
                    <p class="text-sm text-gray-500 mt-1">Mon-Sun: 9:00 AM - 11:00 PM</p>
                  </div>
                </div>
              </div>

              <!-- Email -->
              <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl hover:border-orange-500 transition-all">
                <div class="flex items-start">
                  <div class="w-12 h-12 bg-orange-500 bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-lg font-bold text-white mb-1">Email</h3>
                    <p class="text-gray-300">{{ config.contact.email }}</p>
                    <p class="text-sm text-gray-500 mt-1">We'll respond within 24 hours</p>
                  </div>
                </div>
              </div>

              <!-- Address -->
              <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl hover:border-orange-500 transition-all">
                <div class="flex items-start">
                  <div class="w-12 h-12 bg-orange-500 bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-lg font-bold text-white mb-1">Address</h3>
                    <p class="text-gray-300">{{ config.contact.address }}</p>
                    <a 
                      :href="getDirectionsUrl()" 
                      target="_blank"
                      class="text-orange-500 text-sm mt-2 inline-block hover:underline"
                    >
                      Get Directions →
                    </a>
                  </div>
                </div>
              </div>

              <!-- Business Hours -->
              <div class="bg-orange-500 text-white p-6 rounded-xl">
                <div class="flex items-start">
                  <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <div class="ml-4">
                    <h3 class="text-lg font-bold mb-2">Business Hours</h3>
                    <div class="space-y-1 text-sm">
                      <p>Monday - Friday: {{ config.contact.hours.weekdays }}</p>
                      <p>Saturday - Sunday: {{ config.contact.hours.weekends }}</p>
                      <p class="font-semibold mt-2">{{ config.contact.hours.delivery }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Social Media -->
            <div class="mt-8">
              <h3 class="text-lg font-bold text-white mb-4">Follow Us</h3>
              <div class="flex gap-4">
                <a v-if="config.contact.social.facebook" :href="config.contact.social.facebook" target="_blank" class="w-12 h-12 bg-gray-900 border border-gray-800 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white transition text-gray-400">
                  <span class="text-xl">f</span>
                </a>
                <a v-if="config.contact.social.instagram" :href="config.contact.social.instagram" target="_blank" class="w-12 h-12 bg-gray-900 border border-gray-800 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white transition text-gray-400">
                  <span class="text-xl">📷</span>
                </a>
                <a v-if="config.contact.social.twitter" :href="config.contact.social.twitter" target="_blank" class="w-12 h-12 bg-gray-900 border border-gray-800 rounded-full flex items-center justify-center hover:bg-orange-500 hover:border-orange-500 hover:text-white transition text-gray-400">
                  <span class="text-xl">🐦</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Map Section with Google Maps -->
    <section class="py-16 bg-black">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-white mb-8 text-center">Find Us on Map</h2>
        
        <!-- Google Maps Embed -->
        <div class="relative h-[500px] rounded-2xl overflow-hidden shadow-2xl border-2 border-gray-800">
          <iframe
            :src="getMapEmbedUrl()"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            class="w-full h-full"
          ></iframe>
        </div>

        <!-- Location Info Overlay (Optional) -->
        <div class="mt-8 bg-gray-900 border border-gray-800 rounded-xl p-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div>
              <div class="text-orange-500 mb-2">
                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <h3 class="text-white font-bold mb-1">Location</h3>
              <p class="text-gray-400 text-sm">{{ config.contact.address }}</p>
            </div>
            <div>
              <div class="text-orange-500 mb-2">
                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <h3 class="text-white font-bold mb-1">Hours</h3>
              <p class="text-gray-400 text-sm">Mon-Sun: 4PM - 11PM</p>
            </div>
            <div>
              <div class="text-orange-500 mb-2">
                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
              </div>
              <h3 class="text-white font-bold mb-1">Call Us</h3>
              <p class="text-gray-400 text-sm">{{ config.contact.phone }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-gray-900">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-white mb-8 text-center">Frequently Asked Questions</h2>
        
        <div class="space-y-4">
          <div class="bg-black border border-gray-800 p-6 rounded-xl hover:border-orange-500 transition-all">
            <h3 class="text-lg font-bold text-white mb-2">What are your delivery hours?</h3>
            <p class="text-gray-400">We offer delivery service {{ config.contact.hours.delivery }}. Orders are typically delivered within 30-45 minutes.</p>
          </div>

          <div class="bg-black border border-gray-800 p-6 rounded-xl hover:border-orange-500 transition-all">
            <h3 class="text-lg font-bold text-white mb-2">Do you accept online payments?</h3>
            <p class="text-gray-400">Yes! We accept all major credit cards, debit cards, and online payment methods for your convenience.</p>
          </div>

          <div class="bg-black border border-gray-800 p-6 rounded-xl hover:border-orange-500 transition-all">
            <h3 class="text-lg font-bold text-white mb-2">Can I cancel or modify my order?</h3>
            <p class="text-gray-400">You can cancel or modify your order within 5 minutes of placing it. Please contact us immediately at {{ config.contact.phone }}.</p>
          </div>

          <div class="bg-black border border-gray-800 p-6 rounded-xl hover:border-orange-500 transition-all">
            <h3 class="text-lg font-bold text-white mb-2">Do you cater for events?</h3>
            <p class="text-gray-400">Yes! We offer catering services for events of all sizes. Please contact us for custom quotes and menu options.</p>
          </div>
        </div>
      </div>
    </section>
  </MainLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useWebsiteConfig } from '@/Composables/useWebsiteConfig';

const { config } = useWebsiteConfig();

const form = ref({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: ''
});

const processing = ref(false);
const success = ref(false);
const error = ref('');

// Map configuration
const mapConfig = {
  // Replace with your actual coordinates
  latitude: 52.6248,  // Leicester coordinates (example)
  longitude: -1.1315,
  zoom: 15,
  // Your Google Maps API key (get from https://console.cloud.google.com/)
  apiKey: 'YOUR_GOOGLE_MAPS_API_KEY' // Replace with actual key
};

// Get Google Maps embed URL
const getMapEmbedUrl = () => {
  // Option 1: Using place name
//   const placeName = encodeURIComponent('204 Melbourne Road, Leicester LE2 0DT');
//   return `https://www.google.com/maps/embed/v1/place?key=${mapConfig.apiKey}&q=${placeName}&zoom=${mapConfig.zoom}`;
  
  // Option 2: Using coordinates (alternative)
   return `https://www.google.com/maps/embed/v1/view?key=${mapConfig.apiKey}&center=${mapConfig.latitude},${mapConfig.longitude}&zoom=${mapConfig.zoom}`;
};

// Get directions URL
const getDirectionsUrl = () => {
  const address = encodeURIComponent(config.contact.address);
  return `https://www.google.com/maps/dir/?api=1&destination=${address}`;
};

const submitForm = () => {
  processing.value = true;
  success.value = false;
  error.value = '';

  router.post('/contact/submit', form.value, {
    onSuccess: () => {
      success.value = true;
      form.value = { name: '', email: '', phone: '', subject: '', message: '' };
      processing.value = false;
    },
    onError: (errors) => {
      error.value = 'There was an error sending your message. Please try again.';
      processing.value = false;
    }
  });
};
</script>