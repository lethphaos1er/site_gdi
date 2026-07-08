<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    stock: {
        type: Number,
        required: true,
    },
    modelValue: {
        type: Number,
        default: null,
    },
    showActionButton: {
        type: Boolean,
        default: true,
    },
    isActionDisabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits([
    'add-product',
    'update:modelValue',
]);

const quantity = ref(props.modelValue ?? (props.stock > 0 ? 1 : 0));

watch(
    () => props.modelValue,
    (value) => {
        if (value !== null) {
            quantity.value = value;
        }
    }
);

function updateQuantity(value) {
    quantity.value = value;
    normalizeQuantity();
    emit('update:modelValue', quantity.value);
}

function decreaseQuantity() {
    if (quantity.value > 1) {
        updateQuantity(quantity.value - 1);
    }
}

function increaseQuantity() {
    if (quantity.value < props.stock) {
        updateQuantity(quantity.value + 1);
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
    if (props.isActionDisabled) {
        return;
    }

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
                @change="updateQuantity(quantity)"
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
            v-if="showActionButton"
            type="button"
            class="product-quantity__add"
            :disabled="isActionDisabled || stock <= 0 || quantity < 1 || quantity > stock"
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