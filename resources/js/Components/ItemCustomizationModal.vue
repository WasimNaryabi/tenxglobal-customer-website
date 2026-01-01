<template>
  <!-- Modal Overlay -->
  <Teleport to="body">
    <div v-if="isOpen && item" class="fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center p-4"
      @click.self="closeModal">
      <!-- Modal Container -->
      <div
        class="bg-black border border-gray-800 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">

        <!-- Header -->
        <div class="bg-gray-900 px-4 py-3 flex items-center justify-between border-b border-gray-800 shrink-0">
          <button v-if="canGoBack" @click="goBack"
            class="text-gray-400 hover:text-white p-2 rounded-full hover:bg-gray-800 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
          </button>
          <div v-else class="w-10"></div> <!-- Spacer -->

          <div class="text-center truncate px-2">
            <h2 class="text-lg font-bold text-white">
              {{ isDeal ? 'Customize Deal' : item.name }}
            </h2>
            <p v-if="isDeal" class="text-xs text-orange-400 font-medium">
              Step {{ dealStep + 1 }}/{{ dealTotalSteps }}: {{ activeItem.name }}
            </p>
            <span v-else-if="selectedVariant" class="text-gray-400 text-sm">({{ selectedVariant.name }})</span>
          </div>

          <button @click="closeModal"
            class="text-gray-400 hover:text-white p-2 rounded-full hover:bg-gray-800 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Content Area - Scrollable -->
        <div class="flex-1 overflow-y-auto p-5 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">

          <!-- STEP 1: Ingredients & Base Info (For Active Item) -->
          <div v-if="currentView === 'customize'">
            <p v-if="activeItem.description" class="text-gray-400 text-sm mb-6">{{ activeItem.description }}</p>

            <!-- Variant Selector -->
            <div v-if="activeItem.variants && activeItem.variants.length > 0" class="mb-6">
              <h3 class="text-lg font-bold text-white mb-3">Select Variant</h3>
              <div class="flex flex-wrap gap-2">
                <button v-for="variant in activeItem.variants" :key="variant.id" @click="selectedVariant = variant"
                  class="px-4 py-2 rounded-lg border text-sm font-medium transition flex items-center gap-2" :class="selectedVariant?.id === variant.id
                    ? 'bg-orange-500 text-white border-orange-500 shadow-lg shadow-orange-900/20'
                    : 'bg-gray-900 text-gray-400 border-gray-700 hover:border-gray-500 hover:text-white'">
                  <span>{{ variant.name }}</span>
                  <!-- Only show price if it adds cost in a deal, or full price if regular item -->
                  <span class="opacity-80 text-xs text-orange-200">
                    {{ formatPrice(variant.price) }}
                  </span>
                </button>
              </div>
            </div>

            <!-- Ingredients (if available) -->
            <div v-if="hasIngredients" class="space-y-3 mb-6">
              <div class="mb-4">
                <h3 class="text-lg font-bold text-white">Customize Ingredients</h3>
                <p class="text-sm text-gray-500">Select ingredients to remove</p>
              </div>

              <div class="space-y-2">
                <label v-for="ingredient in currentIngredients" :key="ingredient.id || ingredient.name"
                  class="flex items-center justify-between p-3 bg-gray-900 border border-gray-800 rounded-lg cursor-pointer hover:border-gray-700 transition"
                  :class="{ 'border-red-900 bg-red-900/10': removedIngredients.includes(ingredient.name) }">
                  <div class="flex items-center gap-3">
                    <input type="checkbox" :value="ingredient.name" v-model="removedIngredients"
                      class="w-5 h-5 rounded border-gray-600 bg-gray-800 text-red-500 focus:ring-red-500 focus:ring-offset-gray-900">
                    <span class="text-gray-200"
                      :class="{ 'text-red-400 line-through': removedIngredients.includes(ingredient.name) }">
                      {{ ingredient.name }}
                    </span>
                  </div>
                </label>
              </div>
            </div>

            <!-- Addons Accordion -->
            <div v-if="hasAddonGroups">
              <div class="mb-4">
                <h3 class="text-lg font-bold text-white">Choose Add-ons</h3>
                <p class="text-sm text-gray-500">Customize your {{ activeItem.name }}</p>
              </div>

              <div class="space-y-4">
                <div v-for="group in currentAddonGroups" :key="group.id"
                  class="border border-gray-800 rounded-xl overflow-hidden bg-gray-900/50">
                  <!-- Accordion Header -->
                  <button @click="toggleGroup(group.id)"
                    class="w-full flex items-center justify-between p-4 bg-gray-900 hover:bg-gray-800 transition text-left">
                    <div>
                      <div class="flex items-center gap-2">
                        <h4 class="font-bold text-white">{{ group.name }}</h4>
                      </div>
                      <p class="text-xs text-gray-400 mt-1">
                        <span v-if="group.min_select > 0">Min: {{ group.min_select }}</span>
                        <span v-if="group.max_select > 1" class="ml-2">Max: {{ group.max_select }}</span>
                        <span v-if="group.max_select === 1" class="ml-2">Select 1</span>
                      </p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300"
                      :class="{ 'rotate-180': expandedGroups.includes(group.id) }" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>

                  <!-- Accordion Body -->
                  <div v-show="expandedGroups.includes(group.id)" class="p-3 border-t border-gray-800 bg-black/30">
                    <p v-if="group.description" class="text-xs text-gray-500 mb-3 italic">{{ group.description }}</p>

                    <div class="space-y-2">
                      <div v-for="addon in group.addons" :key="addon.id"
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-800/50 bg-gray-900/50 hover:bg-gray-800 transition"
                        :class="{ 'border-orange-500/30 bg-orange-500/5': getAddonQuantity(group.id, addon.id) > 0 }">
                        <!-- Addon Info -->
                        <div class="flex-1">
                          <p class="text-sm font-medium text-gray-200">{{ addon.name }}</p>
                          <p class="text-xs text-orange-400 font-semibold mt-0.5">
                            {{ parseFloat(addon.price) > 0 ? '+£' + parseFloat(addon.price).toFixed(2) : 'Free' }}
                          </p>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="flex items-center bg-black rounded-lg border border-gray-700 p-0.5">
                          <button @click.stop="updateAddonQuantity(group, addon, -1)"
                            class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-800 rounded-md transition"
                            :disabled="getAddonQuantity(group.id, addon.id) === 0"
                            :class="{ 'opacity-30 cursor-not-allowed': getAddonQuantity(group.id, addon.id) === 0 }">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                          </button>

                          <span class="w-6 text-center text-sm font-bold text-white">
                            {{ getAddonQuantity(group.id, addon.id) }}
                          </span>

                          <button @click.stop="updateAddonQuantity(group, addon, 1)"
                            class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-800 rounded-md transition"
                            :disabled="!canIncreaseAddon(group)"
                            :class="{ 'opacity-30 cursor-not-allowed': !canIncreaseAddon(group) }">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>

                    <!-- Validation Msg for Group -->
                    <!-- Only show if NOT a deal, or if the user specifically wants to see requirements? -->
                    <!-- User asked to make addons optional for deals. So validation text is confusing here for deals. -->
                    <div class="mt-2 text-right" v-if="!isDeal">
                      <span v-if="!isGroupSatisfied(group)" class="text-xs text-orange-400">
                        Select {{ Math.max(0, group.min_select - getGroupTotalSelected(group.id)) }} more
                      </span>
                      <span v-else class="text-xs text-green-500 flex items-center justify-end gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Satisfied
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- STEP 3: Summary -->
          <div v-if="currentView === 'summary'">
            <div class="bg-gray-900 rounded-xl p-5 border border-gray-800">
              <h3 class="text-lg font-bold text-white mb-4 border-b border-gray-800 pb-2">Order Details</h3>

              <div class="space-y-4">
                <!-- Main Deal/Item Info -->
                <div class="flex justify-between items-start">
                  <span class="text-white font-bold text-lg">{{ item.name }}</span>
                  <span class="text-orange-500 font-bold text-lg">£{{ basePrice.toFixed(2) }}</span>
                </div>

                <!-- Deal Items Breakdown -->
                <div v-if="isDeal">
                  <div v-for="(dealItemState, index) in dealState" :key="index"
                    class="bg-black/30 p-3 rounded-lg border border-gray-800/50">
                    <p class="text-white font-bold text-sm mb-1">{{ getItemName(index) }}</p>

                    <!-- Variant -->
                    <p v-if="dealItemState.selectedVariant" class="text-xs text-gray-400 ml-2">
                      Variant: {{ dealItemState.selectedVariant.name }}
                      <span v-if="getVariantDelta(dealItemState.selectedVariant) > 0" class="text-orange-400">
                        (+£{{ getVariantDelta(dealItemState.selectedVariant).toFixed(2) }})
                      </span>
                    </p>

                    <!-- Removed Ingredients -->
                    <div v-if="dealItemState.removedIngredients?.length" class="ml-2 mt-1">
                      <p class="text-xs text-red-400" v-for="ing in dealItemState.removedIngredients" :key="ing">
                        - No {{ ing }}
                      </p>
                    </div>

                    <!-- Addons -->
                    <div v-if="dealItemState.selectedAddons && Object.keys(dealItemState.selectedAddons).length"
                      class="ml-2 mt-1 space-y-1">
                      <div v-for="(groupAddons, groupId) in dealItemState.selectedAddons" :key="groupId">
                        <div v-for="(qty, addonId) in groupAddons" :key="addonId">
                          <div v-if="qty > 0" class="text-xs text-gray-300 flex justify-between">
                            <span>+ {{ qty }}x {{ getAddonName(groupId, addonId, index) }}</span>
                            <span class="text-orange-400" v-if="getAddonPrice(groupId, addonId, index) > 0">
                              £{{ (getAddonPrice(groupId, addonId, index) * qty).toFixed(2) }}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Regular Item Breakdown -->
                <div v-else>
                  <div v-if="selectedVariant" class="text-sm text-gray-400">Variant: {{ selectedVariant.name }}</div>

                  <div v-if="removedIngredients.length > 0" class="text-xs text-red-400 space-y-1">
                    <div v-for="ing in removedIngredients" :key="ing">- No {{ ing }}</div>
                  </div>

                  <div v-for="(groupAddons, groupId) in selectedAddons" :key="groupId">
                    <div v-for="(qty, addonId) in groupAddons" :key="addonId">
                      <div v-if="qty > 0" class="flex justify-between text-sm text-gray-300">
                        <span>{{ qty }}x {{ getAddonNameSingle(groupId, addonId) }}</span>
                        <span>+£{{ (getAddonPriceSingle(groupId, addonId) * qty).toFixed(2) }}</span>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="bg-black/95 p-4 border-t border-gray-800 shrink-0 backdrop-blur-sm mt-4">
            <div class="flex items-center justify-between mb-4">

              <!-- Overall Quantity -->
              <div class="flex items-center gap-3 bg-gray-900 rounded-lg p-1 border border-gray-800">
                <button @click="decreaseQuantity" :disabled="quantity <= 1"
                  class="w-9 h-9 rounded-md flex items-center justify-center text-white hover:bg-gray-800 transition disabled:opacity-30">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
                <span class="text-white font-bold min-w-[1.5rem] text-center">{{ quantity }}</span>
                <button @click="increaseQuantity"
                  class="w-9 h-9 rounded-md flex items-center justify-center text-white hover:bg-gray-800 transition">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </button>
              </div>

              <!-- Total Price -->
              <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest">Total</p>
                <p class="text-2xl font-black text-white">£{{ calculateTotal().toFixed(2) }}</p>
              </div>
            </div>

            <!-- Validation Error -->
            <div v-if="validationError"
              class="mb-3 px-3 py-2 bg-red-900/20 border border-red-900/50 rounded-lg flex items-start gap-2 animate-pulse">
              <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="text-xs text-red-300">{{ validationError }}</p>
            </div>

            <!-- Action Button -->
            <button @click="handleAction"
              class="w-full bg-orange-500 text-white py-3.5 rounded-xl font-bold hover:bg-orange-600 active:scale-[0.99] transition shadow-lg shadow-orange-900/20 text-lg flex items-center justify-center gap-2">
              <span>{{ actionButtonText }}</span>
              <svg v-if="showNextIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </button>
          </div>

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
    type: Array, // Global addon groups for regular items. Deals have their own within items.
    default: () => []
  }
});

