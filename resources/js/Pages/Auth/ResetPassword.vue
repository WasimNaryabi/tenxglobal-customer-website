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
        Reset Password
      </h2>
      <p class="mt-3 text-center text-sm text-gray-400 max-w-xs mx-auto">
        Create a new secure password for your account to regain access.
      </p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4 sm:px-0">
      <div class="bg-[#0f0f0f]/80 backdrop-blur-2xl py-10 px-8 shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/5 sm:rounded-[2.5rem] relative overflow-hidden group">
        <!-- Subtle internal glow -->
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
        
        <form @submit.prevent="submit" class="space-y-6 relative z-10">
          <input type="hidden" v-model="form.token">

          <div>
            <label for="email" class="block text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">
              Email Address
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-600 group-focus-within:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                </svg>
              </div>
              <input 
                id="email" 
                v-model="form.email" 
                type="email" 
                required 
                class="premium-input block w-full pl-14 pr-6 py-5 bg-black/40 border border-white/10 rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-300 shadow-inner"
                placeholder="you@example.com"
              >
            </div>
            <p v-if="form.errors.email" class="mt-2 text-sm text-red-500 flex items-center gap-1">{{ form.errors.email }}</p>
          </div>

          <div>
            <label for="password" class="block text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">
              New Password
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-600 group-focus-within:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <input 
                id="password" 
                v-model="form.password" 
                type="password" 
                required 
                class="premium-input block w-full pl-14 pr-6 py-5 bg-black/40 border border-white/10 rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-300 shadow-inner"
                placeholder="••••••••"
              >
            </div>
            <p v-if="form.errors.password" class="mt-2 text-sm text-red-500 flex items-center gap-1">{{ form.errors.password }}</p>
          </div>

          <div>
            <label for="password_confirmation" class="block text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">
              Confirm New Password
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-600 group-focus-within:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <input 
                id="password_confirmation" 
                v-model="form.password_confirmation" 
                type="password" 
                required 
                class="premium-input block w-full pl-14 pr-6 py-5 bg-black/40 border border-white/10 rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 transition-all duration-300 shadow-inner"
                placeholder="••••••••"
              >
            </div>
            <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-500 flex items-center gap-1">{{ form.errors.password_confirmation }}</p>
          </div>

          <div class="pt-4">
            <button 
              type="submit" 
              :disabled="form.processing"
              class="premium-button w-full flex justify-center py-5 px-6 border-none rounded-2xl shadow-[0_10px_30px_rgba(249,115,22,0.3)] text-base font-black text-white bg-gradient-to-r from-orange-500 via-red-500 to-red-600 hover:shadow-[0_15px_40px_rgba(249,115,22,0.5)] transition-all duration-500 transform hover:scale-[1.03] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="!form.processing">Reset My Password</span>
              <span v-else class="flex items-center gap-3">
                 <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                 </svg>
                 Resetting...
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const route = (name) => {
    const routes = {
        'password.update': '/reset-password',
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
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
</style>
