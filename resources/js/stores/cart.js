import { defineStore } from 'pinia';

const CART_STORAGE_KEY = 'gdi-cart';

function loadCartItems() {
    const storedItems = localStorage.getItem(CART_STORAGE_KEY);

    if (!storedItems) {
        return [];
    }

    const items = JSON.parse(storedItems);

    if (!Array.isArray(items)) {
        return [];
    }

    const hasInvalidItem = items.some((item) => {
        return !item.storeId || !item.storeName || !item.orderDate;
    });

    if (hasInvalidItem) {
        localStorage.removeItem(CART_STORAGE_KEY);
        return [];
    }

    return items;
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

        storeId: (state) => state.items[0]?.storeId ?? null,

        storeName: (state) => state.items[0]?.storeName ?? null,

        orderDate: (state) => state.items[0]?.orderDate ?? null,

        hasItems: (state) => state.items.length > 0,
    },

    actions: {
        save() {
            saveCartItems(this.items);
        },

        canUseStore(storeId) {
            return !this.storeId || this.storeId === storeId;
        },

        canUseOrder(storeId, orderDate) {
            return this.canUseStore(storeId) && (!this.orderDate || this.orderDate === orderDate);
        },

        addProduct(product, quantity, store, orderDate) {
            if (!orderDate || !this.canUseOrder(store.id, orderDate)) {
                return false;
            }

            const existingItem = this.items.find((item) => item.id === product.id);

            if (existingItem) {
                this.updateQuantity(product.id, existingItem.quantity + quantity);
                return true;
            }

            this.items.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
                stock: product.stock,
                quantity,
                storeId: store.id,
                storeName: store.name,
                orderDate,
            });

            this.save();

            return true;
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