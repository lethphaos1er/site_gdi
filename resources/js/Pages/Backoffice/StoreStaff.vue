<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    store: {
        type: Object,
        required: true,
    },

    staff: {
        type: Array,
        required: true,
    },

    permissions: {
        type: Object,
        required: true,
    },
});

const search = ref('');
const searchResults = ref([]);
const selectedUserId = ref(null);
const selectedRole = ref('employee');

const isSearching = ref(false);
const isAdding = ref(false);
const updatingMemberId = ref(null);
const removingMemberId = ref(null);

const searchError = ref('');

async function searchUsers() {
    searchError.value = '';
    searchResults.value = [];
    selectedUserId.value = null;

    const value = search.value.trim();

    if (value.length < 2) {
        searchError.value = 'Saisissez au moins 2 caractères.';
        return;
    }

    isSearching.value = true;

    try {
        const query = new URLSearchParams({
            search: value,
        });

        const response = await fetch(
            `/backoffice/stores/${props.store.id}/staff/search?${query}`,
            {
                method: 'GET',
                headers: {
                    Accept: 'application/json',
                },
                credentials: 'same-origin',
            },
        );

        const data = await response.json();

        if (!response.ok) {
            searchError.value =
                data.errors?.search?.[0]
                ?? 'La recherche a échoué.';

            return;
        }

        searchResults.value = data.users;
    } catch {
        searchError.value =
            'Une erreur est survenue pendant la recherche.';
    } finally {
        isSearching.value = false;
    }
}

function addStaffMember() {
    if (!selectedUserId.value) {
        return;
    }

    isAdding.value = true;

    router.post(
        `/backoffice/stores/${props.store.id}/staff`,
        {
            user_id: selectedUserId.value,
            role: selectedRole.value,
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                search.value = '';
                searchResults.value = [];
                selectedUserId.value = null;
                selectedRole.value = 'employee';
            },

            onFinish: () => {
                isAdding.value = false;
            },
        },
    );
}

function updateMemberRole(member, role) {
    if (
        !member.permissions.can_update_role
        || member.role === role
    ) {
        return;
    }

    const roleLabel =
        role === 'owner'
            ? 'patron'
            : 'employé';

    const confirmed = window.confirm(
        `Modifier le rôle de ${member.name} et le définir comme ${roleLabel} ?`,
    );

    if (!confirmed) {
        return;
    }

    updatingMemberId.value = member.id;

    router.put(
        `/backoffice/stores/${props.store.id}/staff/${member.id}`,
        {
            role,
        },
        {
            preserveScroll: true,

            onFinish: () => {
                updatingMemberId.value = null;
            },
        },
    );
}

function removeStaffMember(member) {
    if (!member.permissions.can_remove) {
        return;
    }

    const confirmed = window.confirm(
        `Retirer ${member.name} du personnel de ce magasin ?`,
    );

    if (!confirmed) {
        return;
    }

    removingMemberId.value = member.id;

    router.delete(
        `/backoffice/stores/${props.store.id}/staff/${member.id}`,
        {
            preserveScroll: true,

            onFinish: () => {
                removingMemberId.value = null;
            },
        },
    );
}

function roleLabel(role) {
    return role === 'owner'
        ? 'Patron'
        : 'Employé';
}
</script>