const emit = defineEmits(['close', 'add-to-cart']);

// --- STATE ---
const quantity = ref(1);
const validationError = ref('');

// Deal State
const dealStep = ref(0); // Index of the item being customized in the deal
const dealState = ref([]); // Array of objects: { selectedVariant, removedIngredients, selectedAddons } for each item

// Regular Item State (used if !isDeal)
const singleItemState = ref({
  selectedVariant: null,
  removedIngredients: [],
  selectedAddons: {} // { groupId: { addonId: qty } }
});

const expandedGroups = ref([]);

// --- COMPUTED HELPERS ---
const isDeal = computed(() => props.item?.type === 'deal');

const activeItem = computed(() => {
  if (!props.item) return null;
  if (isDeal.value) {
    return props.item.items[dealStep.value];
  }
  return props.item;
});

// "Pointer" to the current state being modified
const currentState = computed(() => {
  if (isDeal.value) {
    return dealState.value[dealStep.value];
  }
  return singleItemState.value;
});

// Proxies for easy access in template (Read/Write)
const selectedVariant = computed({
  get: () => currentState.value?.selectedVariant,
  set: (val) => { if (currentState.value) currentState.value.selectedVariant = val; }
});
const removedIngredients = computed({
  get: () => currentState.value?.removedIngredients || [],
  set: (val) => { if (currentState.value) currentState.value.removedIngredients = val; }
});
const selectedAddons = computed(() => currentState.value?.selectedAddons || {});

