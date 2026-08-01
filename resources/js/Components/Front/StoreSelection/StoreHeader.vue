<script setup>
import StoreHeaderNav from './StoreHeaderNav.vue';

defineProps({
    storeName: {
        type: String,
        default: '',
    },
    storeCity: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <header class="store-header">
        <div v-if="storeName" class="store-header__context">
            <p>
                Magasin actuel :
                <strong>
                    {{ storeName }}
                    <span v-if="storeCity">
                        — {{ storeCity }}
                    </span>
                </strong>
            </p>
        </div>

        <StoreHeaderNav />
    </header>
</template>