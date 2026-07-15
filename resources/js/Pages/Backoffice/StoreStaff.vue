<script setup>
defineProps({
    store: {
        type: Object,
        required: true,
    },
    staff: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <main>
        <h1>Personnel de {{ store.name }}</h1>

        <ul>
            <li
                v-for="member in staff"
                :key="member.id"
            >
                {{ member.name }} — {{ member.role }}
            </li>
        </ul>
    </main>
</template>