// View Logic
const currentView = computed(() => {
  // If deal, we show 'customize' until the last step is done
  // Actually, we can just say: if 'summary' mode is active?
  // Let's use a separate state for summary?
  // No, let's say if we are past the last step?
  if (isSummaryView.value) return 'summary';
  return 'customize';
});
const isSummaryView = ref(false);

const dealTotalSteps = computed(() => props.item?.items?.length || 0);

const currentAddonGroups = computed(() => {
  if (!activeItem.value) return [];
  // For deals, the addon groups are inside the item
  // For regular items, we use the prop passed from parent (or item.addon_groups)
  // The controller response puts 'addon_groups' directly on the item for both regular and deal items now.
  return activeItem.value.addon_groups || props.addonGroups || [];
});

const currentIngredients = computed(() => {
  if (!activeItem.value) return [];
  let ingredients = [];
  if (selectedVariant.value && selectedVariant.value.ingredients) {
    ingredients = selectedVariant.value.ingredients.map(ing => ({
      ...ing, name: ing.product_name || ing.name
    }));
  } else if (activeItem.value.ingredients) {
    ingredients = activeItem.value.ingredients;
  }
  return ingredients;
});

const hasIngredients = computed(() => currentIngredients.value && currentIngredients.value.length > 0);
const hasAddonGroups = computed(() => currentAddonGroups.value && currentAddonGroups.value.length > 0);

