<script setup>
import { ref } from 'vue';
import stores from '@/data/stores';
import CartDrawer from '@/Components/Front/cart/CartDrawer.vue';

import {
    StoreHeader,
    StoreSelector,
    StoreContent,
} from '@/Components/Front/StoreSelection/StoreSelection.js';

const selectedStore = ref(null);
const selectedCategory = ref(null);
const selectedSubcategory = ref(null);

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
    <main class="store-page">
        <StoreHeader />

        <StoreSelector
            :stores="stores"
            :selected-store="selectedStore"
            @select-store="selectStore"
        />

        <StoreContent
            :selected-store="selectedStore"
            :selected-category="selectedCategory"
            :selected-subcategory="selectedSubcategory"
            @select-category="selectCategory"
            @select-subcategory="selectSubcategory"
        />

        <CartDrawer />
    </main>
</template>