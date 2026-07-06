<script setup>
const order = {
    id: '20260704-00001',
    date: '04/07/2026',
    store: 'GDI Liège',
    items: [
        {
            id: 1,
            name: 'Marteau',
            quantity: 2,
            total: '25,00 €',
        },
        {
            id: 2,
            name: 'Perceuse',
            quantity: 1,
            total: '89,90 €',
        },
        {
            id: 3,
            name: 'Boîte de vis inox',
            quantity: 3,
            total: '7,50 €',
        },
    ],
    total: '122,40 €',
};
</script>

<template>
    <main class="order-detail">
        <h1>Commande n°{{ order.id }}</h1>

        <p>
            <strong>Date :</strong>
            {{ order.date }}
        </p>

        <p>
            <strong>Magasin :</strong>
            {{ order.store }}
        </p>

        <table class="order-detail__table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Total article</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="item in order.items"
                    :key="item.id"
                >
                    <td>{{ item.name }}</td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.total }}</td>
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="2">
                        Total de la commande
                    </th>

                    <th>
                        {{ order.total }}
                    </th>
                </tr>
            </tfoot>
        </table>
    </main>
</template>