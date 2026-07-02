# Store Selection

## Objectif

La fonctionnalité **Store Selection** permet à l'utilisateur de :

1. Choisir un magasin.
2. Choisir un rayonnage.
3. Consulter les produits disponibles.
4. Sélectionner une quantité.
5. Ajouter un produit au panier (à venir).

Cette fonctionnalité est actuellement basée sur une fake database (`stores.js`) qui sera remplacée plus tard par les données provenant de Laravel.

---

# Architecture

La page suit une architecture où les **Pages** ne font que de l'orchestration.

Les composants sont responsables de leur propre affichage.

Les fonctions utilitaires sont placées dans `tools`.

```
Page
│
├── StoreHeader
├── StoreSelector
└── StoreContent
      │
      ├── ProductCard
      │      │
      │      └── ProductQuantity
      │
      └── ...
```

---

# Arborescence

```
resources
└── js
    ├── Components
    │   └── Front
    │       └── StoreSelection
    │           ├── ProductCard.vue
    │           ├── ProductQuantity.vue
    │           ├── StoreContent.vue
    │           ├── StoreHeader.vue
    │           ├── StoreSelector.vue
    │           └── storeSelection.js
    │
    ├── Pages
    │   └── Front
    │       └── StoreSelection.vue
    │
    ├── data
    │   └── stores.js
    │
    └── tools
        ├── truncate.js
        └── tools.js

resources
└── css
    ├── components
    │   ├── product-card.css
    │   ├── product-quantity.css
    │   └── components.css
    │
    └── pages
        ├── store-selection.css
        └── pages.css
```

---

# Responsabilités

## StoreSelection.vue

Responsabilités :

- importe la fake DB ;
- gère le magasin sélectionné ;
- gère le rayonnage sélectionné ;
- transmet les données aux composants.

Cette page ne contient quasiment aucun HTML.

---

## StoreHeader.vue

Responsable de l'en-tête.

Contient :

- le H1 ;
- le texte d'introduction.

---

## StoreSelector.vue

Responsable de la sélection du magasin.

Émet :

```
select-store
```

---

## StoreContent.vue

Responsable de :

- l'affichage des rayonnages ;
- l'affichage des produits ;
- la communication avec ProductCard.

Émet :

```
select-department
```

---

## ProductCard.vue

Responsable de l'affichage d'un produit.

Affiche :

- image ;
- nom ;
- prix ;
- stock ;
- description ;
- bouton "En savoir plus / Voir moins" ;
- ProductQuantity.

Ne contient aucune logique de panier.

---

## ProductQuantity.vue

Responsable uniquement de la quantité.

Affiche :

```
-
Quantité
+
Ajouter au panier
```

Règles :

- minimum = 1 ;
- maximum = stock ;
- impossible d'ajouter si stock = 0 ;
- impossible de dépasser le stock ;
- possibilité de saisir la quantité au clavier.

Émet :

```
add-product
```

---

# Fake Database

```
stores
└── departments
    └── products
```

Chaque produit contient actuellement :

- id
- name
- image
- description
- price
- stock

Cette structure sera remplacée ultérieurement par Laravel.

---

# Tools

## truncate()

```
truncate(text, limit)
```

Fonction responsable de :

- limiter une description ;
- couper au dernier mot complet ;
- ajouter "…" lorsque le texte est tronqué.

La fonction ne contient aucune logique d'affichage.

---

## isTruncated()

```
isTruncated(text, limit)
```

Retourne :

- true
- false

Permet d'afficher ou non le bouton :

```
En savoir plus
```

---

# Card produit

Disposition mobile validée

```
┌──────────────────────────────────────────────┐
│ IMAGE │ Nom                                 │
│       │ Prix                                │
│       │ En stock                            │
│       │──────────────────────────────────── │
│       │ Description + allergènes            │
│       │ Voir plus / Voir moins              │
│       │──────────────────────────────────── │
│       │                                     │
│       │      [-] [ 1 ] [+]                  │
│       │   🛒 Ajouter au panier              │
└──────────────────────────────────────────────┘
```

Le bloc quantité est placé sous la description afin de favoriser une utilisation confortable sur mobile.

---

# Choix d'architecture

Les principes retenus pour ce projet sont :

- Les pages orchestrent les composants.
- Les composants sont regroupés par fonctionnalité.
- Chaque fonctionnalité possède un fichier d'export (`storeSelection.js`).
- Le CSS est organisé selon la même logique (`components.css`, `pages.css`).
- Les outils sont centralisés dans `tools`.
- Les composants enfants ne connaissent jamais le panier ; ils communiquent uniquement via `props` et `emit`.
- Les fonctions utilitaires restent pures et ne contiennent aucune logique d'interface.