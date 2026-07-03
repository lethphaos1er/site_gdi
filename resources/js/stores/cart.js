import { defineStore } from 'pinia';

const CART_STORAGE_KEY = 'gdi-cart';

function loadCartItems() {
    const storedItems = localStorage.getItem(CART_STORAGE_KEY);

    if (!storedItems) {
        return [];
    }

    return JSON.parse(storedItems);
}

function saveCartItems(items) {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(items));
}

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: loadCartItems(),
    }),

    getters: {
        totalItems: (state) => state.items.reduce((total, item) => total + item.quantity, 0),

        totalPrice: (state) => state.items.reduce(
            (total, item) => total + item.price * item.quantity,
            0
        ),
    },

    actions: {
        save() {
            saveCartItems(this.items);
        },

        addProduct(product, quantity) {
            const existingItem = this.items.find((item) => item.id === product.id);

            if (existingItem) {
                this.updateQuantity(product.id, existingItem.quantity + quantity);
                return;
            }

            this.items.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
                stock: product.stock,
                quantity,
            });

            this.save();
        },

        updateQuantity(productId, quantity) {
            const item = this.items.find((cartItem) => cartItem.id === productId);

            if (!item) {
                return;
            }

            if (quantity < 1) {
                this.removeProduct(productId);
                return;
            }

            item.quantity = Math.min(quantity, item.stock);
            this.save();
        },

        removeProduct(productId) {
            this.items = this.items.filter((item) => item.id !== productId);
            this.save();
        },

        clearCart() {
            this.items = [];
            this.save();
        },
    },
});