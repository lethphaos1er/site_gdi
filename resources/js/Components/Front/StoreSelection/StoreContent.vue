<script setup>
import { computed, ref, watch } from 'vue';
import { ProductCard } from './StoreSelection.js';

const props = defineProps({
    selectedStore: {
        type: Object,
        default: null,
    },
    selectedCategory: {
        type: Object,
        default: null,
    },
    selectedSubcategory: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits([
    'select-category',
    'select-subcategory',
]);

const isCategoryMenuOpen = ref(false);

const displayedProducts = computed(() => {
    if (props.selectedSubcategory) {
        return props.selectedSubcategory.products ?? [];
    }

    if (!props.selectedCategory || !props.selectedCategory.subcategories) {
        return [];
    }

    return props.selectedCategory.subcategories.flatMap((subcategory) => {
        return subcategory.products ?? [];
    });
});

function toggleCategoryMenu() {
    isCategoryMenuOpen.value = !isCategoryMenuOpen.value;
}

function handleCategorySelection(category) {
    if (props.selectedCategory && props.selectedCategory.id === category.id) {
        emit('select-category', null);
        emit('select-subcategory', null);
        return;
    }

    emit('select-category', category);
    emit('select-subcategory', null);
}

function handleSubcategorySelection(subcategory) {
    emit('select-subcategory', subcategory);
    isCategoryMenuOpen.value = false;
}

watch(
    () => props.selectedStore,
    () => {
        isCategoryMenuOpen.value = false;
    }
);
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
            <div class="department-menu">
                <p
                    id="category-selector-title"
                    class="department-menu__title"
                >
                    Catégories
                </p>

                <p v-if="!selectedStore.categories || !selectedStore.categories.length">
                    Aucune catégorie disponible.
                </p>

                <template v-else>
                    <button
                        type="button"
                        class="department-menu__toggle"
                        :aria-expanded="isCategoryMenuOpen"
                        aria-controls="category-selector-menu"
                        @click="toggleCategoryMenu"
                    >
                        <span aria-hidden="true">☰</span>
                        <span>{{ selectedCategory?.name ?? 'Sélectionnez une catégorie' }}</span>
                    </button>

                    <ul
                        v-show="isCategoryMenuOpen"
                        id="category-selector-menu"
                        class="department-selector"
                        aria-labelledby="category-selector-title"
                    >
                        <li
                            v-for="category in selectedStore.categories"
                            :key="category.id"
                            class="department-selector__item"
                        >
                            <button
                                type="button"
                                class="department-selector__button"
                                :class="{ active: selectedCategory && selectedCategory.id === category.id }"
                                @click="handleCategorySelection(category)"
                            >
                                {{ category.name }}
                            </button>

                            <ul
                                v-if="
                                    selectedCategory
                                    && selectedCategory.id === category.id
                                    && category.subcategories
                                    && category.subcategories.length
                                "
                                class="subcategory-selector"
                            >
                                <li
                                    v-for="subcategory in category.subcategories"
                                    :key="subcategory.id"
                                >
                                    <button
                                        type="button"
                                        class="subcategory-selector__button"
                                        :class="{ active: selectedSubcategory && selectedSubcategory.id === subcategory.id }"
                                        @click="handleSubcategorySelection(subcategory)"
                                    >
                                        {{ subcategory.name }}
                                    </button>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </template>
            </div>
        </aside>

        <section class="products-container">
            <p v-if="!selectedCategory">
                Aucune catégorie sélectionnée. Sélectionnez-en une pour consulter les articles.
            </p>

            <p v-else-if="!displayedProducts.length">
                Aucun article disponible pour cette sélection.
            </p>

            <div
                v-else
                class="products-grid"
            >
                <ProductCard
                    v-for="product in displayedProducts"
                    :key="product.id"
                    :product="product"
                    :store="selectedStore"
                />
            </div>
        </section>
    </div>
</template>