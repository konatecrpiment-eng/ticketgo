# Compte-rendu de Kick-Off — TicketGo

**Date :** 12/11/2026 · **Durée :** 1 h · **Lieu :** à définir (présentiel ou visio)
**Participants :** KONATÉ Brahima, _[Membre 2]_, _[Membre 3]_, _[Membre 4 — facultatif]_

> À compléter avec les noms réels des membres du groupe avant le rendu.

## 1. Objectif de la réunion

Valider le besoin (billets de transport achetés depuis chez soi), le périmètre du MVP et se répartir le travail.

## 2. Décisions prises

- Application web **PHP natif + PDO + SQLite**, sans framework (voir ADR 001 et 002).
- Dépôt Git unique, **branche `main` protégée**, une branche par fonctionnalité (`feature/...`), fusion après relecture d'un autre membre.
- Paiement Mobile Money **simulé** pour le MVP.
- Point d'avancement chaque **lundi** (15 min) + canal de discussion de groupe.

## 3. Rôles (qui fait quoi)

| Membre | Rôle principal | Responsabilités |
|---|---|---|
| Membre 1 — KONATÉ Brahima | Chef de projet & back-end | Planning, dépôt Git, base de données, logique de réservation, ADR |
| Membre 2 | Back-end & tests | Authentification, annulation, tests, sécurité (CSRF, mots de passe) |
| Membre 3 | UX/UI | Parcours d'onboarding, wireframes, maquettes Figma, CSS |
| Membre 4 (si présent) | Front-end & documentation | Pages de recherche/billet, JavaScript, README, recette finale |

Chaque membre relit au moins une contribution d'un autre membre. En groupe de 3, le membre 4 est réparti entre les rôles 3 (UX) et 2 (tests).

## 4. Planning prévisionnel

| Semaine | Contenu | Livrable |
|---|---|---|
| S1 | Cadrage, kick-off, parcours d'onboarding, wireframes basse fidélité | Note de cadrage, CR kick-off, wireframes |
| S2 | Maquettes haute fidélité (Figma), ADR, starter kit + dépôt Git | Maquettes, 2 ADR, dépôt initialisé |
| S3 | Développement : comptes, recherche, réservation, paiement simulé | Fonctionnalités F1 à F5 |
| S4 | Billet, historique, annulation, tests, recette, correction | MVP complet, README final |

## 5. Définition de « terminé »

Une fonctionnalité est terminée quand : elle fonctionne dans le navigateur, elle est testée (test automatique ou scénario manuel documenté), le code est relu et fusionné dans `main`.

## 6. Actions

| Action | Responsable | Échéance |
|---|---|---|
| Créer le dépôt GitHub/GitLab et inviter l'équipe | Membre 1 | S1 |
| Dessiner les wireframes basse fidélité | Membre 3 | S1 |
| Rédiger les 2 ADR (relecture collective) | Membre 1 | S2 |
| Mettre en place les tests de réservation | Membre 2 | S3 |
