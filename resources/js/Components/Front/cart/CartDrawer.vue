<script setup>
import { ref } from 'vue';
import CartItem from './CartItem.vue';
import { useCartStore } from '@/stores/cart';

const cart = useCartStore();
const isOpen = ref(false);

function toggleCart() {
    isOpen.value = !isOpen.value;
}
</script>

<template>
    <aside class="cart-drawer" :class="{ 'cart-drawer--open': isOpen }">
        <button type="button" class="cart-drawer__toggle" @click="toggleCart">
            🛒

            <span v-if="cart.totalItems" class="cart-drawer__count">
                {{ cart.totalItems }}
            </span>
        </button>

        <div v-if="isOpen" class="cart-drawer__panel">
            <h2 class="cart-drawer__title">
                Panier
            </h2>

            <p v-if="!cart.items.length">
                Votre panier est vide.
            </p>

            <ul v-else class="cart-drawer__list">
                <CartItem
                    v-for="item in cart.items"
                    :key="item.id"
                    :item="item"
                />
            </ul>

            <div class="cart-drawer__footer">
                <p>
                    Total général : {{ cart.totalPrice }} €
                </p>

                <button type="button" class="cart-drawer__pay">
                    Payer
                </button>
            </div>
        </div>
    </aside>
</template>