const basePrice = computed(() => parseFloat(props.item?.price || 0));

const canGoBack = computed(() => {
  if (isSummaryView.value) return true;
  if (isDeal.value && dealStep.value > 0) return true;
  return false;
});

const showNextIcon = computed(() => !isSummaryView.value);
const actionButtonText = computed(() => {
  if (isSummaryView.value) return 'Add to Basket';
  if (isDeal.value) {
    return dealStep.value < dealTotalSteps.value - 1 ? 'Next Item' : 'Finish Customization';
  }
  return 'Review Order';
});

// --- METHODS ---
const initializeState = () => {
  quantity.value = 1;
  validationError.value = '';
  isSummaryView.value = false;
  dealStep.value = 0;

  if (isDeal.value) {
    dealState.value = props.item.items.map(i => ({
      selectedVariant: i.variants?.length ? i.variants[0] : null,
      removedIngredients: [],
      selectedAddons: {}
    }));
  } else {
    singleItemState.value = {
      selectedVariant: props.item.variants?.length ? props.item.variants[0] : null,
      removedIngredients: [],
      selectedAddons: {}
    };
  }

  expandFirstGroup();
};

const expandFirstGroup = () => {
  const groups = currentAddonGroups.value;
  if (groups && groups.length > 0) {
    expandedGroups.value = [groups[0].id];
  } else {
    expandedGroups.value = [];
  }
};

