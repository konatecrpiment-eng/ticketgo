# TicketGo

Application web de **gestion des billets de transport depuis chez soi** : rechercher un trajet, réserver, payer par Mobile Money (simulé) et obtenir son billet sans se déplacer à la gare.

Projet du TD « Projet Web » — Fondamentaux du Web, MIAGE 2.

## Fonctionnalités (MVP)

- Inscription, connexion, déconnexion
- Recherche de trajets (départ, arrivée, date) avec prix, horaires et places restantes
- Réservation de 1 à 5 passagers, sièges attribués automatiquement, sans surréservation
- Paiement Mobile Money **simulé** (Orange Money, MTN MoMo, Moov Money, Wave)
- Billet avec code de référence, imprimable
- Historique « Mes billets » et annulation jusqu'à 2 h avant le départ

## Prérequis

- PHP 8.1 ou plus avec les extensions `pdo` et `pdo_sqlite`
- (optionnel) Composer, pour le linter

Vérification : `php -v` et `php -m | grep -i sqlite`

## Installation et lancement

```bash
git clone <url-du-depot> ticketgo
cd ticketgo

cp .env.example .env          # configuration locale
php database/init.php         # crée la base + données de démonstration
php -S localhost:8000 -t public
```

Ouvrir <http://localhost:8000>. Compte de démonstration : `demo@ticketgo.test` / `demo1234`.

Pour repartir d'une base vierge : `php database/init.php --fresh`.

## Tests et qualité

```bash
php tests/run.php             # 18 tests (utilisateurs, réservation, annulation)
composer install && composer lint   # PSR-12 via PHP_CodeSniffer
```

## Structure du projet

```
ticketgo/
├── public/               Seule racine web
│   ├── index.php         Point d'entrée (routage par ?page=)
│   └── assets/           CSS et JavaScript
├── src/
│   ├── bootstrap.php     Session, .env, chargement des classes
│   ├── helpers.php       Fonctions utilitaires (échappement, CSRF, rendu…)
│   ├── Database.php      Connexion PDO
│   ├── BookingService.php  Logique de réservation / annulation
│   ├── UserService.php   Inscription et authentification
│   └── pages/            Un fichier par page (contrôleurs)
├── templates/            Vues HTML
├── database/
│   ├── schema.sql        Schéma de la base
│   └── init.php          Initialisation + données de démonstration
├── tests/run.php         Tests automatiques
├── docs/                 Livrables de conception (voir ci-dessous)
├── .env.example          Modèle de configuration
├── phpcs.xml             Règles du linter (PSR-12)
└── composer.json
```

## Configuration (`.env`)

| Variable | Rôle | Valeur par défaut |
|---|---|---|
| `APP_NAME` | Nom affiché | TicketGo |
| `APP_ENV` | `development` affiche les détails d'erreur | development |
| `DB_DSN` | Connexion PDO | `sqlite:storage/ticketgo.sqlite` |
| `CANCEL_LIMIT_HOURS` | Délai minimal avant départ pour annuler | 2 |

## Sécurité

Requêtes préparées PDO, mots de passe hachés (`password_hash`), jeton CSRF sur tous les formulaires POST, échappement de toutes les sorties HTML, session régénérée à la connexion, accès aux billets limité à leur propriétaire.

## Documentation du projet (`docs/`)

| Livrable | Fichier |
|---|---|
| Note de cadrage | [docs/01-note-de-cadrage.md](docs/01-note-de-cadrage.md) |
| Compte-rendu de Kick-Off | [docs/02-compte-rendu-kickoff.md](docs/02-compte-rendu-kickoff.md) |
| Parcours d'onboarding | [docs/03-parcours-onboarding.md](docs/03-parcours-onboarding.md) |
| Wireframes basse fidélité | [docs/04-wireframes.html](docs/04-wireframes.html) |
| ADR 001 — PHP natif + PDO | [docs/adr/001-stack-php-natif-pdo.md](docs/adr/001-stack-php-natif-pdo.md) |
| ADR 002 — Base relationnelle SQLite | [docs/adr/002-base-relationnelle-sqlite.md](docs/adr/002-base-relationnelle-sqlite.md) |

## Équipe

KONATÉ Brahima et _[noms des autres membres]_ — MIAGE 2, Université Polytechnique de Bingerville.

## Limites connues

- Le paiement est simulé : aucun agrégateur Mobile Money n'est branché.
- Pas d'espace administrateur : les trajets sont créés par `database/init.php`.
