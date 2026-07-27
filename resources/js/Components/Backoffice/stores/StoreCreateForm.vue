<script setup>
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['close']);

const form = useForm({
    name: '',
    address: '',
    phone: '',
    email: '',
    api_base_url: '',
    type: '',
    identifier: '',
});

function submit() {
    form.post(route('backoffice.stores.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit('close');
        },
    });
}
</script>

<template>
    <form class="store-create-form" @submit.prevent="submit">
        <div class="store-create-form__field">
            <label for="store-name">
                Nom du point de vente
            </label>

            <input id="store-name" v-model="form.name" type="text" autocomplete="organization" required>

            <p v-if="form.errors.name" class="store-create-form__error">
                {{ form.errors.name }}
            </p>
        </div>

        <div class="store-create-form__field">
            <label for="store-address">
                Adresse
            </label>

            <input id="store-address" v-model="form.address" type="text" autocomplete="street-address" required>

            <p v-if="form.errors.address" class="store-create-form__error">
                {{ form.errors.address }}
            </p>
        </div>

        <div class="store-create-form__field">
            <label for="store-phone">
                GSM
            </label>

            <input id="store-phone" v-model="form.phone" type="tel" autocomplete="tel" required>

            <p v-if="form.errors.phone" class="store-create-form__error">
                {{ form.errors.phone }}
            </p>
        </div>

        <div class="store-create-form__field">
            <label for="store-email">
                E-mail
            </label>

            <input id="store-email" v-model="form.email" type="email" autocomplete="email" required>

            <p v-if="form.errors.email" class="store-create-form__error">
                {{ form.errors.email }}
            </p>
        </div>

        <div class="store-create-form__field">
            <label for="store-api-base-url">
                URL de l’API
            </label>

            <input id="store-api-base-url" v-model="form.api_base_url" type="url" autocomplete="url" required>

            <p v-if="form.errors.api_base_url" class="store-create-form__error">
                {{ form.errors.api_base_url }}
            </p>
        </div>

        <div class="store-create-form__field">
            <label for="store-type">
                Type de magasin
            </label>

            <select id="store-type" v-model="form.type" required>
                <option value="" disabled>
                    Sélectionnez un type
                </option>

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

            <p v-if="form.errors.type" class="store-create-form__error">
                {{ form.errors.type }}
            </p>
        </div>

        <div class="store-create-form__field">
            <label for="store-identifier">
                Identifiant du point de vente
            </label>

            <input id="store-identifier" v-model="form.identifier" type="text" maxlength="10" minlength="10"
                pattern="[A-Za-z0-9]{10}" autocomplete="off" required>

            <p class="store-create-form__help">
                10 caractères alphanumériques.
            </p>

            <p v-if="form.errors.identifier" class="store-create-form__error">
                {{ form.errors.identifier }}
            </p>
        </div>

        <div class="store-create-form__actions">
            <button type="button" class="button" :disabled="form.processing" @click="emit('close')">
                Annuler
            </button>

            <button type="submit" class="button" :disabled="form.processing">
                {{ form.processing ? 'Ajout en cours…' : 'Ajouter le point de vente' }}
            </button>
        </div>
    </form>
</template>