<script setup>
import StockQuantityControl from './StockQuantityControl.vue';

defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits([
    'update-quantity',
]);

function updateQuantity(payload) {
    emit('update-quantity', payload);
}
</script>

<template>
    <article
        v-if="product"
        class="product-card"
    >
        <img
            v-if="product.image"
            class="product-card__image"
            :src="product.image"
            :alt="`Photo du produit ${product.name}`"
        >

        <div class="product-card__content">
            <header class="product-card__header">
                <h3 class="product-card__title">
                    {{ product.name }}
                </h3>

                <p class="product-card__price">
                    {{ product.price }} €
                </p>

                <p class="product-card__stock">
                    Stock : {{ product.stock }}
                </p>
            </header>

            <div
                v-if="product.description"
                class="product-card__description"
            >
                <p>
                    {{ product.description }}
                </p>
            </div>

            <StockQuantityControl
                :product-id="product.id"
                :quantity="product.stock"
                @update-quantity="updateQuantity"
            />
        </div>
    </article>
</template>