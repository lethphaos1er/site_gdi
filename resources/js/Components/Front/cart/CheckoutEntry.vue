<script setup>
import { computed } from 'vue';

const props = defineProps({
    hasItems: {
        type: Boolean,
        required: true,
    },
    storeName: {
        type: String,
        default: null,
    },
    orderDate: {
        type: String,
        default: null,
    },
    totalItems: {
        type: Number,
        required: true,
    },
    totalPrice: {
        type: Number,
        required: true,
    },
});

const formattedOrderDate = computed(() => {
    if (!props.orderDate) {
        return '';
    }

    return props.orderDate.split('-').reverse().join('/');
});

const checkoutErrors = computed(() => {
    const errors = [];

    if (!props.hasItems) {
        errors.push('Votre panier est vide.');
    }

    if (!props.storeName) {
        errors.push('Aucun magasin n’est lié au panier.');
    }

    if (!props.orderDate) {
        errors.push('Aucune date de retrait n’est liée au panier.');
    }

    return errors;
});

const canStartCheckout = computed(() => checkoutErrors.value.length === 0);
</script>

<template>
    <section class="checkout-entry" aria-labelledby="checkout-entry-title">
        <h3 id="checkout-entry-title" class="checkout-entry__title">
            Confirmation avant paiement
        </h3>

        <ul v-if="!canStartCheckout" class="checkout-entry__errors">
            <li v-for="error in checkoutErrors" :key="error">
                {{ error }}
            </li>
        </ul>

        <div v-else class="checkout-entry__content">
            <p class="checkout-entry__text">
                Votre panier est prêt. Vérifiez les informations avant de passer à l’étape de paiement.
            </p>

            <dl class="checkout-entry__list">
                <div class="checkout-entry__row">
                    <dt class="checkout-entry__label">
                        Magasin
                    </dt>

                    <dd class="checkout-entry__value">
                        {{ storeName }}
                    </dd>
                </div>

                <div class="checkout-entry__row">
                    <dt class="checkout-entry__label">
                        Date de retrait
                    </dt>

                    <dd class="checkout-entry__value">
                        {{ formattedOrderDate }}
                    </dd>
                </div>

                <div class="checkout-entry__row">
                    <dt class="checkout-entry__label">
                        Articles
                    </dt>

                    <dd class="checkout-entry__value">
                        {{ totalItems }}
                    </dd>
                </div>

                <div class="checkout-entry__row checkout-entry__row--total">
                    <dt class="checkout-entry__label">
                        Total
                    </dt>

                    <dd class="checkout-entry__value">
                        {{ totalPrice }} €
                    </dd>
                </div>
            </dl>

            <p class="checkout-entry__notice">
                Le paiement réel sera ajouté dans une étape dédiée.
            </p>
        </div>
    </section>
</template>