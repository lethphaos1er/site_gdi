<script setup>
const emit = defineEmits([
    'save-stock',
]);

function saveStock() {
    emit('save-stock');
}
</script>

<template>
    <aside
        class="stock-save"
        aria-label="Sauvegarde du stock"
    >
        <button
            type="button"
            class="stock-save__button"
            aria-label="Sauvegarder les modifications du stock"
            @click="saveStock"
        >
            <span aria-hidden="true">
                💾
            </span>

            <span class="stock-save__label">
                Sauvegarder les modifications
            </span>
        </button>
    </aside>
</template>