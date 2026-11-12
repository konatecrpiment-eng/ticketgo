# ADR 001 — Choisir PHP natif avec PDO (sans framework) pour le front et le back-end

- **Statut :** Accepté
- **Date :** 12/11/2026
- **Décideurs :** équipe TicketGo

## Contexte

Le projet doit livrer en quelques semaines une application web complète (front connecté au back et à la base). L'équipe suit le cours Fondamentaux du Web et connaît surtout HTML, CSS, JavaScript de base et PHP. Le besoin est une application classique de pages (recherche, formulaire, billet) avec comptes utilisateurs et paiement simulé. Les utilisateurs visés sont surtout sur smartphone avec une connexion parfois lente.

## Options envisagées

| Option | Avantages | Inconvénients |
|---|---|---|
| **A. PHP natif + PDO + HTML/CSS/JS léger** | Connu de l'équipe, aucune installation lourde (`php -S`), pages rendues côté serveur donc légères, hébergement partout | Il faut écrire soi-même routage, validation et protections (CSRF) |
| B. Framework PHP (Laravel, Symfony) | Sécurité, routage et ORM fournis | Courbe d'apprentissage, structure imposée, dépendances lourdes, trop pour un MVP de 4 semaines |
| C. Front SPA (React/Vue) + API Node.js | Interface très dynamique | Deux projets à maintenir, plus de code, plus lourd pour des connexions lentes, compétences à acquérir |

## Décision

Nous choisissons **l'option A** : PHP 8.1+ natif, organisé en couches simples (`src/` pour la logique, `templates/` pour l'affichage, `public/` comme seule racine web), accès aux données via **PDO et requêtes préparées**.

## Conséquences

- **Positives :** démarrage immédiat, code compréhensible par toute l'équipe, pages rapides sur mobile, objectif pédagogique atteint (comprendre ce que font les frameworks).
- **Négatives :** nous devons implémenter nous-mêmes les protections : jeton CSRF sur les formulaires, échappement systématique des sorties (`e()`), `password_hash`, régénération de session à la connexion. Ces points sont couverts et testés.
- **Mitigation :** la logique métier est isolée dans des classes (`BookingService`, `UserService`) indépendantes de l'affichage, ce qui permettrait une migration vers un framework ou une API plus tard.
