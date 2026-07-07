<script setup>
import { computed, ref } from 'vue';
import ProductQuantity from './ProductQuantity.vue';
import { useCartStore } from '@/stores/cart';
import { truncate, isTruncated } from '@/tools/tools.js';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },

    store: {
        type: Object,
        required: true,
    },
});

const cart = useCartStore();
const isExpanded = ref(false);
const hasStoreError = ref(false);

const isLockedToAnotherStore = computed(() => !cart.canUseStore(props.store.id));

function toggleDescription() {
    isExpanded.value = !isExpanded.value;
}

function addProduct(quantity) {
    const isAdded = cart.addProduct(props.product, quantity, props.store);

    hasStoreError.value = !isAdded;
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
            

            <p
                v-if="hasStoreError || isLockedToAnotherStore"
                class="product-card__warning"
            >
                Vous avez déjà des articles du magasin {{ cart.storeName }} dans le panier.
                Impossible d’ajouter des articles venant d’un autre magasin.
                Videz le panier pour changer de magasin.
            </p>

            <ProductQuantity
                :stock="product.stock"
                :is-action-disabled="isLockedToAnotherStore"
                @add-product="addProduct"
            />
            <p style="display: block; padding: 1rem; border: 3px solid red; background: yellow; color: black;">
    DEBUG PRODUCT CARD - {{ cart.storeName ?? 'aucun magasin panier' }}
</p>
        </div>
    </article>
</template>