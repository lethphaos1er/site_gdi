<script setup>
defineProps({
    departments: {
        type: Array,
        required: true,
    },
    selectedDepartmentId: {
        type: Number,
        default: null,
    },
});

const emit = defineEmits(['selectDepartment']);

function selectDepartment(departmentId) {
    emit('selectDepartment', departmentId);
}
</script>

<template>
    <ul
        v-if="departments.length > 0"
        class="department-selector"
    >
        <li
            v-for="department in departments"
            :key="department.id"
        >
            <button
                type="button"
                class="department-selector__button"
                :class="{
                    active: department.id === selectedDepartmentId,
                }"
                @click="selectDepartment(department.id)"
            >
                {{ department.name }}
            </button>
        </li>
    </ul>

    <p v-else>
        Aucun rayon n’est encore disponible pour ce magasin.
    </p>
</template>