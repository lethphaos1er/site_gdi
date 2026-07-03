<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <nav class="store-header-nav" aria-label="Navigation principale">
        <ul class="store-header-nav__list">
            <li>
                <Link href="/">Accueil</Link>
            </li>

            <template v-if="page.props.auth?.user">
                <li>
                    <Link href="/profile">Mon profil</Link>
                </li>

                <li>
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        type="button"
                    >
                        Déconnexion
                    </Link>
                </li>
            </template>

            <template v-else>
                <li>
                    <Link href="/login">Connexion</Link>
                </li>

                <li>
                    <Link href="/register">Inscription</Link>
                </li>
            </template>
        </ul>
    </nav>
</template>