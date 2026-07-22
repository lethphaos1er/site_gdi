<script setup>
import { computed, ref } from 'vue';
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
const selectedType = ref(null);

const storeTypes = [
    {
        value: 'bakery',
        label: 'Boulangeries',
    },
    {
        value: 'italian',
        label: 'Italien',
    },
    {
        value: 'sport',
        label: 'Sport',
    },
];

const bakeryStores = computed(() => {
    return props.stores.filter((store) => store.type === 'bakery');
});

function toggleStoreMenu() {
    isStoreMenuOpen.value = !isStoreMenuOpen.value;
}

function isStoreDisabled(store) {
    return Boolean(cart.storeId && cart.storeId !== store.id);
}

function isTypeDisabled(type) {
    if (!cart.storeId) {
        return false;
    }

    const cartStore = props.stores.find((store) => store.id === cart.storeId);

    return cartStore?.type !== type;
}

function handleTypeSelection(type) {
    if (isTypeDisabled(type)) {
        return;
    }

    selectedType.value = type;

    if (type === 'bakery') {
        return;
    }

    const store = props.stores.find((item) => item.type === type);

    if (store) {
        handleStoreSelection(store);
    }
}

function handleStoreSelection(store) {
    if (isStoreDisabled(store)) {
        return;
    }

    selectedType.value = store.type;

    emit('selectStore', store);

    isStoreMenuOpen.value = false;
}
</script>

<template>
    <div class="store-selector">
        <button type="button" class="store-selector__toggle" :aria-expanded="isStoreMenuOpen"
            aria-controls="store-selector-menu" @click="toggleStoreMenu">
            <span aria-hidden="true">☰</span>
            <span>{{ selectedStore?.name ?? 'Sélectionnez un magasin' }}</span>
        </button>

        <p v-if="cart.storeName" class="store-selector__notice">
            Panier en cours chez {{ cart.storeName }}. Videz le panier pour changer de magasin.
        </p>

        <div id="store-selector-menu" class="store-selector__menu"
            :class="{ 'store-selector__menu--open': isStoreMenuOpen }">
            <ul class="store-selector__list">
                <li v-for="type in storeTypes" :key="type.value" class="store-selector__item">
                    <button type="button" class="store-selector__button" :class="{
                        active: selectedType === type.value,
                    }" :disabled="isTypeDisabled(type.value)" @click="handleTypeSelection(type.value)">
                        {{ type.label }}

                        <span v-if="isTypeDisabled(type.value)">
                            — panier verrouillé
                        </span>
                    </button>
                </li>
            </ul>

            <ul v-if="selectedType === 'bakery'" class="store-selector__sublist">
                <li v-for="store in bakeryStores" :key="store.id" class="store-selector__item">
                    <button type="button" class="store-selector__button store-selector__button--store" :class="{
                        active: selectedStore && selectedStore.id === store.id,
                    }" :disabled="isStoreDisabled(store)" @click="handleStoreSelection(store)">
                        {{ store.city ?? store.name }}

                        <span v-if="isStoreDisabled(store)">
                            — panier verrouillé
                        </span>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>