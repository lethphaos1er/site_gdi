<script setup>
import { ref } from 'vue';
import CartDrawer from '@/Components/Front/cart/CartDrawer.vue';
import { useCartStore } from '@/stores/cart';

import {
    StoreHeader,
    StoreSelector,
    StoreContent,
    OrderDateSelector,
} from '@/Components/Front/StoreSelection/StoreSelection.js';

defineProps({
    stores: {
        type: Array,
        required: true,
    },
});

function getTodayDate() {
    const today = new Date();
    const timezoneOffset = today.getTimezoneOffset() * 60000;

    return new Date(today.getTime() - timezoneOffset).toISOString().slice(0, 10);
}

const cart = useCartStore();
const todayDate = getTodayDate();
const selectedStore = ref(null);
const selectedCategory = ref(null);
const selectedSubcategory = ref(null);
const selectedOrderDate = ref(cart.orderDate ?? todayDate);

function selectStore(store) {
    selectedStore.value = store;
    selectedCategory.value = null;
    selectedSubcategory.value = null;
}

function selectCategory(category) {
    selectedCategory.value = category;
    selectedSubcategory.value = null;
}

function selectSubcategory(subcategory) {
    selectedSubcategory.value = subcategory;
}
</script>

<template>
    <main class="page-container store-page">
        <StoreHeader />

        <StoreSelector
            :stores="stores"
            :selected-store="selectedStore"
            @select-store="selectStore"
        />

        <OrderDateSelector
            v-model="selectedOrderDate"
            :min-date="todayDate"
            :is-locked="cart.hasItems"
            :locked-store-name="cart.storeName"
        />

        <StoreContent
            :selected-store="selectedStore"
            :selected-category="selectedCategory"
            :selected-subcategory="selectedSubcategory"
            :order-date="selectedOrderDate"
            @select-category="selectCategory"
            @select-subcategory="selectSubcategory"
        />

        <CartDrawer />
    </main>
</template>