<template>
  <!-- Modal Overlay -->
  <Teleport to="body">
    <div 
      v-if="isOpen && item" 
      class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
      @click.self="closeModal"
    >
      <!-- Modal Container -->
      <div class="bg-black border-2 border-gray-800 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
        
        <!-- Header -->
        <div class="bg-gray-900 p-4 flex items-center justify-between border-b border-gray-800">
          <button 
            v-if="currentStep > 0"
            @click="goBack"
            class="text-white p-2"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
          </button>
          <h2 class="text-xl font-bold text-white flex-1 text-center">{{ item.name }}</h2>
          <button @click="closeModal" class="text-white p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Content Area - Scrollable -->
        <div class="flex-1 overflow-y-auto p-6">
          
          <!-- Show description on first step -->
          <p v-if="currentStep === 0 && item.description" class="text-gray-400 text-sm mb-6">{{ item.description }}</p>
          
          <!-- Addon Groups Steps -->
          <div v-if="hasAddonGroups && currentStep < addonGroups.length">
            <div v-for="(group, index) in addonGroups" :key="group.id" v-show="currentStep === index">
              <div class="space-y-3">
                <div class="mb-4">
                  <h3 class="text-lg font-bold text-white">{{ group.name }}</h3>
                  <p v-if="group.description" class="text-sm text-gray-400 mt-1">{{ group.description }}</p>
                  <p class="text-xs text-orange-500 mt-2">
                    Select {{ group.min_select }} - {{ group.max_select }} options
                  </p>
                </div>
                
                <label 
                  v-for="addon in group.addons" 
                  :key="addon.id"
                  class="flex items-center justify-between p-4 bg-gray-900 rounded-lg cursor-pointer hover:bg-gray-800 transition border-2"
                  :class="isAddonSelected(group.id, addon.id) ? 'border-orange-500' : 'border-transparent'"
                >
                  <div class="flex items-center gap-3">
                    <!-- Checkbox if max_select > 1, Radio if max_select = 1 -->
                    <input 
                      v-if="group.max_select > 1"
                      type="checkbox"
                      :checked="isAddonSelected(group.id, addon.id)"
                      @change="toggleAddon(group.id, addon.id, group.min_select, group.max_select)"
                      class="w-5 h-5 rounded border-gray-700 text-orange-500 focus:ring-orange-500"
                    >
                    <input 
                      v-else
                      type="radio"
                      :name="`group-${group.id}`"
                      :checked="isAddonSelected(group.id, addon.id)"
                      @change="selectRadioAddon(group.id, addon.id)"
                      class="w-5 h-5 border-gray-700 text-orange-500 focus:ring-orange-500"
                    >
                    <span class="text-white">{{ addon.name }}</span>
                  </div>
                  <span v-if="parseFloat(addon.price) > 0" class="text-white font-semibold">
                    +£{{ parseFloat(addon.price).toFixed(2) }}
                  </span>
                  <span v-else class="text-gray-400 text-sm">Free</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Ingredients Step (if item has multiple ingredients) -->
          <div v-if="hasIngredients && currentStep === ingredientsStepIndex">
            <div class="space-y-3">
              <div class="mb-4">
                <h3 class="text-lg font-bold text-white">Customize Ingredients</h3>
                <p class="text-sm text-gray-400 mt-1">Select ingredients to remove</p>
              </div>
              
              <label 
                v-for="ingredient in item.ingredients" 
                :key="ingredient.id || ingredient.name"
                class="flex items-center justify-between p-4 bg-gray-900 rounded-lg cursor-pointer hover:bg-gray-800 transition"
              >
                <div class="flex items-center gap-3">
                  <input 
                    type="checkbox"
                    :value="ingredient.name"
                    v-model="removedIngredients"
                    class="w-5 h-5 rounded border-gray-700 text-red-500 focus:ring-red-500"
                  >
                  <span class="text-white">Remove {{ ingredient.name }}</span>
                </div>
              </label>

              <button
                v-if="removedIngredients.length > 0"
                @click="removedIngredients = []"
                class="text-orange-500 text-sm hover:text-orange-400"
              >
                Keep all ingredients
              </button>
            </div>
          </div>

          <!-- Final Step: Summary -->
          <div v-if="currentStep === totalSteps">
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
              <h3 class="text-lg font-bold text-white mb-6">Order Summary</h3>
              <div class="space-y-3">
                
                <!-- Base Item -->
                <div class="flex justify-between text-white text-lg">
                  <span>{{ item.name }}</span>
                  <span>£{{ parseFloat(item.price).toFixed(2) }}</span>
                </div>
                
                <!-- Selected Addons by Group -->
                <div v-for="(group, groupId) in selectedAddonsByGroup" :key="groupId" v-if="group.length > 0" class="border-t border-gray-800 pt-3">
                  <div class="text-gray-400 text-sm mb-2">{{ getGroupName(groupId) }}</div>
                  <div v-for="addonId in group" :key="addonId" class="flex justify-between text-gray-300 pl-4 text-sm">
                    <span>{{ getAddonName(groupId, addonId) }}</span>
                    <span v-if="getAddonPrice(groupId, addonId) > 0">
                      +£{{ getAddonPrice(groupId, addonId).toFixed(2) }}
                    </span>
                    <span v-else class="text-gray-500">Free</span>
                  </div>
                </div>

                <!-- Removed Ingredients -->
                <div v-if="removedIngredients.length > 0" class="border-t border-gray-800 pt-3">
                  <div class="text-gray-400 text-sm mb-1">Removed:</div>
                  <div class="text-red-400 text-sm pl-4">
                    {{ removedIngredients.join(', ') }}
                  </div>
                </div>

                <!-- Quantity Line -->
                <div class="border-t border-gray-800 pt-3 mt-3">
                  <div class="flex justify-between text-gray-400">
                    <span>Quantity</span>
                    <span>{{ quantity }}</span>
                  </div>
                </div>

              </div>
            </div>

            <!-- Nutrition & Allergies Info -->
            <div v-if="item.nutrition || item.allergies" class="mt-4 space-y-3">
              
              <!-- Nutrition -->
              <div v-if="item.nutrition" class="bg-gray-900 rounded-lg p-4 border border-gray-800">
                <h4 class="text-sm font-semibold text-white mb-2">Nutrition Information</h4>
                <div class="grid grid-cols-2 gap-2 text-xs text-gray-400">
                  <div v-for="(value, key) in item.nutrition" :key="key">
                    <span class="capitalize">{{ key }}:</span> <span class="text-white">{{ value }}</span>
                  </div>
                </div>
              </div>

              <!-- Allergies -->
              <div v-if="item.allergies && item.allergies.length > 0" class="bg-red-900 bg-opacity-20 border border-red-500 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-red-400 mb-2">⚠️ Allergen Information</h4>
                <p class="text-xs text-red-300">Contains: {{ item.allergies.join(', ') }}</p>
              </div>

            </div>
          </div>

        </div>

        <!-- Footer -->
        <div class="bg-black p-4 border-t border-gray-800">
          <div class="flex items-center justify-between mb-4">
            
            <!-- Quantity Controls -->
            <div class="flex items-center gap-4 bg-gray-900 rounded-full p-2">
              <button 
                @click="decreaseQuantity"
                :disabled="quantity <= 1"
                class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
              </button>
              <span class="text-white font-bold text-lg min-w-[2rem] text-center">{{ quantity }}</span>
              <button 
                @click="increaseQuantity"
                class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white hover:bg-gray-700"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
              </button>
            </div>

            <!-- Total Price -->
            <div class="text-right">
              <p class="text-xs text-gray-400 uppercase">Total</p>
              <p class="text-2xl font-bold text-orange-500">£{{ calculateTotal().toFixed(2) }}</p>
            </div>
          </div>

          <!-- Validation Error -->
          <p v-if="validationError" class="text-red-400 text-sm text-center mb-3">
            {{ validationError }}
          </p>

          <!-- Next/Add Button -->
          <button 
            @click="handleNext"
            :disabled="!canProceed"
            class="w-full bg-orange-500 text-white py-4 rounded-lg font-bold hover:bg-orange-600 transition text-lg disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ currentStep === totalSteps ? 'Add to Basket' : 'Next' }}
          </button>
        </div>

      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  isOpen: Boolean,
  item: {
    type: Object,
    default: null
  },
  addonGroups: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'add-to-cart']);

