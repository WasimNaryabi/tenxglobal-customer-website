<template>
  <div class="min-h-screen bg-black flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
      
      <!-- Logo/Brand -->
      <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">Smash N Grub</h1>
        <p class="text-gray-400">Sign in to your account</p>
      </div>

      <!-- Login Card -->
      <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8">
        
        <!-- Step 1: Phone Number -->
        <div v-if="step === 1">
          <h2 class="text-2xl font-bold text-white mb-6">Enter Your Phone</h2>
          
          <form @submit.prevent="sendOTP">
            <div class="mb-6">
              <label class="block text-sm font-semibold text-gray-300 mb-2">UK Phone Number</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">+44</span>
                <input 
                  v-model="phone"
                  type="tel" 
                  class="w-full pl-14 pr-4 py-4 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500 text-lg"
                  placeholder="7123456789"
                  maxlength="10"
                  @input="formatPhone"
                >
              </div>
              <p class="text-xs text-gray-500 mt-2">We'll send you a verification code</p>
            </div>

            <div v-if="error" class="mb-4 p-3 bg-red-900 bg-opacity-20 border border-red-500 rounded-lg">
              <p class="text-red-400 text-sm">{{ error }}</p>
            </div>

            <button
              type="submit"
              :disabled="!phone || phone.length < 10 || sending"
              class="w-full bg-orange-500 text-white py-4 rounded-full font-bold hover:bg-orange-600 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-lg"
            >
              <span v-if="!sending">Send Verification Code</span>
              <span v-else class="flex items-center justify-center gap-2">
                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Sending...
              </span>
            </button>
          </form>
        </div>

        <!-- Step 2: OTP Verification -->
        <div v-if="step === 2">
          <div class="mb-6">
            <button 
              @click="step = 1; otp = ['', '', '', '', '', '']; error = ''"
              class="text-gray-400 hover:text-white flex items-center gap-2 mb-4"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
              Back
            </button>
            <h2 class="text-2xl font-bold text-white mb-2">Enter Code</h2>
            <p class="text-gray-400">We sent a code to +44{{ phone }}</p>
          </div>

          <form @submit.prevent="verifyOTP">
            <!-- OTP Input -->
            <div class="mb-6">
              <div class="flex gap-2 justify-between">
                <input
                  v-for="(digit, index) in otp"
                  :key="index"
                  :ref="el => otpInputs[index] = el"
                  v-model="otp[index]"
                  type="text"
                  maxlength="1"
                  class="w-14 h-14 bg-black border border-gray-800 text-white rounded-lg text-center text-2xl font-bold focus:outline-none focus:border-orange-500"
                  @input="handleOTPInput(index, $event)"
                  @keydown="handleOTPKeydown(index, $event)"
                >
              </div>
            </div>

            <div v-if="error" class="mb-4 p-3 bg-red-900 bg-opacity-20 border border-red-500 rounded-lg">
              <p class="text-red-400 text-sm">{{ error }}</p>
            </div>

            <button
              type="submit"
              :disabled="otp.join('').length < 6 || verifying"
              class="w-full bg-orange-500 text-white py-4 rounded-full font-bold hover:bg-orange-600 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-lg"
            >
              <span v-if="!verifying">Verify Code</span>
              <span v-else class="flex items-center justify-center gap-2">
                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Verifying...
              </span>
            </button>

            <!-- Resend Code -->
            <div class="text-center mt-4">
              <button
                v-if="!resendCountdown"
                @click="resendOTP"
                type="button"
                class="text-orange-500 hover:text-orange-600 text-sm font-semibold"
              >
                Resend Code
              </button>
              <p v-else class="text-gray-500 text-sm">
                Resend code in {{ resendCountdown }}s
              </p>
            </div>
          </form>
        </div>

        <!-- Step 3: Complete Profile (if new user) -->
        <div v-if="step === 3">
          <h2 class="text-2xl font-bold text-white mb-6">Complete Your Profile</h2>
          
          <form @submit.prevent="completeProfile">
            <div class="space-y-4 mb-6">
              <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Full Name *</label>
                <input 
                  v-model="profileForm.name"
                  type="text" 
                  class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                  placeholder="John Doe"
                >
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-300 mb-2">Email (Optional)</label>
                <input 
                  v-model="profileForm.email"
                  type="email" 
                  class="w-full px-4 py-3 bg-black border border-gray-800 text-white rounded-lg focus:outline-none focus:border-orange-500"
                  placeholder="john@example.com"
                >
              </div>
            </div>

            <div v-if="error" class="mb-4 p-3 bg-red-900 bg-opacity-20 border border-red-500 rounded-lg">
              <p class="text-red-400 text-sm">{{ error }}</p>
            </div>

            <button
              type="submit"
              :disabled="!profileForm.name || completing"
              class="w-full bg-orange-500 text-white py-4 rounded-full font-bold hover:bg-orange-600 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-lg"
            >
              <span v-if="!completing">Continue</span>
              <span v-else>Processing...</span>
            </button>
          </form>
        </div>

      </div>

      <!-- Footer Links -->
      <div class="text-center mt-6">
        <Link href="/" class="text-gray-400 hover:text-white text-sm">
          ← Back to Home
        </Link>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router, Link } from '@inertiajs/vue3';

