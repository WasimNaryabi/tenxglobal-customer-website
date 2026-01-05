<template>
  <div class="min-h-screen bg-black flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
      <div class="absolute top-[-20%] left-[-10%] w-[60%] h-[60%] bg-orange-600/10 blur-[150px] rounded-full"></div>
      <div class="absolute bottom-[-20%] right-[-10%] w-[60%] h-[60%] bg-red-600/10 blur-[150px] rounded-full"></div>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
      <div class="text-center mb-10">
        <h1 class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-500 via-red-500 to-red-600 filter drop-shadow-[0_0_15px_rgba(249,115,22,0.4)] mb-2">
          Smash N Grub
        </h1>
        <p class="text-gray-400 text-lg font-medium tracking-wide">Taste the premium quality</p>
      </div>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4 sm:px-0">
      <div class="bg-[#0f0f0f]/80 backdrop-blur-2xl py-12 px-10 shadow-[0_25px_60px_rgba(0,0,0,0.6)] border border-white/5 sm:rounded-[3rem] relative overflow-hidden group">
        <!-- Subtle internal glow -->
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

        <div class="mb-10 text-center">
          <h2 class="text-4xl font-black text-white tracking-tight mb-3">Welcome Back</h2>
          <p class="text-gray-500 font-medium">Sign in to your account</p>
        </div>

        <!-- Status Message (e.g. after password reset) -->
        <div v-if="status" class="mb-8 font-medium text-sm text-green-400 bg-green-500/10 p-4 rounded-2xl border border-green-500/20 flex items-center gap-3 animate-pulse">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ status }}
        </div>
        
        <form @submit.prevent="submit" class="space-y-8 relative z-10">
          <!-- Email Input -->
          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Email Address</label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors duration-300">
                <svg class="h-5 w-5 text-gray-600 group-focus-within:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                </svg>
              </div>
              <input 
                v-model="form.email"
                type="email" 
                required
                class="premium-input block w-full pl-14 pr-6 py-3 bg-black/40 border border-white/10 rounded-2xl text-white placeholder-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-300 shadow-inner"
                placeholder="you@example.com"
              >
            </div>
            <p v-if="errors.email" class="mt-2 text-sm text-red-500 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ errors.email }}
            </p>
          </div>

          <!-- Password Input -->
          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Password</label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors duration-300">
                <svg class="h-5 w-5 text-gray-600 group-focus-within:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <input 
                v-model="form.password"
                type="password" 
                required
                class="premium-input block w-full pl-14 pr-6 py-3 bg-black/40 border border-white/10 rounded-2xl text-white placeholder-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-300 shadow-inner"
                placeholder="••••••••"
              >
            </div>
            <p v-if="errors.password" class="mt-2 text-sm text-red-500 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ errors.password }}
            </p>
          </div>

          <!-- Remember Me & Forgot Password -->
          <div class="flex items-center justify-between px-1">
            <div class="flex items-center">
              <input 
                v-model="form.remember"
                id="remember_me" 
                name="remember_me" 
                type="checkbox" 
                class="h-5 w-5 text-orange-600 focus:ring-orange-500/30 border-white/10 rounded bg-black cursor-pointer transition-all"
              >
              <label for="remember_me" class="ml-3 block text-sm font-bold text-gray-500 cursor-pointer select-none hover:text-gray-400 transition-colors">
                Remember me
              </label>
            </div>
            <Link :href="route('password.request')" class="text-xs font-bold text-orange-500 hover:text-orange-400 transition-colors">
              Forgot password?
            </Link>
          </div>

          <!-- General Error -->
          <div v-if="error" class="p-5 bg-red-950/30 border border-red-500/30 rounded-2xl">
            <p class="text-red-400 text-sm text-center font-bold flex items-center justify-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                {{ error }}
            </p>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="processing"
            class="premium-button w-full py-3 relative group overflow-hidden bg-gradient-to-r from-orange-500 via-red-500 to-red-600 text-white rounded-2xl font-black text-lg transition-all duration-500 transform hover:scale-[1.02] active:scale-95 disabled:opacity-50 shadow-[0_15px_35px_rgba(249,115,22,0.3)] hover:shadow-[0_20px_50px_rgba(249,115,22,0.5)]"
          >
            <span v-if="!processing" class="flex items-center justify-center gap-3">
              Sign In
              <svg class="w-6 h-6 transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </span>
            <span v-else class="flex items-center justify-center gap-3">
              <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Processing...
            </span>
          </button>
        </form>

        <div class="mt-12 pt-8 border-t border-white/5 text-center">
          <p class="text-gray-500 font-medium">
            New to Smash N Grub? 
            <Link :href="route('register')" class="text-orange-500 font-black hover:text-orange-400 transition-colors ml-2 underline decoration-orange-500/30 underline-offset-4">
              Create your account
            </Link>
          </p>
        </div>
      </div>

      <!-- Footer Links -->
      <div class="mt-12 text-center relative z-10">
        <Link href="/" class="text-gray-500 hover:text-white font-bold text-sm transition-all duration-300 flex items-center justify-center gap-3 group">
          <svg class="w-5 h-5 group-hover:-translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Return to Home
        </Link>
      </div>

    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { router, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const processing = ref(false);
const error = ref('');

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const errors = reactive({});

const submit = () => {
    error.value = '';
    processing.value = true;
    
    form.post('/login', {
        onFinish: () => {
            processing.value = false;
        },
        onError: (err) => {
            Object.assign(errors, err);
            if (err.email) {
                error.value = err.email;
            }
        },
    });
};

const route = (name) => {
    const routes = {
        'register': '/register',
        'password.request': '/forgot-password',
    };
    return routes[name] || '#';
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

input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active{
    -webkit-box-shadow: 0 0 0 30px #000 inset !important;
    -webkit-text-fill-color: white !important;
}

.premium-button {
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}
</style>