// Current step in customization flow
const currentStep = ref(0);

// Quantity
const quantity = ref(1);

// Selected addons - structure: { groupId: [addonId1, addonId2, ...] }
const selectedAddonsByGroup = ref({});

// Removed ingredients
const removedIngredients = ref([]);

// Validation error message
const validationError = ref('');

// Check if item has addon groups
const hasAddonGroups = computed(() => {
  return props.addonGroups && props.addonGroups.length > 0;
});

// Check if item has multiple ingredients
const hasIngredients = computed(() => {
  return props.item?.ingredients && 
         Array.isArray(props.item.ingredients) && 
         props.item.ingredients.length > 1;
});

// Calculate ingredient step index
const ingredientsStepIndex = computed(() => {
  return hasAddonGroups.value ? props.addonGroups.length : 0;
});

// Calculate total steps (addon groups + ingredients + summary)
const totalSteps = computed(() => {
  let steps = 0;
  if (hasAddonGroups.value) steps += props.addonGroups.length;
  if (hasIngredients.value) steps += 1;
  return steps; // Summary is always at totalSteps
});

// Check if addon is selected
const isAddonSelected = (groupId, addonId) => {
  return selectedAddonsByGroup.value[groupId]?.includes(addonId) || false;
};

// Toggle addon selection (for checkboxes)
const toggleAddon = (groupId, addonId, minSelect, maxSelect) => {
  if (!selectedAddonsByGroup.value[groupId]) {
    selectedAddonsByGroup.value[groupId] = [];
  }

  const index = selectedAddonsByGroup.value[groupId].indexOf(addonId);
  
  if (index > -1) {
    selectedAddonsByGroup.value[groupId].splice(index, 1);
  } else {
    if (selectedAddonsByGroup.value[groupId].length < maxSelect) {
      selectedAddonsByGroup.value[groupId].push(addonId);
    }
  }
  
  validationError.value = '';
};

