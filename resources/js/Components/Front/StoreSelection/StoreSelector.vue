<script setup>
import { ref } from 'vue';

const props = defineProps({
    stores: {
        type: Array,
        required: true,
    },
    selectedStore: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['selectStore']);

const isStoreMenuOpen = ref(false);

function toggleStoreMenu() {
    isStoreMenuOpen.value = !isStoreMenuOpen.value;
}

function handleStoreSelection(store) {
    emit('selectStore', store);
    isStoreMenuOpen.value = false;
}
</script>

<template>
    <div class="store-selector">
        <button
            type="button"
            class="store-selector__toggle"
            :aria-expanded="isStoreMenuOpen"
            aria-controls="store-selector-menu"
            @click="toggleStoreMenu"
        >
            <span aria-hidden="true">☰</span>
            <span>{{ selectedStore?.name ?? 'Sélectionnez un magasin' }}</span>
        </button>

        <ul
            v-show="isStoreMenuOpen"
            id="store-selector-menu"
            class="store-selector__list"
        >
            <li
                v-for="store in stores"
                :key="store.id"
                class="store-selector__item"
            >
                <button
                    type="button"
                    class="store-selector__button"
                    :class="{ active: selectedStore && selectedStore.id === store.id }"
                    @click="handleStoreSelection(store)"
                >
                    {{ store.name }}
                </button>
            </li>
        </ul>
    </div>
</template>