watch(() => props.isOpen, (val) => {
  if (val && props.item) initializeState();
});

watch(dealStep, () => {
  expandFirstGroup();
});

const goBack = () => {
  if (isSummaryView.value) {
    isSummaryView.value = false;
    return;
  }
  if (isDeal.value && dealStep.value > 0) {
    dealStep.value--;
  }
};

const closeModal = () => emit('close');

const formatPrice = (price) => {
  const p = parseFloat(price);
  return isDeal.value ? (p > 0 ? `+£${p.toFixed(2)}` : '') : `£${p.toFixed(2)}`;
};

const getVariantDelta = (variant) => {
  // Simplified logic: Assume variants in deals are extra cost if price > 0
  // Real logic might need original price diff. 
  return parseFloat(variant.price);
};

// --- VALIDATION & NAVIGATION ---
const validateCurrentStep = () => {
  // For Deals, addons are optional - skip validation
  if (isDeal.value) return true;

  // Check required addon groups
  const groups = currentAddonGroups.value;
  for (const group of groups) {
    if (group.min_select > 0) {
      const total = getGroupTotalSelected(group.id);
      if (total < group.min_select) {
        validationError.value = `You must select at least ${group.min_select} option(s) for ${group.name}`;
        return false;
      }
    }
  }
  return true;
};

const handleAction = () => {
  validationError.value = '';

  if (isSummaryView.value) {
    addToCart();
    return;
  }

  if (!validateCurrentStep()) return;

  if (isDeal.value) {
    if (dealStep.value < dealTotalSteps.value - 1) {
      dealStep.value++;
    } else {
      isSummaryView.value = true;
    }
  } else {
    isSummaryView.value = true;
  }
};

// --- ADDON LOGIC ---
const getAddonQuantity = (groupId, addonId) => {
  return selectedAddons.value[groupId]?.[addonId] || 0;
};

const updateAddonQuantity = (group, addon, delta) => {
  validationError.value = '';
  const currentQty = getAddonQuantity(group.id, addon.id);
  const newQty = currentQty + delta;

  if (newQty < 0) return;
  if (delta > 0 && !canIncreaseAddon(group)) return;

  // We can't mutate computed prop directly for nested obj property assignment easily 
  // without full object replacement if we mapped it, but here selectedAddons 
  // corresponds to currentState.selectedAddons which is reactive object.

  const state = currentState.value;
  if (!state.selectedAddons[group.id]) state.selectedAddons[group.id] = {};

  if (newQty === 0) {
    delete state.selectedAddons[group.id][addon.id];
  } else {
    state.selectedAddons[group.id][addon.id] = newQty;
  }
};

const canIncreaseAddon = (group) => {
  const total = getGroupTotalSelected(group.id);
  return total < group.max_select;
};

const getGroupTotalSelected = (groupId) => {
  const groupSelections = selectedAddons.value[groupId] || {};
  return Object.values(groupSelections).reduce((a, b) => a + b, 0);
};

const isGroupSatisfied = (group) => {
  const total = getGroupTotalSelected(group.id);
  return total >= group.min_select && total <= group.max_select;
};

const toggleGroup = (id) => {
  if (expandedGroups.value.includes(id)) {
    expandedGroups.value = expandedGroups.value.filter(g => g !== id);
  } else {
    expandedGroups.value.push(id);
  }
};

