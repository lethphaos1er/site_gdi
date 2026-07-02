<script setup>
import { ref } from 'vue';

const props = defineProps({
    stock: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(['add-product']);

const quantity = ref(props.stock > 0 ? 1 : 0);

function decreaseQuantity() {
    if (quantity.value > 1) {
        quantity.value--;
    }
}

function increaseQuantity() {
    if (quantity.value < props.stock) {
        quantity.value++;
    }
}

function normalizeQuantity() {
    if (!quantity.value || quantity.value < 1) {
        quantity.value = props.stock > 0 ? 1 : 0;
        return;
    }

    if (quantity.value > props.stock) {
        quantity.value = props.stock;
    }
}

function addProduct() {
    normalizeQuantity();

    if (props.stock > 0 && quantity.value >= 1 && quantity.value <= props.stock) {
        emit('add-product', quantity.value);
    }
}
</script>

<template>
    <div class="product-quantity">
        <div class="product-quantity__controls">
            <button
                type="button"
                class="product-quantity__button"
                :disabled="quantity <= 1"
                @click="decreaseQuantity"
            >
                -
            </button>

            <input
                v-model.number="quantity"
                class="product-quantity__input"
                type="number"
                min="1"
                :max="stock"
                :disabled="stock <= 0"
                @change="normalizeQuantity"
            >

            <button
                type="button"
                class="product-quantity__button"
                :disabled="quantity >= stock"
                @click="increaseQuantity"
            >
                +
            </button>
        </div>

        <button
            type="button"
            class="product-quantity__add"
            :disabled="stock <= 0 || quantity < 1 || quantity > stock"
            @click="addProduct"
        >
            <span
                class="product-quantity__icon"
                aria-hidden="true"
            >
                🛒
            </span>

            <span>Ajouter au panier</span>
        </button>
    </div>
</template>