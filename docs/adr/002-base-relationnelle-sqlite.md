# ADR 002 — Choisir une base relationnelle SQL, avec SQLite pour le MVP

- **Statut :** Accepté
- **Date :** 12/11/2026
- **Décideurs :** équipe TicketGo

## Contexte

Les données de TicketGo sont fortement liées entre elles : un utilisateur a plusieurs réservations, une réservation concerne un trajet et plusieurs passagers, chaque siège ne peut être attribué qu'une fois. Une règle est critique : **ne jamais vendre plus de places que la capacité du bus**, même si deux personnes réservent en même temps.

## Options envisagées

| Option | Avantages | Inconvénients |
|---|---|---|
| **A. SQLite** (fichier, via PDO) | Zéro installation, un seul fichier, transactions ACID, contraintes et clés étrangères, idéal pour un TD et pour les tests (mode mémoire) | Une seule écriture à la fois, pas adapté à une forte charge |
| B. MySQL / MariaDB | Standard de production, bonne montée en charge | Serveur à installer et configurer pour chaque membre, plus de friction au démarrage |
| C. Base NoSQL (MongoDB) | Schéma souple | Relations et contrôle de capacité plus difficiles à garantir ; pas de besoin de souplesse ici |

## Décision

Nous choisissons **une base relationnelle SQL**, avec **SQLite pour le MVP**. Tout l'accès passe par **PDO**, la connexion est paramétrée par la variable `DB_DSN` du fichier `.env`.

La cohérence est garantie ainsi :
- clés étrangères et contraintes `CHECK` (prix > 0, 1 à 5 passagers, statut limité à `paid` / `cancelled`) ;
- la réservation s'exécute dans une **transaction `BEGIN IMMEDIATE`** : les places restantes sont recalculées et les sièges attribués avant validation, ce qui empêche la surréservation ;
- les places restantes sont **calculées** à partir des réservations payées (pas de compteur à synchroniser) : annuler un billet libère automatiquement ses places.

## Conséquences

- **Positives :** installation en une commande (`php database/init.php`), tests automatiques rapides sur base en mémoire, données toujours cohérentes.
- **Négatives :** SQLite ne convient pas à un déploiement avec beaucoup de réservations simultanées.
- **Évolution :** pour la production, passer à MySQL ou PostgreSQL en changeant `DB_DSN` et en adaptant les quelques types propres à SQLite dans `database/schema.sql` (`AUTOINCREMENT`, `datetime('now')`) ; la logique de transaction se conserve.
