<template>
    <MainLayout>
        <!-- Search Bar & Category Filter - Column on Mobile, Row on Desktop -->
        <section class="py-4 bg-black border-b border-gray-800 sticky top-16 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 md:gap-4">

                    <!-- Category Tabs - Full Width on Mobile, Flex-1 on Desktop -->
                    <div ref="tabsScrollRef" class="flex-1 overflow-x-auto no-scrollbar tabs-scroll order-2 md:order-1">
                        <div class="flex gap-3 whitespace-nowrap w-max">
                            <button v-for="category in categories" :key="category.id"
                                @click="toggleCategoryAndScroll(category.slug)" :class="[
                                    'px-6 py-2.5 rounded-full font-semibold whitespace-nowrap transition-all',
                                    activeCategory === category.slug
                                        ? 'bg-orange-500 text-white shadow-lg'
                                        : 'bg-gray-900 text-gray-300 hover:bg-gray-800'
                                ]">
                                {{ category.name }}
                                <span class="ml-2 text-xs opacity-75">
                                    ({{ getCategoryItems(category.slug).length }})
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Search Bar - Full Width on Mobile, w-80 on Desktop -->
                    <div class="relative w-full md:w-80 flex-shrink-0 order-1 md:order-2">
                        <input v-model="searchQuery" @input="handleSearch" type="text" placeholder="Search dishes..."
                            class="w-full px-4 py-2.5 pl-10 bg-gray-900 border-2 border-gray-800 text-white rounded-full focus:outline-none focus:border-orange-500 transition placeholder-gray-500 text-sm">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <button v-if="searchQuery" @click="clearSearch"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </section>

        <!-- Menu Items by Category -->
        <section class="py-8 bg-black min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Search Results Info -->
                <div v-if="searchQuery" class="mb-8">
                    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                        <p class="text-gray-300">
                            Found <span class="text-orange-500 font-bold">{{ getTotalFilteredItems() }}</span> items
                            <span v-if="searchQuery"> matching "<span class="text-white font-semibold">{{ searchQuery
                            }}</span>"</span>
                        </p>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="text-center py-20">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500"></div>
                    <p class="mt-4 text-gray-400">Loading menu...</p>
                </div>

                <!-- No Results -->
                <div v-else-if="getTotalFilteredItems() === 0" class="text-center py-20">
                    <svg class="w-24 h-24 mx-auto text-gray-700 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-2xl font-bold text-white mb-2">No items found</h3>
                    <p class="text-gray-400">Try searching for something else</p>
                    <button @click="clearSearch"
                        class="mt-4 px-6 py-2 bg-orange-500 text-white rounded-full font-semibold hover:bg-orange-600 transition">
                        Clear Search
                    </button>
                </div>

                <!-- Collapsible Categories with Horizontal Scroll -->
                <div v-else class="space-y-6">
                    <div v-for="category in categories" :key="category.id" :data-category="category.slug"
                        v-show="getCategoryItems(category.slug).length > 0" class="category-section">
                        <!-- Category Header -->
                        <button @click="toggleCategory(category.slug)"
                            class="w-full flex items-center justify-between bg-black border-2 border-gray-800 hover:border-orange-500 rounded-xl p-4 transition-all group mb-4">
                            <div class="flex items-center gap-4">
                                <h2
                                    class="text-lg md:text-xl font-bold text-white group-hover:text-orange-500 transition">
                                    {{ category.name }}
                                </h2>
                                <span class="text-sm font-normal text-gray-400">
                                    ({{ getCategoryItems(category.slug).length }} items)
                                </span>
                            </div>

                            <div :class="[
                                'w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center transition-all',
                                activeCategory === category.slug ? 'rotate-180 bg-orange-500' : ''
                            ]">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>

                        <!-- Items Horizontal Scroll Container -->
                        <div v-show="activeCategory === category.slug">
                            <div class="w-full overflow-x-auto no-scrollbar items-scroll"
                                :ref="el => setItemsScrollRef(category.slug, el)">
                                <div class="flex gap-3 pb-2 whitespace-nowrap w-max">
                                    <div v-for="item in getCategoryItems(category.slug)"
                                        :key="category.slug + '-' + item.id"
                                        class="inline-block w-[240px] sm:w-[260px] md:w-[280px]">
                                        <MenuItemCard :item="item" @open-customization="openCustomizationModal" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- Customization Modal -->
        <ItemCustomizationModal :is-open="isModalOpen" :item="selectedItem" :addon-groups="selectedItemAddonGroups"
            @close="closeCustomizationModal" @add-to-cart="handleAddToCart" />
    </MainLayout>
</template>

<script setup>
import { ref, computed, nextTick, onMounted, onUnmounted } from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import MenuItemCard from "@/Components/MenuItemCard.vue";
import ItemCustomizationModal from "@/Components/ItemCustomizationModal.vue";
import { useCartStore } from "@/Stores/cart";

const props = defineProps({
    menuItems: Array,
    categories: Array,
    addonGroups: Array, // All addon groups from API
});

const cartStore = useCartStore();
const searchQuery = ref("");
const activeCategory = ref(null);
const loading = ref(false);
const isModalOpen = ref(false);
const selectedItem = ref(null);

// Get addon groups for selected item
// Get addon groups for selected item
const selectedItemAddonGroups = computed(() => {
    return selectedItem.value?.addon_groups || [];
});

/** -------------------------
 *  Horizontal scroll helper
 *  ------------------------- */
