<script setup>
import { computed, ref } from 'vue';
import StoreGrid from '@/Components/Backoffice/stores/StoreGrid.vue';
import StoreCreateForm from '@/Components/Backoffice/stores/StoreCreateForm.vue';
import SortSelect from '@/Components/Common/SortSelect.vue';
import StoreHeader from '@/Components/Front/StoreSelection/StoreHeader.vue';

const props = defineProps({
    stores: {
        type: Array,
        required: true,
    },
});

const isCreateFormOpen = ref(false);

const sortBy = ref('name-asc');

const sortOptions = [
    {
        value: 'name-asc',
        label: 'Nom A → Z',
    },
    {
        value: 'name-desc',
        label: 'Nom Z → A',
    },
    {
        value: 'city-asc',
        label: 'Ville A → Z',
    },
    {
        value: 'city-desc',
        label: 'Ville Z → A',
    },
];

const sortedStores = computed(() => {
    return [...props.stores].sort((a, b) => {
        switch (sortBy.value) {
            case 'name-desc':
                return b.name.localeCompare(a.name);

            case 'city-asc':
                return (a.city ?? '').localeCompare(b.city ?? '');

            case 'city-desc':
                return (b.city ?? '').localeCompare(a.city ?? '');

            case 'name-asc':
            default:
                return a.name.localeCompare(b.name);
        }
    });
});

function openCreateForm() {
    isCreateFormOpen.value = true;
}

function closeCreateForm() {
    isCreateFormOpen.value = false;
}
</script>

<template>
    <StoreHeader />

    <main class="page-container customer-dashboard">
        <section aria-labelledby="backoffice-title">
            <h1 id="backoffice-title">
                Backoffice magasin
            </h1>

            <p>
                Sélectionnez un magasin à gérer ou ajoutez un nouveau point de vente.
            </p>

            <button
                type="button"
                class="button"
                @click="openCreateForm"
            >
                Ajouter un point de vente
            </button>
        </section>

        <section v-if="isCreateFormOpen" class="store-create">
            <header class="store-create__header">
                <h2>Ajouter un point de vente</h2>

                <button
                    type="button"
                    class="button"
                    @click="closeCreateForm"
                >
                    Fermer
                </button>
            </header>

            <StoreCreateForm @close="closeCreateForm" />
        </section>

        <SortSelect
            v-model="sortBy"
            :options="sortOptions"
        />

        <StoreGrid :stores="sortedStores" />
    </main>
</template>