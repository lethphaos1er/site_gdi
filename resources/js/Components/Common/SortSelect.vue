<script setup>
defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue']);

function updateValue(event) {
    emit('update:modelValue', event.target.value);
}
</script>

<template>
    <div class="sort-select">
        <label for="sort-select">
            Trier par
        </label>

        <select
            id="sort-select"
            :value="modelValue"
            @change="updateValue"
        >
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
    </div>
</template>