// --- DATA HELPERS ---
const getItemName = (index) => props.item.items[index].name;

const getAddonName = (groupId, addonId, itemIndex) => {
  const item = props.item.items[itemIndex];
  if (!item) return '';
  const group = item.addon_groups.find(g => g.id == groupId);
  const addon = group?.addons.find(a => a.id == addonId);
  return addon?.name || '';
};

const getAddonPrice = (groupId, addonId, itemIndex) => {
  const item = props.item.items[itemIndex];
  const group = item.addon_groups.find(g => g.id == groupId);
  const addon = group?.addons.find(a => a.id == addonId);
  return addon ? parseFloat(addon.price) : 0;
};

const getAddonNameSingle = (groupId, addonId) => {
  const group = currentAddonGroups.value.find(g => g.id == groupId);
  const addon = group?.addons.find(a => a.id == addonId);
  return addon?.name || '';
};

const getAddonPriceSingle = (groupId, addonId) => {
  const group = currentAddonGroups.value.find(g => g.id == groupId);
  const addon = group?.addons.find(a => a.id == addonId);
  return addon ? parseFloat(addon.price) : 0;
};

// --- TOTAL CALC ---
const calculateTotal = () => {
  let total = basePrice.value;

  if (isDeal.value) {
    // Add variant costs + addon costs for each item
    dealState.value.forEach((state, index) => {
      if (state.selectedVariant) {
        total += parseFloat(state.selectedVariant.price);
      }
      // Addons
      Object.entries(state.selectedAddons).forEach(([groupId, addons]) => {
        Object.entries(addons).forEach(([addonId, qty]) => {
          total += getAddonPrice(groupId, addonId, index) * qty;
        });
      });
    });
  } else {
    const state = singleItemState.value;
    if (state.selectedVariant) {
      total = parseFloat(state.selectedVariant.price); // Replace base if regular item variant
    }
    Object.entries(state.selectedAddons).forEach(([groupId, addons]) => {
      Object.entries(addons).forEach(([addonId, qty]) => {
        total += getAddonPriceSingle(groupId, addonId) * qty;
      });
    });
  }

  return total * quantity.value;
};

// --- BUILD & CART ---
const addToCart = () => {
  let customizations = {};

  if (isDeal.value) {
    customizations.type = 'deal';
    customizations.items = dealState.value.map((state, index) => {
      const item = props.item.items[index];
      const itemCustomization = {
        id: item.id,
        name: item.name,
        removedIngredients: state.removedIngredients,
        addons: []
      };
      if (state.selectedVariant) {
        itemCustomization.variant = { id: state.selectedVariant.id, name: state.selectedVariant.name };
      }

      Object.entries(state.selectedAddons).forEach(([groupId, addons]) => {
        Object.entries(addons).forEach(([addonId, qty]) => {
          const name = getAddonName(groupId, addonId, index);
          const price = getAddonPrice(groupId, addonId, index);
          itemCustomization.addons.push({ id: addonId, name, price, quantity: qty });
        });
      });
      return itemCustomization;
    });
  } else {
    const state = singleItemState.value;
    if (state.selectedVariant) {
      customizations.variant = { id: state.selectedVariant.id, name: state.selectedVariant.name };
    }
    customizations.removedIngredients = state.removedIngredients;
    customizations.addons = [];

    Object.entries(state.selectedAddons).forEach(([groupId, addons]) => {
      Object.entries(addons).forEach(([addonId, qty]) => {
        const name = getAddonNameSingle(groupId, addonId);
        const price = getAddonPriceSingle(groupId, addonId);
        customizations.addons.push({ id: addonId, name, price, quantity: qty });
      });
    });
  }

  emit('add-to-cart', {
    id: props.item.id,
    name: props.item.name,
    image: props.item.image,
    price: calculateTotal() / quantity.value,
    quantity: quantity.value,
    totalPrice: calculateTotal(),
    customizations
  });
  closeModal();
};
</script>