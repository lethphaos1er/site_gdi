<script setup>
import { computed } from 'vue';
import {
    Link,
    usePage,
} from '@inertiajs/vue3';
import StoreHeader from '@/Components/Front/StoreSelection/StoreHeader.vue';
import StoreEditForm from '@/Components/Backoffice/stores/StoreEditForm.vue';
import StoreDeleteSection from '@/Components/Backoffice/stores/StoreDeleteSection.vue';

const props = defineProps({
    store: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const successMessage = computed(() => {
    return page.props.flash?.success ?? '';
});

</script>

<template>
    <StoreHeader
        :store-name="store.name"
        :store-city="store.city"
    />

    <main class="page-container customer-dashboard">
        <p
            v-if="successMessage"
            class="feedback-message feedback-message--success"
            role="status"
        >
            {{ successMessage }}
        </p>

        <p>
            <Link
                class="button button--secondary"
                href="/backoffice/stores"
            >
                Retour aux magasins
            </Link>
        </p>

        <section aria-labelledby="store-title">
            <h1 id="store-title">
                {{ store.name }}
            </h1>

            <p>
                Gestion du point de vente
            </p>
        </section>

        <StoreEditForm :store="store" />

        <StoreDeleteSection :store="store" />

    </main>
</template>