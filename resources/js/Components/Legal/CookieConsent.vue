<script setup>
import { computed, onMounted, ref } from 'vue';

const CONSENT_STORAGE_KEY = 'site_gdi_cookie_consent';
const CONSENT_VERSION = 1;

const isVisible = ref(false);
const isSettingsOpen = ref(false);

const consent = ref({
    necessary: true,
    analytics: false,
    marketing: false,
});

const hasOptionalConsent = computed(() => {
    return consent.value.analytics || consent.value.marketing;
});

function getStoredConsent() {
    const storedConsent = localStorage.getItem(CONSENT_STORAGE_KEY);

    if (!storedConsent) {
        return null;
    }

    try {
        return JSON.parse(storedConsent);
    } catch {
        localStorage.removeItem(CONSENT_STORAGE_KEY);

        return null;
    }
}

function saveConsent(newConsent) {
    const consentPayload = {
        version: CONSENT_VERSION,
        necessary: true,
        analytics: newConsent.analytics,
        marketing: newConsent.marketing,
        updatedAt: new Date().toISOString(),
    };

    localStorage.setItem(CONSENT_STORAGE_KEY, JSON.stringify(consentPayload));

    consent.value = {
        necessary: true,
        analytics: consentPayload.analytics,
        marketing: consentPayload.marketing,
    };

    isVisible.value = false;
    isSettingsOpen.value = false;
}

function acceptAllCookies() {
    saveConsent({
        analytics: true,
        marketing: true,
    });
}

function refuseOptionalCookies() {
    saveConsent({
        analytics: false,
        marketing: false,
    });
}

function openCookieSettings() {
    isSettingsOpen.value = true;
}

function closeCookieSettings() {
    isSettingsOpen.value = false;
}

function saveCookieSettings() {
    saveConsent({
        analytics: consent.value.analytics,
        marketing: consent.value.marketing,
    });
}

onMounted(() => {
    const storedConsent = getStoredConsent();

    if (!storedConsent || storedConsent.version !== CONSENT_VERSION) {
        isVisible.value = true;

        return;
    }

    consent.value = {
        necessary: true,
        analytics: Boolean(storedConsent.analytics),
        marketing: Boolean(storedConsent.marketing),
    };
});
</script>

<template>
    <section v-if="isVisible" class="legal-notice-banner" aria-labelledby="cookie-consent-title">
        <div class="cookie-consent__content">
            <h2 id="cookie-consent-title" class="cookie-consent__title">
                Gestion des cookies
            </h2>

            <p class="cookie-consent__text">
                Nous utilisons les cookies nécessaires au fonctionnement du site.
                Vous pouvez aussi accepter ou refuser les cookies optionnels
                destinés aux statistiques et au marketing.
            </p>

            <p v-if="!hasOptionalConsent" class="cookie-consent__notice">
                Aucun cookie optionnel n’est activé sans votre accord.
            </p>
        </div>

        <div class="cookie-consent__actions">
            <button type="button" class="cookie-consent__button cookie-consent__button--secondary"
                @click="refuseOptionalCookies">
                Refuser
            </button>

            <button type="button" class="cookie-consent__button cookie-consent__button--secondary"
                @click="openCookieSettings">
                Paramétrer
            </button>

            <button type="button" class="cookie-consent__button cookie-consent__button--primary"
                @click="acceptAllCookies">
                Tout accepter
            </button>
        </div>

        <div v-if="isSettingsOpen" class="cookie-consent__settings" aria-labelledby="cookie-settings-title">
            <h3 id="cookie-settings-title" class="cookie-consent__settings-title">
                Préférences des cookies
            </h3>

            <div class="cookie-consent__option">
                <input id="cookie-necessary" type="checkbox" checked disabled>

                <label for="cookie-necessary">
                    Cookies nécessaires
                    <span>
                        Obligatoires pour le fonctionnement du site.
                    </span>
                </label>
            </div>

            <div class="cookie-consent__option">
                <input id="cookie-analytics" v-model="consent.analytics" type="checkbox">

                <label for="cookie-analytics">
                    Cookies statistiques
                    <span>
                        Utilisés pour comprendre l’utilisation du site.
                    </span>
                </label>
            </div>

            <div class="cookie-consent__option">
                <input id="cookie-marketing" v-model="consent.marketing" type="checkbox">

                <label for="cookie-marketing">
                    Cookies marketing
                    <span>
                        Utilisés pour personnaliser certaines communications.
                    </span>
                </label>
            </div>

            <div class="cookie-consent__settings-actions">
                <button type="button" class="cookie-consent__button cookie-consent__button--secondary"
                    @click="closeCookieSettings">
                    Retour
                </button>

                <button type="button" class="cookie-consent__button cookie-consent__button--primary"
                    @click="saveCookieSettings">
                    Enregistrer mes choix
                </button>
            </div>
        </div>
    </section>
</template>