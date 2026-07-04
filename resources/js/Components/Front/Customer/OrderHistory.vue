<script setup>
import { Link } from '@inertiajs/vue3';

const orders = [
    {
        id: 1,
        date: '04/07/2026',
        total: '82,50 €',
    },
    {
        id: 2,
        date: '15/06/2026',
        total: '36,90 €',
    },
    {
        id: 3,
        date: '02/06/2026',
        total: '124,10 €',
    },
];
</script>

<template>
    <main class="customer-orders">
        <h1>Mon historique</h1>

        <ul
            v-if="orders.length"
            class="customer-orders__list"
        >
            <li
                v-for="order in orders"
                :key="order.id"
            >
                <Link
                    :href="`/customer/orders/${order.id}`"
                    class="customer-orders__item"
                >
                    <span>{{ order.date }}</span>

                    <strong>{{ order.total }}</strong>
                </Link>
            </li>
        </ul>

        <p v-else>
            Vous n'avez encore passé aucune commande.
        </p>
    </main>
</template>