<script setup>
import ProductCard from './ProductCard.vue';

defineProps({
    selectedStore: {
        type: Object,
        default: null,
    },
    selectedDepartment: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['select-department']);
</script>

<template>
    <p
        v-if="!selectedStore"
        class="store-empty-state"
    >
        Sélectionnez un magasin pour commencer.
    </p>

    <div
        v-else
        class="store-content"
    >
        <aside class="department-wrapper">
            <p v-if="!selectedStore.departments || !selectedStore.departments.length">
                Aucun rayonnage disponible.
            </p>

            <ul
                v-else
                class="department-selector"
            >
                <li
                    v-for="department in selectedStore.departments"
                    :key="department.id"
                    @click="emit('select-department', department)"
                    :class="{ active: selectedDepartment && selectedDepartment.id === department.id }"
                >
                    {{ department.name }}
                </li>
            </ul>
        </aside>

        <section class="products-container">
            <p v-if="!selectedDepartment">
                Aucun rayonnage sélectionné. Sélectionnez-en un pour consulter les articles.
            </p>

            <p v-else-if="!selectedDepartment.products || !selectedDepartment.products.length">
                Aucun article disponible dans ce rayonnage.
            </p>

            <div
                v-else
                class="products-grid"
            >
                <ProductCard
                    v-for="product in selectedDepartment.products"
                    :key="product.id"
                    :product="product"
                />
            </div>
        </section>
    </div>
</template>