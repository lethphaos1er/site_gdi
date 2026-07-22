<script setup>
import { ref } from 'vue';
import StoreGrid from '@/Components/Backoffice/stores/StoreGrid.vue';
import StoreCreateForm from '@/Components/Backoffice/stores/StoreCreateForm.vue';

defineProps({
    stores: {
        type: Array,
        required: true,
    },
});

const isCreateFormOpen = ref(false);

function openCreateForm() {
    isCreateFormOpen.value = true;
}

function closeCreateForm() {
    isCreateFormOpen.value = false;
}
</script>

<template>
    <main class="customer-dashboard">
        <header>
            <h1>Backoffice magasin</h1>

            <p>
                Sélectionnez un magasin à gérer ou ajoutez un nouveau point de vente.
            </p>

            <button type="button" class="button" @click="openCreateForm">
                Ajouter un point de vente
            </button>
        </header>

        <StoreGrid :stores="stores" />

        <section v-if="isCreateFormOpen" class="store-create">
            <header class="store-create__header">
                <h2>Ajouter un point de vente</h2>

                <button type="button" class="button" @click="closeCreateForm">
                    Fermer
                </button>
            </header>

            <StoreCreateForm @close="closeCreateForm" />

        </section>
    </main>
</template>