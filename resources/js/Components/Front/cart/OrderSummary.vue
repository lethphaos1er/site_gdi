<script setup>
import { computed } from 'vue';

const props = defineProps({
    storeName: {
        type: String,
        required: true,
    },
    orderDate: {
        type: String,
        required: true,
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
</script>

<template>
    <section class="order-summary" aria-labelledby="order-summary-title">
        <h3 id="order-summary-title" class="order-summary__title">
            Résumé de commande
        </h3>

        <dl class="order-summary__list">
            <div class="order-summary__row">
                <dt class="order-summary__label">
                    Magasin
                </dt>

                <dd class="order-summary__value">
                    {{ storeName }}
                </dd>
            </div>

            <div class="order-summary__row">
                <dt class="order-summary__label">
                    Date de retrait
                </dt>

                <dd class="order-summary__value">
                    {{ formattedOrderDate }}
                </dd>
            </div>

            <div class="order-summary__row">
                <dt class="order-summary__label">
                    Articles
                </dt>

                <dd class="order-summary__value">
                    {{ totalItems }}
                </dd>
            </div>

            <div class="order-summary__row order-summary__row--total">
                <dt class="order-summary__label">
                    Total
                </dt>

                <dd class="order-summary__value">
                    {{ totalPrice }} €
                </dd>
            </div>
        </dl>
    </section>
</template>