<template>
    <main class="store-staff-page">
        <header>
            <h1>
                Personnel de {{ store.name }}
            </h1>

            <p>
                Gérez les personnes autorisées à travailler dans ce magasin.
            </p>
        </header>

        <section
            class="store-staff-section"
            aria-labelledby="add-staff-title"
        >
            <h2 id="add-staff-title">
                Ajouter un membre du personnel
            </h2>

            <form @submit.prevent="searchUsers">
                <div>
                    <label for="staff-search">
                        Rechercher par prénom, nom ou adresse email
                    </label>

                    <input
                        id="staff-search"
                        v-model="search"
                        type="search"
                        name="search"
                        minlength="2"
                        maxlength="100"
                        autocomplete="off"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="button button--secondary"
                    :disabled="isSearching"
                >
                    {{
                        isSearching
                            ? 'Recherche…'
                            : 'Rechercher'
                    }}
                </button>
            </form>

            <p
                v-if="searchError"
                role="alert"
            >
                {{ searchError }}
            </p>

            <div
                v-if="searchResults.length > 0"
                aria-live="polite"
            >
                <h3>
                    Résultats
                </h3>

                <ul>
                    <li
                        v-for="user in searchResults"
                        :key="user.id"
                    >
                        <label>
                            <input
                                v-model="selectedUserId"
                                type="radio"
                                name="selected_user"
                                :value="user.id"
                            >

                            <span>
                                {{ user.full_name }}
                                — {{ user.email }}
                            </span>
                        </label>
                    </li>
                </ul>

                <div
                    v-if="permissions.can_assign_owner"
                    class="staff-role-selection"
                >
                    <label for="new-staff-role">
                        Rôle dans le magasin
                    </label>

                    <select
                        id="new-staff-role"
                        v-model="selectedRole"
                        name="role"
                    >
                        <option value="employee">
                            Employé
                        </option>

                        <option value="owner">
                            Patron
                        </option>
                    </select>
                </div>

                <button
                    type="button"
                    class="button button--primary"
                    :disabled="!selectedUserId || isAdding"
                    @click="addStaffMember"
                >
                    {{
                        isAdding
                            ? 'Ajout…'
                            : `Ajouter comme ${roleLabel(selectedRole).toLowerCase()}`
                    }}
                </button>
            </div>

            <p
                v-else-if="
                    search.trim().length >= 2
                    && !isSearching
                    && !searchError
                "
                aria-live="polite"
            >
                Aucun utilisateur trouvé.
            </p>
        </section>

        <section
            class="store-staff-section"
            aria-labelledby="staff-list-title"
        >
            <h2 id="staff-list-title">
                Personnel actuel
            </h2>

            <ul
                v-if="staff.length > 0"
                class="store-staff-list"
            >
                <li
                    v-for="member in staff"
                    :key="member.id"
                    class="staff-member"
                >
                    <div class="staff-member__identity">
                        <strong class="staff-member__name">
                            {{ member.name }}
                        </strong>

                        <a
                            class="staff-member__email"
                            :href="`mailto:${member.email}`"
                        >
                            {{ member.email }}
                        </a>
                    </div>

                    <span class="staff-member__role">
                        {{ roleLabel(member.role) }}
                    </span>

                    <div
                        v-if="
                            member.permissions.can_update_role
                            || member.permissions.can_remove
                        "
                        class="staff-member__actions"
                    >
                        <button
                            v-if="
                                member.permissions.can_update_role
                                && member.role === 'employee'
                            "
                            type="button"
                            class="button button--secondary"
                            :disabled="
                                updatingMemberId === member.id
                                || removingMemberId === member.id
                            "
                            @click="updateMemberRole(member, 'owner')"
                        >
                            {{
                                updatingMemberId === member.id
                                    ? 'Modification…'
                                    : 'Promouvoir patron'
                            }}
                        </button>

                        <button
                            v-if="
                                member.permissions.can_update_role
                                && member.role === 'owner'
                            "
                            type="button"
                            class="button button--secondary"
                            :disabled="
                                updatingMemberId === member.id
                                || removingMemberId === member.id
                            "
                            @click="updateMemberRole(member, 'employee')"
                        >
                            {{
                                updatingMemberId === member.id
                                    ? 'Modification…'
                                    : 'Rétrograder employé'
                            }}
                        </button>

                        <button
                            v-if="member.permissions.can_remove"
                            type="button"
                            class="button button--danger"
                            :disabled="
                                removingMemberId === member.id
                                || updatingMemberId === member.id
                            "
                            @click="removeStaffMember(member)"
                        >
                            {{
                                removingMemberId === member.id
                                    ? 'Suppression…'
                                    : 'Retirer du magasin'
                            }}
                        </button>
                    </div>
                </li>
            </ul>

            <p v-else>
                Aucun membre du personnel n’est rattaché à ce magasin.
            </p>
        </section>
    </main>
</template>