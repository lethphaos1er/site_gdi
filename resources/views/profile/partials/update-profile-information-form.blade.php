<section class="profile-information">
    <header class="profile-information__header">
        <h2 id="profile-information-title">
            Informations personnelles
        </h2>

        <p>
            Modifiez les informations de votre compte ainsi que votre adresse e-mail.
        </p>
    </header>

    <form
        id="send-verification"
        method="post"
        action="{{ route('verification.send') }}"
    >
        @csrf
    </form>

    <form
        method="post"
        action="{{ route('profile.update') }}"
        class="profile-information__form"
    >
        @csrf
        @method('patch')

        <div class="profile-information__field">
            <x-input-label
                for="name"
                :value="__('Nom')"
            />

            <x-text-input
                id="name"
                name="name"
                type="text"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
                class="profile-information__input"
            />

            <x-input-error
                :messages="$errors->get('name')"
            />
        </div>

        <div class="profile-information__field">
            <x-input-label
                for="email"
                :value="__('Adresse e-mail')"
            />

            <x-text-input
                id="email"
                name="email"
                type="email"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
                class="profile-information__input"
            />

            <x-input-error
                :messages="$errors->get('email')"
            />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="profile-information__verification">
                    <p>
                        Votre adresse e-mail n'est pas encore vérifiée.
                    </p>

                    <button
                        type="submit"
                        form="send-verification"
                        class="profile-information__verification-button"
                    >
                        Renvoyer l'e-mail de vérification
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="profile-information__success">
                            Un nouvel e-mail de vérification a été envoyé.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="profile-information__actions">
            <x-primary-button>
                Enregistrer
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="profile-information__success"
                >
                    Informations enregistrées.
                </p>
            @endif
        </div>
    </form>
</section>