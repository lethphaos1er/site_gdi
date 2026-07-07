<script setup>
import { ref, watch } from 'vue';
import { ProductCard } from './StoreSelection.js';

const props = defineProps({
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

const isDepartmentMenuOpen = ref(false);

function toggleDepartmentMenu() {
    isDepartmentMenuOpen.value = !isDepartmentMenuOpen.value;
}

function handleDepartmentSelection(department) {
    emit('select-department', department);
    isDepartmentMenuOpen.value = false;
}

watch(
    () => props.selectedStore,
    () => {
        isDepartmentMenuOpen.value = false;
    }
);
</script>

<template>
    <p v-if="!selectedStore" class="store-empty-state">
        Sélectionnez un magasin pour commencer.
    </p>

    <div v-else class="store-content">
        <aside class="department-wrapper">
            <p v-if="!selectedStore.departments || !selectedStore.departments.length">
                Aucun rayonnage disponible.
            </p>

            <div v-else class="department-menu">
                <button type="button" class="department-menu__toggle" :aria-expanded="isDepartmentMenuOpen"
                    aria-controls="department-selector-menu" @click="toggleDepartmentMenu">
                    <span aria-hidden="true">☰</span>
                    <span>{{ selectedDepartment?.name ?? 'Sélectionnez un rayonnage' }}</span>
                </button>

                <ul v-show="isDepartmentMenuOpen" id="department-selector-menu" class="department-selector">
                    <li v-for="department in selectedStore.departments" :key="department.id">
                        <button type="button" class="department-selector__button"
                            :class="{ active: selectedDepartment && selectedDepartment.id === department.id }"
                            @click="handleDepartmentSelection(department)">
                            {{ department.name }}
                        </button>
                    </li>
                </ul>
            </div>
        </aside>

        <section class="products-container">
            <p v-if="!selectedDepartment">
                Aucun rayonnage sélectionné. Sélectionnez-en un pour consulter les articles.
            </p>

            <p v-else-if="!selectedDepartment.products || !selectedDepartment.products.length">
                Aucun article disponible dans ce rayonnage.
            </p>

            <div v-else class="products-grid">
                <ProductCard v-for="product in selectedDepartment.products" 
                    :key="product.id" 
                    :product="product"
                    :store="selectedStore" />
            </div>
        </section>
    </div>
</template>