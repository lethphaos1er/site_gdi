# Architecture CSS

## Objectif

Le CSS du projet est découpé afin de faciliter sa maintenance, limiter les doublons et rendre chaque élément facile à retrouver.

---

# Philosophie

Le projet est développé selon une approche **Mobile First**.

Les styles présents dans les dossiers principaux correspondent au rendu mobile.

Les adaptations pour les résolutions supérieures sont placées dans le dossier `responsive`.

```
css/
│
├── root/
├── base/
├── layout/
├── components/
├── pages/
├── utilities/
│
└── responsive/
    ├── tablet/
    └── desktop/
```

---

# Organisation des dossiers

## root/

Variables CSS globales.

Exemple :

- couleurs
- tailles
- polices
- espacements
- z-index
- ombres

---

## base/

Styles HTML génériques.

Exemple :

- reset
- typographie
- styles des balises HTML

---

## layout/

Organisation générale du site.

Exemple :

- header
- footer
- menu des catégories
- structure principale

---

## components/

Composants réutilisables.

Exemple :

- boutons
- cartes produits
- sélecteur de quantité
- modales

Chaque composant possède son propre fichier CSS.

---

## pages/

Styles spécifiques à une page.

Une règle placée ici ne doit pas être utilisée par une autre page.

---

## utilities/

Classes utilitaires.

Exemple :

- truncate
- helpers

---

## responsive/

Contient uniquement les adaptations Responsive.

Les règles présentes ici remplacent uniquement celles nécessaires.

Aucun doublon avec le CSS principal.

```
responsive/

tablet/

desktop/
```

---

# Conventions

- Mobile First.
- Un composant = un fichier CSS.
- Une page = un fichier CSS.
- Une responsabilité par fichier.
- Aucun doublon volontaire.
- Les fichiers Responsive ne contiennent que les différences par rapport au CSS principal.

---

# Imports

L'ordre des imports est important.

1. root
2. base
3. layout
4. components
5. pages
6. utilities
7. responsive/tablet
8. responsive/desktop