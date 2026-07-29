<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <main class="page-container customer-dashboard">
        <h1>Mon compte</h1>

        <nav
            class="customer-dashboard__nav"
            aria-label="Navigation du compte client"
        >
            <Link
                href="/profile"
                class="customer-dashboard__card"
            >
                <h2>Mes informations</h2>

                <p>
                    Modifier mes informations personnelles et mon mot de passe.
                </p>
            </Link>

            <Link
                href="/customer/orders"
                class="customer-dashboard__card"
            >
                <h2>Mon historique</h2>

                <p>
                    Consulter la liste de mes commandes.
                </p>
            </Link>
        </nav>
    </main>
</template>