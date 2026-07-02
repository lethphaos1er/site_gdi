# Store Selection

## Objectif

Permettre à un utilisateur de :

1. Choisir un magasin.
2. Choisir un rayon.
3. Consulter les produits.
4. Ajouter un produit au panier.

---

# Architecture

StoreSelection.vue

- Orchestrateur de la page.
- Gère l'état.
- Gère les événements.
- Assemble les composants.

---

## Composants

### StoreHeader.vue

Responsable de l'en-tête de la page.

### StoreSelector.vue

Responsable de la sélection du magasin.

### StoreContent.vue

Responsable :

- de l'affichage des rayons ;
- de la liste des produits.

### ProductCard.vue

Responsable de l'affichage d'un produit.

Contient :

- image ;
- nom ;
- prix ;
- stock ;
- description ;
- bouton Voir plus (à venir) ;
- ProductQuantity.

### ProductQuantity.vue

Responsable uniquement de la quantité.

Règles métier :

- quantité minimale : 1 ;
- quantité maximale : stock ;
- bouton "-" désactivé à 1 ;
- bouton "+" désactivé au stock maximum ;
- bouton "Ajouter au panier" désactivé si stock = 0.

Émet :

- add-product.

---

# Fake Data

stores
└── departments
    └── products

---

# Card produit

Disposition mobile :

IMAGE | Nom
      | Prix
      | En stock

-------------------------

Description + allergènes

Voir plus

        [-] [1] [+]

     🛒 Ajouter au panier

---

# Responsive

Mobile

- une seule colonne ;
- navigation des rayons sticky.

Tablette

- à définir.

Desktop

- grille 1/3 - 2/3.

---

# Évolutions prévues

- truncate automatique ;
- Voir plus ;
- allergènes ;
- panier réel ;
- promotions ;
- disponibilité dynamique via Laravel.

# Choix d'architecture

- Les pages jouent uniquement le rôle d'orchestrateur.
- Les composants sont regroupés par fonctionnalité.
- Chaque fonctionnalité possède un fichier d'export (`storeSelection.js`).
- Le CSS suit la même logique avec un fichier par composant (`product-card.css`, `product-quantity.css`) et des fichiers d'entrée (`components.css`, `pages.css`).
- Les composants enfants ne connaissent jamais le panier ; ils communiquent uniquement par `props` et `emit`.