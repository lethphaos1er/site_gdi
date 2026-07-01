# Architecture CSS

## Objectif

Le CSS du projet est découpé afin de faciliter sa maintenance, limiter les doublons et rendre chaque élément facile à retrouver.

---

# Philosophie

Le projet est développé selon une approche **Mobile First**.

Les styles présents dans les dossiers principaux correspondent au rendu mobile.

Les adaptations pour les résolutions supérieures sont placées dans le dossier `responsive`.

```text
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

Contient les variables CSS globales utilisées dans l'ensemble du projet.

Exemples :

- couleurs
- tailles
- polices
- espacements
- z-index
- ombres
- transitions

Aucune valeur ne doit être codée en dur si une variable existe.

---

## base/

Contient les styles HTML génériques.

Exemples :

- reset
- typographie
- styles des balises HTML

---

## layout/

Contient l'organisation générale du site.

Exemples :

- header
- footer
- menu des catégories
- structure principale

---

## components/

Contient les composants réutilisables.

Exemples :

- boutons
- cartes produits
- sélecteur de quantité
- modales

Chaque composant possède son propre fichier CSS.

Les composants doivent rester indépendants des pages qui les utilisent.

---

## pages/

Contient uniquement les styles spécifiques à une page.

Une règle placée ici ne doit pas être utilisée par une autre page.

Les composants réutilisables ne doivent jamais être définis dans ce dossier.

---

## utilities/

Contient les classes utilitaires.

Exemples :

- truncate
- helpers

---

## responsive/

Contient uniquement les adaptations Responsive.

Seules les règles nécessitant une adaptation selon la résolution doivent être présentes ici.

Aucun doublon avec le CSS principal.

```text
responsive/
│
├── tablet/
└── desktop/
```

---

# Fichiers d'import

Chaque dossier principal possède un fichier CSS portant le même nom que le dossier.

Ces fichiers ne contiennent **aucun style**.

Leur unique rôle est d'importer tous les fichiers CSS du dossier afin de centraliser les imports.

Exemple :

```text
components/
│
├── buttons.css
├── cards.css
├── forms.css
├── tables.css
└── components.css
```

`components.css` contient uniquement :

```css
@import "./buttons.css";
@import "./cards.css";
@import "./forms.css";
@import "./tables.css";
```

Le même principe est utilisé pour :

- `root/root.css`
- `base/base.css`
- `layout/layout.css`
- `components/components.css`
- `pages/pages.css`
- `utilities/utilities.css`
- `responsive/tablet/tablet.css`
- `responsive/desktop/desktop.css`

Ainsi, le fichier `app.css` reste simple :

```css
@import "./root/root.css";
@import "./base/base.css";
@import "./layout/layout.css";
@import "./components/components.css";
@import "./pages/pages.css";
@import "./utilities/utilities.css";
@import "./responsive/tablet/tablet.css";
@import "./responsive/desktop/desktop.css";
```

---

# Conventions

- Développement **Mobile First**.
- Un composant = un fichier CSS.
- Une page = un fichier CSS.
- Une responsabilité par fichier.
- Aucun doublon volontaire.
- Les composants doivent rester génériques.
- Les pages ne doivent contenir que leurs propres exceptions.
- Les fichiers Responsive ne contiennent que les différences par rapport au CSS principal.

---

# Règles importantes

- Les couleurs ne doivent jamais être écrites directement dans les fichiers CSS.
- Utiliser les variables définies dans `root/`.
- Les tailles, espacements, ombres et arrondis doivent également passer par des variables lorsque cela est possible.
- Les composants doivent rester génériques.
- Les pages ne doivent contenir que des exceptions propres à leur fonctionnement.
- Les noms de classes sont écrits en anglais.
- Les commentaires sont rédigés en français.
- Les variables CSS sont nommées selon leur fonction (`--color-primary`) et non selon leur couleur (`--blue`).

---

# Ordre des imports

L'ordre des imports est important.

1. root
2. base
3. layout
4. components
5. pages
6. utilities
7. responsive/tablet
8. responsive/desktop

---

# Validation

Après une modification CSS importante :

1. Vérifier que le style est placé dans le bon dossier.
2. Vérifier qu'aucun doublon n'a été introduit.
3. Exécuter :

```bash
npm run build
```

4. Corriger les éventuels avertissements avant de poursuivre.