<section class="profile-delete">
    <header class="profile-delete__header">
        <h2 id="profile-delete-title">
            Supprimer le compte
        </h2>

        <p>
            Une fois votre compte supprimé, toutes vos données seront définitivement effacées. Cette action est irréversible.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="profile-delete__button"
    >
        Supprimer mon compte
    </x-danger-button>

    <x-modal
        name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        focusable
    >
        <form
            method="post"
            action="{{ route('profile.destroy') }}"
            class="profile-delete__form"
        >
            @csrf
            @method('delete')

            <h2>
                Confirmer la suppression
            </h2>

            <p>
                Cette action est définitive. Saisissez votre mot de passe pour confirmer la suppression de votre compte.
            </p>

            <div class="profile-delete__field">
                <x-input-label
                    for="password"
                    value="Mot de passe"
                    class="sr-only"
                />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Mot de passe"
                    class="profile-delete__input"
                />

                <x-input-error
                    :messages="$errors->userDeletion->get('password')"
                />
            </div>

            <div class="profile-delete__actions">
                <x-secondary-button
                    x-on:click="$dispatch('close')"
                >
                    Annuler
                </x-secondary-button>

                <x-danger-button>
                    Supprimer définitivement
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>