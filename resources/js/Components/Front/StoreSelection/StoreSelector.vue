<script setup>
import { ref } from 'vue';
import { useCartStore } from '@/stores/cart';

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

const cart = useCartStore();
const isStoreMenuOpen = ref(false);

function toggleStoreMenu() {
    isStoreMenuOpen.value = !isStoreMenuOpen.value;
}

function isStoreDisabled(store) {
    return Boolean(cart.storeId && cart.storeId !== store.id);
}

function handleStoreSelection(store) {
    if (isStoreDisabled(store)) {
        return;
    }

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

        <p
            v-if="cart.storeName"
            class="store-selector__notice"
        >
            Panier en cours chez {{ cart.storeName }}. Videz le panier pour changer de magasin.
        </p>

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
                    :disabled="isStoreDisabled(store)"
                    @click="handleStoreSelection(store)"
                >
                    {{ store.name }}

                    <span v-if="isStoreDisabled(store)">
                        — panier verrouillé
                    </span>
                </button>
            </li>
        </ul>
    </div>
</template>