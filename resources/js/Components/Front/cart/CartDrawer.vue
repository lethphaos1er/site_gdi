<script setup>
import { ref } from 'vue';
import CartItem from './CartItem.vue';
import OrderSummary from './OrderSummary.vue';
import CheckoutEntry from './CheckoutEntry.vue';
import { useCartStore } from '@/stores/cart';

const cart = useCartStore();
const isOpen = ref(false);
const showCheckoutEntry = ref(false);

function toggleCart() {
    isOpen.value = !isOpen.value;
}

function startCheckout() {
    showCheckoutEntry.value = true;
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

            <div v-if="cart.hasItems" class="cart-drawer__summary">
                <OrderSummary
                    :store-name="cart.storeName"
                    :order-date="cart.orderDate"
                    :total-items="cart.totalItems"
                    :total-price="cart.totalPrice"
                />

                <button
                    type="button"
                    class="cart-drawer__pay"
                    @click="startCheckout"
                >
                    Payer
                </button>

                <CheckoutEntry
                    v-if="showCheckoutEntry"
                    :has-items="cart.hasItems"
                    :store-name="cart.storeName"
                    :order-date="cart.orderDate"
                    :total-items="cart.totalItems"
                    :total-price="cart.totalPrice"
                />
            </div>
        </div>
    </aside>
</template>