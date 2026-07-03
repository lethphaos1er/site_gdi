<script setup>
import { ref } from 'vue';
import ProductQuantity from './ProductQuantity.vue';
import { useCartStore } from '@/stores/cart';
import { truncate, isTruncated } from '@/tools/tools.js';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const cart = useCartStore();
const isExpanded = ref(false);

function toggleDescription() {
    isExpanded.value = !isExpanded.value;
}

function addProduct(quantity) {
    cart.addProduct(props.product, quantity);
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
                <p>
                    {{
                        isExpanded
                            ? product.description
                            : truncate(product.description, 50)
                    }}
                </p>

                <button
                    v-if="isTruncated(product.description, 50)"
                    type="button"
                    class="product-card__more"
                    @click="toggleDescription"
                >
                    {{ isExpanded ? 'Voir moins ▲' : 'En savoir plus ▼' }}
                </button>
            </div>

            <ProductQuantity
                :stock="product.stock"
                @add-product="addProduct"
            />
        </div>
    </article>
</template>