// Select radio addon (for radio buttons)
const selectRadioAddon = (groupId, addonId) => {
  selectedAddonsByGroup.value[groupId] = [addonId];
  validationError.value = '';
};

// Get group details
const getGroupName = (groupId) => {
  const group = props.addonGroups?.find(g => g.id == groupId);
  return group ? group.name : '';
};

// Get addon details
const getAddonName = (groupId, addonId) => {
  const group = props.addonGroups?.find(g => g.id == groupId);
  const addon = group?.addons?.find(a => a.id == addonId);
  return addon ? addon.name : '';
};

const getAddonPrice = (groupId, addonId) => {
  const group = props.addonGroups?.find(g => g.id == groupId);
  const addon = group?.addons?.find(a => a.id == addonId);
  return addon ? parseFloat(addon.price) : 0;
};

// Validate current step selections
const canProceed = computed(() => {
  // If on summary step
  if (currentStep.value >= totalSteps.value) {
    return true;
  }

  // If on addon group step
  if (currentStep.value < (hasAddonGroups.value ? props.addonGroups.length : 0)) {
    const currentGroup = props.addonGroups[currentStep.value];
    if (!currentGroup) return true;

    const selectedCount = selectedAddonsByGroup.value[currentGroup.id]?.length || 0;
    return selectedCount >= currentGroup.min_select && selectedCount <= currentGroup.max_select;
  }

  // If on ingredients step - always allow proceeding
  return true;
});

// Navigate steps
const goBack = () => {
  if (currentStep.value > 0) {
    currentStep.value--;
    validationError.value = '';
  }
};

const handleNext = () => {
  if (currentStep.value < totalSteps.value) {
    // Validate addon group step
    if (currentStep.value < (hasAddonGroups.value ? props.addonGroups.length : 0)) {
      const currentGroup = props.addonGroups[currentStep.value];
      const selectedCount = selectedAddonsByGroup.value[currentGroup.id]?.length || 0;
      
      if (selectedCount < currentGroup.min_select) {
        validationError.value = `Please select at least ${currentGroup.min_select} option(s)`;
        return;
      }
      
      if (selectedCount > currentGroup.max_select) {
        validationError.value = `Please select maximum ${currentGroup.max_select} option(s)`;
        return;
      }
    }
    
    currentStep.value++;
    validationError.value = '';
  } else {
    // Final step - add to cart
    addToCart();
  }
};

// Quantity controls
const increaseQuantity = () => {
  quantity.value++;
};

const decreaseQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--;
  }
};

// Calculate total price
const calculateTotal = () => {
  let total = parseFloat(props.item?.price || 0);
  
  // Add all selected addons prices
  Object.keys(selectedAddonsByGroup.value).forEach(groupId => {
    selectedAddonsByGroup.value[groupId].forEach(addonId => {
      total += getAddonPrice(groupId, addonId);
    });
  });
  
  return total * quantity.value;
};

// Build customizations object
const buildCustomizations = () => {
  const customizations = {};
  
  // Add selected addons
  props.addonGroups?.forEach(group => {
    const selectedAddons = selectedAddonsByGroup.value[group.id] || [];
    if (selectedAddons.length > 0) {
      customizations[group.id] = selectedAddons.map(addonId => {
        const addon = group.addons.find(a => a.id == addonId);
        return {
          id: addon.id,
          name: addon.name,
          price: parseFloat(addon.price)
        };
      });
    }
  });
  
  // Add removed ingredients
  if (removedIngredients.value.length > 0) {
    customizations.removedIngredients = removedIngredients.value;
  }
  
  return customizations;
};

// Add to cart
const addToCart = () => {
  const customizedItem = {
    id: props.item.id,
    name: props.item.name,
    description: props.item.description,
    image: props.item.image,
    price: parseFloat(props.item.price),
    quantity: quantity.value,
    customizations: buildCustomizations(),
    totalPrice: calculateTotal()
  };
  
  emit('add-to-cart', customizedItem);
  closeModal();
};

// Close modal
const closeModal = () => {
  currentStep.value = 0;
  quantity.value = 1;
  selectedAddonsByGroup.value = {};
  removedIngredients.value = [];
  validationError.value = '';
  
  emit('close');
};

// Watch for modal open/close
watch(() => props.isOpen, (newValue) => {
  if (newValue && props.item) {
    // Reset when opened
    currentStep.value = 0;
    quantity.value = 1;
    selectedAddonsByGroup.value = {};
    removedIngredients.value = [];
    validationError.value = '';
  } else if (!newValue) {
    closeModal();
  }
});
</script>