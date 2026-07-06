<x-app-layout>
    <x-slot name="header">
        <h1 class="page-title">
            Mon compte
        </h1>
    </x-slot>

    <main class="profile-page">
        <div class="profile-page__container">
            <section
                class="profile-card"
                aria-labelledby="profile-information-title"
            >
                @include('profile.partials.update-profile-information-form')
            </section>

            <section
                class="profile-card"
                aria-labelledby="profile-password-title"
            >
                @include('profile.partials.update-password-form')
            </section>

            <section
                class="profile-card profile-card--danger"
                aria-labelledby="profile-delete-title"
            >
                @include('profile.partials.delete-user-form')
            </section>
        </div>
    </main>
</x-app-layout>