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
const selectedDepartment = ref(null);

function selectStore(store) {
    selectedStore.value = store;
    selectedDepartment.value = null;
}

function selectDepartment(department) {
    selectedDepartment.value = department;
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
            :selected-department="selectedDepartment"
            @select-department="selectDepartment"
        />

        <CartDrawer />
    </main>
</template>