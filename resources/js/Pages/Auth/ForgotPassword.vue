<template>
  <div class="min-h-screen bg-black flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
      <div class="absolute top-[-20%] left-[-10%] w-[60%] h-[60%] bg-orange-600/10 blur-[150px] rounded-full"></div>
      <div class="absolute bottom-[-20%] right-[-10%] w-[60%] h-[60%] bg-red-600/10 blur-[150px] rounded-full"></div>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
      <Link href="/" class="flex justify-center mb-8">
        <div class="flex flex-col items-center">
            <h2 class="text-5xl font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-orange-500 via-red-500 to-red-600 filter drop-shadow-[0_0_10px_rgba(249,115,22,0.3)]">
                Smash N Grub
            </h2>
            <div class="h-1 w-24 bg-gradient-to-r from-orange-500 to-red-600 rounded-full mt-1"></div>
        </div>
      </Link>
      <h2 class="text-center text-3xl font-bold text-white tracking-tight">
        {{ displayStatus ? 'Check your email' : 'Forgot your password?' }}
      </h2>
      <p class="mt-3 text-center text-sm text-gray-400 max-w-xs mx-auto">
        {{ displayStatus ? "We've sent a password reset link to your email address." : "No worries! Enter your email and we'll send you a secure link to reset it." }}
      </p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4 sm:px-0">
      <div class="bg-[#0f0f0f]/80 backdrop-blur-2xl py-10 px-8 shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/5 sm:rounded-[2.5rem] relative overflow-hidden group">
        <!-- Subtle internal glow -->
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
        
        <!-- Success State -->
        <div v-if="displayStatus" class="text-center py-4 relative z-10">
            <div class="w-20 h-20 bg-green-500/10 border border-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-[0_0_30px_rgba(34,197,94,0.1)]">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Email Sent Successfully</h3>
            <p class="text-gray-500 mb-8 px-4 text-sm leading-relaxed">
                {{ displayStatus }}
            </p>
            <Link href="/login" class="inline-flex items-center gap-2 text-orange-500 font-bold hover:text-orange-400 transition-colors">
                Back to Sign In
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </Link>
        </div>

        <!-- Form State -->
        <form v-else @submit.prevent="submit" class="space-y-8 relative z-10">
          <div>
            <label for="email" class="block text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">
              Registered Email
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors duration-300">
                <svg class="h-5 w-5 text-gray-600 group-focus-within:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                </svg>
              </div>
              <input 
                id="email" 
                v-model="form.email" 
                type="email" 
                required 
                class="premium-input block w-full pl-14 pr-6 py-4 bg-black/40 border border-white/10 rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-300 shadow-inner"
                placeholder="you@example.com"
              >
            </div>
            <p v-if="form.errors.email" class="mt-2 text-sm text-red-500 flex items-center gap-1">
               <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
               </svg>
               {{ form.errors.email }}
            </p>
          </div>

          <div>
            <button 
              type="submit" 
              :disabled="form.processing"
              class="premium-button w-full flex justify-center py-4 px-6 border-none rounded-2xl shadow-[0_10px_30px_rgba(249,115,22,0.3)] text-base font-black text-white bg-gradient-to-r from-orange-500 via-red-500 to-red-600 hover:shadow-[0_15px_40px_rgba(249,115,22,0.5)] transition-all duration-500 transform hover:scale-[1.03] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
            >
              <span v-if="!form.processing" class="flex items-center gap-2">
                Send Reset Link
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
              </span>
              <span v-else class="flex items-center gap-3">
                 <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                 </svg>
                 Processing...
              </span>
            </button>
          </div>

          <div class="mt-8 text-center relative z-10 pt-4 border-t border-white/5">
            <Link href="/login" class="text-sm font-bold text-gray-500 hover:text-white transition-colors flex items-center justify-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Back to Sign In
            </Link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    status: String,
});

const page = usePage();

// More robust way to check for status message
const displayStatus = computed(() => {
    return props.status || page.props.status || page.props.flash?.message || page.props.flash?.success;
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post('/forgot-password', {
        preserveScroll: true,
    });
};
</script>

<style scoped>
.premium-input {
    background-color: rgba(0, 0, 0, 0.45) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
}

.premium-input:focus {
    background-color: rgba(0, 0, 0, 0.6) !important;
    border-color: rgba(249, 115, 22, 0.5) !important;
}

/* Override browser autofill background */
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active{
    -webkit-box-shadow: 0 0 0 30px #000 inset !important;
    -webkit-text-fill-color: white !important;
}

.premium-button {
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
</style>
