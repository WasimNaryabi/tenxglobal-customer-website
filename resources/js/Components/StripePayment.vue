<template>
  <div class="stripe-container mt-4">
    <div 
      id="payment-element" 
      class="p-4 bg-gray-800 rounded-lg border border-gray-700 min-h-[150px]"
    >
      <!-- Stripe Elements will be mounted here -->
    </div>
    <div v-if="error" class="mt-2 text-red-500 text-sm">
      {{ error }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { loadStripe } from '@stripe/stripe-js';

const props = defineProps({
  clientSecret: {
    type: String,
    required: true
  },
  publicKey: {
    type: String,
    required: true
  }
});

const emit = defineEmits(['ready', 'success', 'error', 'processing']);

const stripe = ref(null);
const elements = ref(null);
const error = ref(null);
const isMounted = ref(false);

onMounted(async () => {
  try {
    stripe.value = await loadStripe(props.publicKey);
    
    if (!stripe.value) {
      throw new Error('Failed to load Stripe.js');
    }

    const options = {
      clientSecret: props.clientSecret,
      appearance: {
        theme: 'night',
        variables: {
          colorPrimary: '#f97316', // Orange-500
          colorBackground: '#1f2937', // Gray-800
          colorText: '#ffffff',
          colorDanger: '#ef4444',
          fontFamily: 'Inter, system-ui, sans-serif',
          spacingUnit: '4px',
          borderRadius: '8px',
        },
      },
    };

    elements.value = stripe.value.elements(options);
    const paymentElement = elements.value.create('payment');
    paymentElement.mount('#payment-element');
    
    paymentElement.on('ready', () => {
      emit('ready');
      isMounted.value = true;
    });

    paymentElement.on('change', (event) => {
      if (event.error) {
        error.value = event.error.message;
        emit('error', event.error.message);
      } else {
        error.value = null;
      }
    });

  } catch (e) {
    console.error('Stripe initialization failed:', e);
    error.value = 'Failed to initialize payment system.';
    emit('error', error.value);
  }
});

const confirmPayment = async () => {
  if (!stripe.value || !elements.value) return { error: 'Payment system not ready' };

  emit('processing', true);
  
  const { error: stripeError, paymentIntent } = await stripe.value.confirmPayment({
    elements: elements.value,
    confirmParams: {
      // Return URL is required, but we'll try to handle completion manually if possible
      // or redirect to the confirmation page.
      // Since we want to submit the order to OUR backend after success,
      // we might prefer to confirm without redirection if Stripe supports it for the current method.
      return_url: window.location.origin + '/checkout',
    },
    redirect: 'if_required',
  });

  if (stripeError) {
    error.value = stripeError.message;
    emit('processing', false);
    return { error: stripeError.message };
  }

  if (paymentIntent.status === 'succeeded') {
    emit('success', paymentIntent.id);
    return { paymentIntentId: paymentIntent.id };
  }

  return { error: 'Payment status: ' + paymentIntent.status };
};

// Expose confirmPayment to parent
defineExpose({
  confirmPayment
});
</script>

<style scoped>
#payment-element {
  width: 100%;
}
</style>
