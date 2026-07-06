<section class="profile-password">
    <header class="profile-password__header">
        <h2 id="profile-password-title">
            Modifier le mot de passe
        </h2>

        <p>
            Utilisez un mot de passe long et unique afin de protéger votre compte.
        </p>
    </header>

    <form
        method="post"
        action="{{ route('password.update') }}"
        class="profile-password__form"
    >
        @csrf
        @method('put')

        <div class="profile-password__field">
            <x-input-label
                for="update_password_current_password"
                :value="__('Mot de passe actuel')"
            />

            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                autocomplete="current-password"
                class="profile-password__input"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
            />
        </div>

        <div class="profile-password__field">
            <x-input-label
                for="update_password_password"
                :value="__('Nouveau mot de passe')"
            />

            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                autocomplete="new-password"
                class="profile-password__input"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password')"
            />
        </div>

        <div class="profile-password__field">
            <x-input-label
                for="update_password_password_confirmation"
                :value="__('Confirmer le mot de passe')"
            />

            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="profile-password__input"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password_confirmation')"
            />
        </div>

        <div class="profile-password__actions">
            <x-primary-button>
                Enregistrer
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="profile-password__success"
                >
                    Mot de passe mis à jour.
                </p>
            @endif
        </div>
    </form>
</section>