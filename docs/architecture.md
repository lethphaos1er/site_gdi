# Architecture du projet

## Objectif

Application de gestion multi-magasin développée avec Laravel 13.

L'objectif est de conserver une architecture claire, évolutive et facilement maintenable.

---

## Principe général

Le projet suit une architecture orientée métier.

Le contrôleur ne contient que le traitement HTTP.

La logique métier est placée dans les Services.

Les accès aux données pourront être externalisés dans des Repositories si leur complexité le justifie.

Les DTO servent à transporter les données.

Les Enums remplacent les constantes ou chaînes de caractères.

---

## Structure

app/

Services
    Contient la logique métier.

Repositories
    Gestion de l'accès aux données lorsque nécessaire.

DTO
    Transport de données.

Enums
    États fixes de l'application.

Helpers
    Fonctions utilitaires.

Traits
    Fonctionnalités réutilisables.

Interfaces
    Contrats des Services et Repositories.