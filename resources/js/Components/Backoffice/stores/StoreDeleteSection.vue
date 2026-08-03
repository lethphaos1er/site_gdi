<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    store: {
        type: Object,
        required: true,
    },
});

const isDeleting = ref(false);

function destroyStore() {
    const confirmed = window.confirm(
        `Supprimer définitivement le magasin "${props.store.name}" ?`
    );

    if (!confirmed) {
        return;
    }

    router.delete(`/backoffice/stores/${props.store.id}`, {
        onStart: () => {
            isDeleting.value = true;
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
}
</script>

<template>
    <section class="store-create">
        <header class="store-create__header">
            <h2>
                Supprimer le magasin
            </h2>
        </header>

        <p>
            Cette action supprime définitivement ce point de vente.
        </p>

        <button
            type="button"
            class="button"
            :disabled="isDeleting"
            @click="destroyStore"
        >
            {{ isDeleting ? 'Suppression…' : 'Supprimer le magasin' }}
        </button>
    </section>
</template>