<script setup>
defineProps({
    stores: {
        type: Array,
        required: true,
    },
    selectedStore: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['select-store']);
</script>

<template>
    <ul class="store-selector">
        <li
            v-for="store in stores"
            :key="store.id"
            @click="emit('select-store', store)"
            :class="{ active: selectedStore && selectedStore.id === store.id }"
        >
            {{ store.name }}
        </li>
    </ul>
</template>