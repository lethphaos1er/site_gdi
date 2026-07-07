<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import stores from '@/data/stores';

import StockDepartmentNav from '@/Components/Backoffice/stock/StockDepartmentNav.vue';
import StockProductGrid from '@/Components/Backoffice/stock/StockProductGrid.vue';
import StockSaveButton from '@/Components/Backoffice/stock/StockSaveButton.vue';

const props = defineProps({
    storeId: {
        type: String,
        required: true,
    },
});

const localStores = ref(structuredClone(stores));

const store = computed(() => {
    return localStores.value.find((currentStore) => {
        return currentStore.id === Number(props.storeId);
    });
});

const selectedDepartmentId = ref(store.value?.departments[0]?.id ?? null);

const selectedDepartment = computed(() => {
    if (!store.value) {
        return null;
    }

    return store.value.departments.find((department) => {
        return department.id === selectedDepartmentId.value;
    });
});

function selectDepartment(departmentId) {
    selectedDepartmentId.value = departmentId;
}

function updateQuantity(payload) {
    if (!store.value) {
        return;
    }

    store.value.departments.forEach((department) => {
        department.products.forEach((product) => {
            if (product.id === payload.productId) {
                product.stock = payload.quantity;
            }
        });
    });
}

function saveStock() {
    if (!store.value) {
        return;
    }
}
</script>

<template>
    <main class="store-page">
        <p class="backoffice-page__back">
            <Link
                class="button button--secondary"
                href="/backoffice/stores"
            >
                Retour aux magasins
            </Link>
        </p>

        <template v-if="store">
            <header class="backoffice-page__header">
                <h1>
                    Gestion du stock — {{ store.name }}
                </h1>

                <p v-if="store.city">
                    Magasin de {{ store.city }}
                </p>
            </header>

            <div class="store-content">
                <aside
                    class="department-wrapper"
                    aria-label="Rayons du magasin"
                >
                    <StockDepartmentNav
                        :departments="store.departments"
                        :selected-department-id="selectedDepartmentId"
                        @select-department="selectDepartment"
                    />
                </aside>

                <section
                    class="products-container"
                    aria-label="Produits du rayon"
                >
                    <StockProductGrid
                        v-if="selectedDepartment"
                        :department="selectedDepartment"
                        @update-quantity="updateQuantity"
                    />
                </section>
            </div>

            <StockSaveButton @save-stock="saveStock" />
        </template>

        <template v-else>
            <header class="backoffice-page__header">
                <h1>
                    Magasin introuvable
                </h1>

                <p>
                    Le magasin demandé n’existe pas dans les données actuelles.
                </p>
            </header>
        </template>
    </main>
</template>