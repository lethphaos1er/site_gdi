<script setup>
import stores from '@/data/stores';
import StoreGrid from '@/Components/Backoffice/stores/StoreGrid.vue';
</script>

<template>
    <main class="customer-dashboard">
        <header>
            <h1>Backoffice magasin</h1>

            <p>
                Sélectionnez un magasin à gérer.
            </p>
        </header>

        <StoreGrid :stores="stores" />
    </main>
</template>