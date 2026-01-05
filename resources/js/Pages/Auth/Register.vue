<template>
  <div class="min-h-screen bg-black flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
      <div class="absolute top-[-20%] left-[-10%] w-[60%] h-[60%] bg-orange-600/10 blur-[150px] rounded-full"></div>
      <div class="absolute bottom-[-20%] right-[-10%] w-[60%] h-[60%] bg-red-600/10 blur-[150px] rounded-full"></div>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
      <div class="text-center mb-8">
        <h1 class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-500 via-red-500 to-red-600 filter drop-shadow-[0_0_15px_rgba(249,115,22,0.4)] mb-2">
          Smash N Grub
        </h1>
        <p class="text-gray-400 text-lg font-medium">Join the premier food experience</p>
      </div>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4 sm:px-0">
      <div class="bg-[#0f0f0f]/80 backdrop-blur-2xl py-10 px-10 shadow-[0_25px_60px_rgba(0,0,0,0.6)] border border-white/5 sm:rounded-[3rem] relative overflow-hidden group">
        <!-- Subtle internal glow -->
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

        <div class="mb-8 text-center">
          <h2 class="text-3xl font-black text-white tracking-tight mb-2">Create Account</h2>
          <p class="text-gray-500 font-medium">Get started with your premium account</p>
        </div>
        
        <form @submit.prevent="submit" class="space-y-6 relative z-10">
          <!-- Name Input -->
          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-2.5 ml-1">Full Name</label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors duration-300">
                <svg class="h-5 w-5 text-gray-600 group-focus-within:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <input 
                v-model="form.name"
                type="text" 
                required
                class="premium-input block w-full pl-14 pr-6 py-3 bg-black/40 border border-white/10 rounded-2xl text-white placeholder-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-300 shadow-inner"
                placeholder="John Doe"
              >
            </div>
            <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-500">{{ form.errors.name }}</p>
          </div>

          <!-- Email Input -->
          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-2.5 ml-1">Email Address</label>
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
                placeholder="name@example.com"
              >
            </div>
            <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-500">{{ form.errors.email }}</p>
          </div>

          <!-- Password Input -->
          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-2.5 ml-1">Password</label>
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
            <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-500">{{ form.errors.password }}</p>
          </div>

          <!-- Confirm Password Input -->
          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-2.5 ml-1">Confirm Password</label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors duration-300">
                <svg class="h-5 w-5 text-gray-600 group-focus-within:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
              </div>
              <input 
                v-model="form.password_confirmation"
                type="password" 
                required
                class="premium-input block w-full pl-14 pr-6 py-3 bg-black/40 border border-white/10 rounded-2xl text-white placeholder-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-300 shadow-inner"
                placeholder="••••••••"
              >
            </div>
          </div>
          
          <!-- Remember Me -->
          <div class="flex items-center px-1">
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

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="form.processing"
            class="premium-button w-full relative group overflow-hidden bg-gradient-to-r from-orange-500 via-red-500 to-red-600 text-white py-3 rounded-2xl font-black text-lg transition-all duration-500 transform hover:scale-[1.02] active:scale-95 disabled:opacity-50 shadow-[0_15px_35px_rgba(249,115,22,0.3)] hover:shadow-[0_20px_50px_rgba(249,115,22,0.5)] mt-4"
          >
            <span v-if="!form.processing" class="flex items-center justify-center gap-3">
              Create Account
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

        <div class="mt-10 pt-8 border-t border-white/5 text-center px-2">
          <p class="text-gray-500 font-medium">
            Already have an account? 
            <Link href="/login" class="text-orange-500 font-black hover:text-orange-400 transition-colors ml-2 underline decoration-orange-500/30 underline-offset-4">
              Sign in instead
            </Link>
          </p>
        </div>
      </div>

      <!-- Footer Links -->
      <div class="mt-10 text-center relative z-10">
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
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    remember: false,
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const route = (name) => {
    const routes = {
        'login': '/login',
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
