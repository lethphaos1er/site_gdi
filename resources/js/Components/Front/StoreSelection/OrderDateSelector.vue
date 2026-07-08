<script setup>
defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    minDate: {
        type: String,
        required: true,
    },
    isLocked: {
        type: Boolean,
        default: false,
    },
    lockedStoreName: {
        type: String,
        default: null,
    },
});

const emit = defineEmits([
    'update:modelValue',
]);

function updateDate(event) {
    emit('update:modelValue', event.target.value);
}
</script>

<template>
    <section
        class="order-date-selector"
        aria-labelledby="order-date-selector-title"
    >
        <div class="order-date-selector__content">
            <div>
                <h2
                    id="order-date-selector-title"
                    class="order-date-selector__title"
                >
                    Date de retrait
                </h2>

                <p class="order-date-selector__description">
                    Choisissez la date avant d’ajouter un article au panier.
                </p>
            </div>

            <label class="order-date-selector__field">
                <span class="order-date-selector__label">Date</span>

                <input
                    :value="modelValue"
                    class="order-date-selector__input"
                    type="date"
                    :min="minDate"
                    :disabled="isLocked"
                    @input="updateDate"
                >
            </label>
        </div>

        <p
            v-if="isLocked"
            class="order-date-selector__warning"
        >
            Le panier contient déjà des articles pour le magasin {{ lockedStoreName }} le {{ modelValue }}.
            Videz le panier pour modifier la date.
        </p>
    </section>
</template>