const step = ref(1); // 1: Phone, 2: OTP, 3: Profile (if new)
const phone = ref('');
const otp = ref(['', '', '', '', '', '']);
const otpInputs = ref([]);
const error = ref('');
const sending = ref(false);
const verifying = ref(false);
const completing = ref(false);
const resendCountdown = ref(0);

const profileForm = reactive({
  name: '',
  email: '',
});

// Format phone number (remove non-digits)
const formatPhone = (e) => {
  phone.value = e.target.value.replace(/\D/g, '');
};

// Send OTP
const sendOTP = async () => {
  error.value = '';
  sending.value = true;

  try {
    await router.post('/auth/send-otp', {
      phone: '+44' + phone.value,
    }, {
      onSuccess: () => {
        step.value = 2;
        startResendCountdown();
        // Focus first OTP input
        setTimeout(() => {
          otpInputs.value[0]?.focus();
        }, 100);
      },
      onError: (errors) => {
        error.value = errors.phone || 'Failed to send OTP. Please try again.';
      },
    });
  } catch (e) {
    error.value = 'Something went wrong. Please try again.';
  } finally {
    sending.value = false;
  }
};

// Handle OTP input
const handleOTPInput = (index, event) => {
  const value = event.target.value;
  
  // Only allow numbers
  otp.value[index] = value.replace(/\D/g, '');
  
  // Move to next input if digit entered
  if (value && index < 5) {
    otpInputs.value[index + 1]?.focus();
  }
};

// Handle OTP keydown (backspace)
const handleOTPKeydown = (index, event) => {
  if (event.key === 'Backspace' && !otp.value[index] && index > 0) {
    otpInputs.value[index - 1]?.focus();
  }
};

// Verify OTP
const verifyOTP = async () => {
  error.value = '';
  verifying.value = true;

  try {
    await router.post('/auth/verify-otp', {
      phone: '+44' + phone.value,
      otp: otp.value.join(''),
    }, {
      onSuccess: (page) => {
        // Check if new user (needs profile completion)
        if (page.props.needsProfile) {
          step.value = 3;
        } else {
          // Redirect to intended page or home
          window.location.href = page.props.redirect || '/';
        }
      },
      onError: (errors) => {
        error.value = errors.otp || 'Invalid code. Please try again.';
        otp.value = ['', '', '', '', '', ''];
        otpInputs.value[0]?.focus();
      },
    });
  } catch (e) {
    error.value = 'Verification failed. Please try again.';
  } finally {
    verifying.value = false;
  }
};

// Resend OTP
const resendOTP = async () => {
  otp.value = ['', '', '', '', '', ''];
  error.value = '';
  await sendOTP();
};

// Start resend countdown
const startResendCountdown = () => {
  resendCountdown.value = 60;
  const interval = setInterval(() => {
    resendCountdown.value--;
    if (resendCountdown.value <= 0) {
      clearInterval(interval);
    }
  }, 1000);
};

// Complete profile (for new users)
const completeProfile = async () => {
  error.value = '';
  completing.value = true;

  try {
    await router.post('/auth/complete-profile', {
      phone: '+44' + phone.value,
      name: profileForm.name,
      email: profileForm.email,
    }, {
      onSuccess: (page) => {
        window.location.href = page.props.redirect || '/';
      },
      onError: (errors) => {
        error.value = errors.name || errors.email || 'Failed to save profile.';
      },
    });
  } catch (e) {
    error.value = 'Something went wrong. Please try again.';
  } finally {
    completing.value = false;
  }
};
</script>