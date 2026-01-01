import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useCartStore = defineStore('cart', () => {
  const items = ref([]);
  const isOpen = ref(false);

  const itemCount = computed(() => {
    return items.value.reduce((total, item) => total + item.quantity, 0);
  });

  const total = computed(() => {
    return items.value.reduce((total, item) => {
      return total + (item.price * item.quantity);
    }, 0);
  });

  const addItem = (product) => {
    // Generate a unique signature for the customizations
    const customizationKey = JSON.stringify(product.customizations || {});

    // Calculate the effective unit price (Base + Addons)
    // If totalPrice is provided, derive unit price from it. Fallback to base price.
    const effectiveUnitPrice = product.totalPrice
      ? (product.totalPrice / (product.quantity || 1))
      : product.price;

    // Find if this exact item configuration exists
    const existingItem = items.value.find(item =>
      item.id === product.id &&
      JSON.stringify(item.customizations || {}) === customizationKey
    );

    if (existingItem) {
      existingItem.quantity += product.quantity || 1;
    } else {
      items.value.push({
        ...product,
        quantity: product.quantity || 1,
        price: effectiveUnitPrice,     // Update price to include addons
        basePrice: product.price,      // Keep original base price for reference
        customizationKey: customizationKey // Store key for easier debugging/comparisons
      });
    }
  };

  const removeItem = (productId) => {
    const index = items.value.findIndex(item => item.id === productId);
    if (index > -1) {
      items.value.splice(index, 1);
    }
  };

  const increaseQuantity = (productId) => {
    const item = items.value.find(item => item.id === productId);
    if (item) {
      item.quantity++;
    }
  };

  const decreaseQuantity = (productId) => {
    const item = items.value.find(item => item.id === productId);
    if (item && item.quantity > 1) {
      item.quantity--;
    } else if (item && item.quantity === 1) {
      removeItem(productId);
    }
  };

  const clearCart = () => {
    items.value = [];
  };

  const toggleCart = () => {
    isOpen.value = !isOpen.value;
  };

  const openCart = () => {
    isOpen.value = true;
  };

  const closeCart = () => {
    isOpen.value = false;
  };

  return {
    items,
    isOpen,
    itemCount,
    total,
    addItem,
    removeItem,
    increaseQuantity,
    decreaseQuantity,
    clearCart,
    toggleCart,
    openCart,
    closeCart
  };
});