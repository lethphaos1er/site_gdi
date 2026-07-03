<script setup>
import ProductQuantity from '@/Components/Front/storeSelection/ProductQuantity.vue';
import { useCartStore } from '@/stores/cart';

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const cart = useCartStore();

function updateQuantity(quantity) {
    cart.updateQuantity(props.item.id, quantity);
}

function removeItem() {
    cart.removeProduct(props.item.id);
}
</script>

<template>
    <li class="cart-item">
        <article class="cart-item__card">
            <img :src="item.image" :alt="item.name" class="cart-item__image">

            <div class="cart-item__content">
                <header class="cart-item__header">
                    <h3 class="cart-item__title">
                        {{ item.name }}
                    </h3>

                    <p class="cart-item__price">
                        {{ item.price }} € / unité
                    </p>
                </header>

                <ProductQuantity :stock="item.stock" :model-value="item.quantity" :show-action-button="false"
                    @update:model-value="updateQuantity" />

                <p class="cart-item__subtotal">
                    Sous-total : {{ item.price * item.quantity }} €
                </p>

                <button type="button" class="cart-item__delete" @click="removeItem">
                    🗑️ Supprimer
                </button>
            </div>
        </article>
    </li>
</template>