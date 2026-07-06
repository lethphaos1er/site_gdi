<script setup>
const props = defineProps({
    productId: {
        type: Number,
        required: true,
    },
    quantity: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(['update-quantity']);

function decreaseQuantity() {
    if (props.quantity <= 0) {
        return;
    }

    emit('update-quantity', {
        productId: props.productId,
        quantity: props.quantity - 1,
    });
}

function increaseQuantity() {
    emit('update-quantity', {
        productId: props.productId,
        quantity: props.quantity + 1,
    });
}

function updateQuantity(event) {
    const value = Number(event.target.value);

    emit('update-quantity', {
        productId: props.productId,
        quantity: Math.max(0, value),
    });
}
</script>

<template>
    <div class="product-quantity">
        <div class="product-quantity__controls">
            <button
                type="button"
                class="product-quantity__button"
                aria-label="Diminuer la quantité"
                @click="decreaseQuantity"
            >
                -
            </button>

            <input
                class="product-quantity__input"
                type="number"
                min="0"
                :value="quantity"
                aria-label="Quantité en stock"
                @input="updateQuantity"
            >

            <button
                type="button"
                class="product-quantity__button"
                aria-label="Augmenter la quantité"
                @click="increaseQuantity"
            >
                +
            </button>
        </div>
    </div>
</template>