<script setup>
import { ref } from 'vue';
import stores from '@/data/stores';

const selectedStore = ref(null);
const selectedDepartment = ref(null);

function selectStore(store) {
    selectedStore.value = store;
    selectedDepartment.value = null;

    console.log('Magasin sélectionné :', store);
}

function selectDepartment(department) {
    selectedDepartment.value = department;

    console.log('Rayonnage sélectionné :', department);
}
</script>

<template>
    <main>
        <h1>Trouvez le magasin qui vous correspond</h1>

        <p>
            Choisissez un magasin pour voir ses produits disponibles.
        </p>

        <ul class="store-selector">
            <li
                v-for="store in stores"
                :key="store.id"
                @click="selectStore(store)"
                :class="{ active: selectedStore && selectedStore.id === store.id }"
            >
                {{ store.name }}
            </li>
        </ul>

        <div
            v-if="selectedStore"
            class="store-content"
        >
            <aside class="department-wrapper">

                <p v-if="!selectedStore.departments?.length">
                    Aucun rayonnage disponible.
                </p>

                <ul
                    v-else
                    class="department-selector"
                >
                    <li
                        v-for="department in selectedStore.departments"
                        :key="department.id"
                        @click="selectDepartment(department)"
                        :class="{ active: selectedDepartment && selectedDepartment.id === department.id }"
                    >
                        {{ department.name }}
                    </li>
                </ul>

            </aside>

            <section class="products-container">

                <p v-if="!selectedDepartment">
                    Aucun rayonnage sélectionné. Sélectionnez-en un pour consulter les articles.
                </p>

                <p v-else-if="!selectedDepartment.products?.length">
                    Aucun article disponible dans ce rayonnage.
                </p>

                <div
                    v-else
                    class="products-grid"
                >
                    <!-- Boucle des cards produits -->
                </div>

            </section>
        </div>

        <p
            v-else
            class="store-empty-state"
        >
            Sélectionnez un magasin pour commencer.
        </p>

    </main>
</template>