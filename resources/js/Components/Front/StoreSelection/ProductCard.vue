<script setup>
import { computed, ref, watch } from 'vue';
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

    orderDate: {
        type: String,
        required: true,
    },
});

const cart = useCartStore();
const isExpanded = ref(false);
const hasOrderError = ref(false);

const isLockedToAnotherStore = computed(() => !cart.canUseStore(props.store.id));

const isLockedToAnotherDate = computed(() => {
    return Boolean(cart.orderDate) && cart.orderDate !== props.orderDate;
});

const isActionDisabled = computed(() => {
    return !props.orderDate || isLockedToAnotherStore.value || isLockedToAnotherDate.value;
});

const formattedCartOrderDate = computed(() => {
    if (!cart.orderDate) {
        return null;
    }

    return new Intl.DateTimeFormat('fr-BE').format(new Date(cart.orderDate));
});

watch(
    () => [props.store.id, props.orderDate, cart.storeId, cart.orderDate],
    () => {
        hasOrderError.value = false;
    }
);

function toggleDescription() {
    isExpanded.value = !isExpanded.value;
}

function addProduct(quantity) {
    const isAdded = cart.addProduct(props.product, quantity, props.store, props.orderDate);

    hasOrderError.value = !isAdded;
}
</script>

<template>
    <article class="product-card">
        <img
            v-if="product.image"
            :src="product.image"
            :alt="product.name"
            class="product-card__image"
        >

        <div
            v-else
            class="product-card__image product-card__image--placeholder"
            aria-hidden="true"
        >
            🥐
        </div>

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
                v-if="isLockedToAnotherStore"
                class="product-card__warning"
            >
                Vous avez déjà des articles dans votre panier pour le magasin
                {{ cart.storeName }}
                <template v-if="formattedCartOrderDate">
                    le {{ formattedCartOrderDate }}
                </template>.
                Videz le panier pour commander dans un autre magasin.
            </p>

            <p
                v-else-if="isLockedToAnotherDate"
                class="product-card__warning"
            >
                Vous avez déjà des articles dans votre panier pour le magasin
                {{ cart.storeName }}
                le {{ formattedCartOrderDate }}.
                Videz le panier pour choisir une autre date.
            </p>

            <p
                v-else-if="hasOrderError"
                class="product-card__warning"
            >
                Impossible d’ajouter cet article avec le magasin ou la date sélectionnée.
                Vérifiez votre panier avant de continuer.
            </p>

            <ProductQuantity
                :stock="product.stock"
                :is-action-disabled="isActionDisabled"
                @add-product="addProduct"
            />
        </div>
    </article>
</template>