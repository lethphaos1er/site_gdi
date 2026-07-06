<script setup>
import StockProductCard from './StockProductCard.vue';

defineProps({
    department: {
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
    <section :aria-labelledby="`department-${department.id}-title`">
        <h2 :id="`department-${department.id}-title`">
            {{ department.name }}
        </h2>

        <div
            v-if="department.products && department.products.length > 0"
            class="products-grid"
        >
            <StockProductCard
                v-for="product in department.products"
                :key="product.id"
                :product="product"
                @update-quantity="updateQuantity"
            />
        </div>

        <p v-else>
            Aucun produit dans ce rayon.
        </p>
    </section>
</template>