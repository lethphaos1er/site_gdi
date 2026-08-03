<script setup>
import { computed, ref } from 'vue';
import {
    Link,
    router,
    useForm,
    usePage,
} from '@inertiajs/vue3';
import StoreHeader from '@/Components/Front/StoreSelection/StoreHeader.vue';

const props = defineProps({
    store: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const successMessage = computed(() => {
    return page.props.flash?.success ?? '';
});

const isDeleting = ref(false);

const form = useForm({
    name: props.store.name ?? '',
    address: props.store.address ?? '',
    phone: props.store.phone ?? '',
    email: props.store.email ?? '',
    api_base_url: props.store.api_base_url ?? '',
    type: props.store.type ?? '',
    identifier: props.store.identifier ?? '',
    owner_name: props.store.owner_name ?? '',
    city: props.store.city ?? '',
});

function submit() {
    form.put(`/backoffice/stores/${props.store.id}`);
}

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
    <StoreHeader
        :store-name="store.name"
        :store-city="store.city"
    />

    <main class="page-container customer-dashboard">
        <p
            v-if="successMessage"
            class="feedback-message feedback-message--success"
            role="status"
        >
            {{ successMessage }}
        </p>

        <p>
            <Link
                class="button button--secondary"
                href="/backoffice/stores"
            >
                Retour aux magasins
            </Link>
        </p>

        <section aria-labelledby="store-title">
            <h1 id="store-title">
                {{ store.name }}
            </h1>

            <p>
                Gestion du point de vente
            </p>
        </section>

        <section class="store-create">
            <header class="store-create__header">
                <h2>
                    Informations du magasin
                </h2>
            </header>

            <form
                class="store-create-form"
                @submit.prevent="submit"
            >
                <div class="store-create-form__field">
                    <label for="store-name">
                        Nom
                    </label>

                    <input
                        id="store-name"
                        v-model="form.name"
                        type="text"
                        maxlength="80"
                        required
                    >

                    <p
                        v-if="form.errors.name"
                        class="store-create-form__error"
                    >
                        {{ form.errors.name }}
                    </p>
                </div>

                <div class="store-create-form__field">
                    <label for="store-address">
                        Adresse
                    </label>

                    <input
                        id="store-address"
                        v-model="form.address"
                        type="text"
                        maxlength="80"
                        required
                    >

                    <p
                        v-if="form.errors.address"
                        class="store-create-form__error"
                    >
                        {{ form.errors.address }}
                    </p>
                </div>

                <div class="store-create-form__field">
                    <label for="store-city">
                        Ville
                    </label>

                    <input
                        id="store-city"
                        v-model="form.city"
                        type="text"
                        maxlength="80"
                        autocomplete="address-level2"
                        required
                    >

                    <p
                        v-if="form.errors.city"
                        class="store-create-form__error"
                    >
                        {{ form.errors.city }}
                    </p>
                </div>

                <div class="store-create-form__field">
                    <label for="store-phone">
                        GSM
                    </label>

                    <input
                        id="store-phone"
                        v-model="form.phone"
                        type="tel"
                        maxlength="30"
                        required
                    >

                    <p
                        v-if="form.errors.phone"
                        class="store-create-form__error"
                    >
                        {{ form.errors.phone }}
                    </p>
                </div>

                <div class="store-create-form__field">
                    <label for="store-email">
                        E-mail
                    </label>

                    <input
                        id="store-email"
                        v-model="form.email"
                        type="email"
                        required
                    >

                    <p
                        v-if="form.errors.email"
                        class="store-create-form__error"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <div class="store-create-form__field">
                    <label for="store-api-base-url">
                        URL de l’API
                    </label>

                    <input
                        id="store-api-base-url"
                        v-model="form.api_base_url"
                        type="url"
                        maxlength="255"
                    >

                    <p
                        v-if="form.errors.api_base_url"
                        class="store-create-form__error"
                    >
                        {{ form.errors.api_base_url }}
                    </p>
                </div>

                <div class="store-create-form__field">
                    <label for="store-type">
                        Type de magasin
                    </label>

                    <select
                        id="store-type"
                        v-model="form.type"
                        required
                    >
                        <option value="bakery">
                            Boulangerie
                        </option>

                        <option value="italian">
                            Italien
                        </option>

                        <option value="sport">
                            Sport
                        </option>
                    </select>

                    <p
                        v-if="form.errors.type"
                        class="store-create-form__error"
                    >
                        {{ form.errors.type }}
                    </p>
                </div>

                <div class="store-create-form__field">
                    <label for="store-identifier">
                        Identifiant
                    </label>

                    <input
                        id="store-identifier"
                        v-model="form.identifier"
                        type="text"
                        minlength="10"
                        maxlength="10"
                        pattern="[A-Za-z0-9]{10}"
                        required
                    >

                    <p class="store-create-form__help">
                        10 caractères alphanumériques.
                    </p>

                    <p
                        v-if="form.errors.identifier"
                        class="store-create-form__error"
                    >
                        {{ form.errors.identifier }}
                    </p>
                </div>

                <div class="store-create-form__field">
                    <label for="store-owner-name">
                        Patron
                    </label>

                    <input
                        id="store-owner-name"
                        v-model="form.owner_name"
                        type="text"
                        maxlength="80"
                    >

                    <p
                        v-if="form.errors.owner_name"
                        class="store-create-form__error"
                    >
                        {{ form.errors.owner_name }}
                    </p>
                </div>

                <div class="store-create-form__actions">
                    <button
                        type="submit"
                        class="button"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Sauvegarde…' : 'Sauvegarder' }}
                    </button>
                </div>
            </form>
        </section>

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
    </main>
</template>