<script setup>
import StoreCard from './StoreCard.vue';

defineProps({
    stores: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <section
        aria-labelledby="backoffice-store-grid-title"
    >
        <h2 id="backoffice-store-grid-title">
            Magasins disponibles
        </h2>

        <div class="customer-dashboard__nav">
            <StoreCard
                v-for="store in stores"
                :key="store.id"
                :store="store"
            />
        </div>
    </section>
</template>