function attachHScroll(el) {
    if (!el) return () => { };

    let isDown = false;
    let startX = 0;
    let startScrollLeft = 0;

    const onWheel = (e) => {
        // turn wheel vertical into horizontal for this bar
        if (e.deltaY === 0) return;
        e.preventDefault();
        el.scrollLeft += e.deltaY;
    };

    const onDown = (e) => {
        // only left mouse
        if (e.button !== 0) return;
        isDown = true;
        el.classList.add("is-dragging");
        startX = e.pageX;
        startScrollLeft = el.scrollLeft;
    };

    const onMove = (e) => {
        if (!isDown) return;
        e.preventDefault();
        const walk = (e.pageX - startX) * 1.5;
        el.scrollLeft = startScrollLeft - walk;
    };

    const onUp = () => {
        isDown = false;
        el.classList.remove("is-dragging");
    };

    el.addEventListener("wheel", onWheel, { passive: false });
    el.addEventListener("mousedown", onDown);
    el.addEventListener("mousemove", onMove);
    el.addEventListener("mouseleave", onUp);
    window.addEventListener("mouseup", onUp);

    // cleanup
    return () => {
        el.removeEventListener("wheel", onWheel);
        el.removeEventListener("mousedown", onDown);
        el.removeEventListener("mousemove", onMove);
        el.removeEventListener("mouseleave", onUp);
        window.removeEventListener("mouseup", onUp);
    };
}

/** Tabs scroll */
const tabsScrollRef = ref(null);
let cleanupTabs = null;

/** Category items scroll refs */
const itemsScrollRefs = new Map(); // slug => element
const cleanupsItems = new Map();   // slug => cleanupFn

const setItemsScrollRef = (slug, el) => {
    if (!slug) return;

    // Vue can call ref function with null on unmount
    if (!el) {
        const oldCleanup = cleanupsItems.get(slug);
        if (oldCleanup) oldCleanup();
        cleanupsItems.delete(slug);
        itemsScrollRefs.delete(slug);
        return;
    }

    // attach once
    if (itemsScrollRefs.get(slug) === el) return;
    itemsScrollRefs.set(slug, el);

    const oldCleanup = cleanupsItems.get(slug);
    if (oldCleanup) oldCleanup();

    cleanupsItems.set(slug, attachHScroll(el));
};

onMounted(() => {
    if (tabsScrollRef.value) {
        cleanupTabs = attachHScroll(tabsScrollRef.value);
    }
});

onUnmounted(() => {
    if (cleanupTabs) cleanupTabs();
    cleanupsItems.forEach((fn) => fn && fn());
    cleanupsItems.clear();
    itemsScrollRefs.clear();
});

/** Modal & cart */
/** Modal & cart */
const openCustomizationModal = (item) => {
    selectedItem.value = item;

    // Use the addon_groups already attached to the item from the backend
    const itemAddonGroups = item.addon_groups || [];

    // Check if item has multiple ingredients (more than 1)
    const hasIngredients = item.ingredients &&
        Array.isArray(item.ingredients) &&
        item.ingredients.length > 1;

    // Check if item needs customization
    // Show modal if: has addon groups OR has multiple ingredients OR is a DEAL
    const needsCustomization = item.type === 'deal' || itemAddonGroups.length > 0 || hasIngredients;

    if (needsCustomization) {
        // Show customization modal
        isModalOpen.value = true;
    } else {
        // Add directly to cart (no customization needed)
        const simpleItem = {
            id: item.id,
            name: item.name,
            description: item.description,
            image: item.image,
            price: item.price,
            quantity: 1,
            customizations: null,
            totalPrice: item.price
        };
        cartStore.addItem(simpleItem);
        cartStore.openCart();
    }
};

const closeCustomizationModal = () => {
    isModalOpen.value = false;
    selectedItem.value = null;
};

const handleAddToCart = (customizedItem) => {
    cartStore.addItem(customizedItem);
    cartStore.openCart();
};

/** Search & filtering */
const handleSearch = () => {
    if (!searchQuery.value) activeCategory.value = null;
};
const clearSearch = () => {
    searchQuery.value = "";
    activeCategory.value = null;
};

const getCategoryItems = (categorySlug) => {
    let items = props.menuItems.filter((item) => item.categorySlug === categorySlug);

    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        items = items.filter(
            (item) =>
                item.name.toLowerCase().includes(q) ||
                item.description.toLowerCase().includes(q) ||
                item.category.toLowerCase().includes(q)
        );
    }
    return items;
};

const getTotalFilteredItems = () => {
    if (!searchQuery.value) return props.menuItems.length;
    const q = searchQuery.value.toLowerCase();
    return props.menuItems.filter(
        (item) =>
            item.name.toLowerCase().includes(q) ||
            item.description.toLowerCase().includes(q) ||
            item.category.toLowerCase().includes(q)
    ).length;
};

const toggleCategory = (categorySlug) => {
    activeCategory.value = activeCategory.value === categorySlug ? null : categorySlug;
};

const toggleCategoryAndScroll = async (categorySlug) => {
    activeCategory.value = activeCategory.value === categorySlug ? null : categorySlug;

    if (activeCategory.value === categorySlug) {
        await nextTick();
        const element = document.querySelector(`[data-category="${categorySlug}"]`);
        if (element) {
            const headerOffset = 180;
            const elementPosition = element.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({ top: offsetPosition, behavior: "smooth" });
        }
    }
};
</script>

<style scoped>
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

/* Only apply to scroll containers we control */
.tabs-scroll,
.items-scroll {
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
    cursor: grab;
}

.tabs-scroll.is-dragging,
.items-scroll.is-dragging {
    cursor: grabbing;
    user-select: none;
}

.category-section {
    scroll-margin-top: 180px;
}
</style>