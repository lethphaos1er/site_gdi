<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    store: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Link
        class="customer-dashboard__card"
        :href="`/backoffice/stores/${store.id}/stock`"
    >
        <h2>
            {{ store.name }}
        </h2>

        <p v-if="store.city">
            Magasin de {{ store.city }}
        </p>

        <p>
            Gérer le stock
        </p>
    </Link>
</template>