<script setup>
import ProductQuantity from './ProductQuantity.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

function addProduct(quantity) {
    console.log('Produit ajouté :', {
        product: props.product,
        quantity,
    });
}
</script>

<template>
    <article class="product-card">
        <img
            :src="product.image"
            :alt="product.name"
            class="product-card__image"
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
                    En stock : {{ product.stock }}
                </p>
            </header>

            <div class="product-card__description">
                <p>{{ product.description }}</p>

                <!-- Voir plus plus tard avec truncate -->
            </div>

            <ProductQuantity
                :stock="product.stock"
                @add-product="addProduct"
            />
        </div>
    </article>
</template>