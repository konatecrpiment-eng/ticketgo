# Note de cadrage — TicketGo

**Projet :** TD Projet Web — Fondamentaux du Web, MIAGE 2
**Version :** 1.0 — 12/11/2026
**Équipe :** groupe de 3 à 4 étudiants (voir compte-rendu de kick-off)

## 1. Le besoin

Aujourd'hui, pour voyager en bus entre deux villes de Côte d'Ivoire, il faut souvent **se rendre physiquement à la gare** pour acheter son billet : trajet jusqu'à la gare, file d'attente, risque de ne plus trouver de place, horaires peu lisibles, paiement en espèces.

**Problème à résoudre :** permettre à un voyageur de consulter les départs, réserver sa place, payer et obtenir son billet **depuis chez lui**, sans se déplacer à la gare.

## 2. La cible

| Cible | Besoin principal |
|---|---|
| Voyageurs occasionnels (étudiants, familles) | Réserver à l'avance, connaître le prix, être sûr d'avoir une place |
| Travailleurs en déplacement régulier | Gagner du temps, retrouver ses billets facilement |
| Compagnies de transport (cible secondaire) | Moins de files d'attente, meilleur remplissage des bus |

Contraintes de contexte : usage majoritaire sur **smartphone**, connexion parfois lente (pages légères), paiement par **Mobile Money** (Orange Money, MTN MoMo, Moov Money, Wave).

## 3. Proposition de valeur

> « Réservez votre billet de bus en 2 minutes depuis votre téléphone, payez par Mobile Money, présentez simplement votre code au chauffeur. »

## 4. Fonctionnalités clés (MVP)

| # | Fonctionnalité | Priorité |
|---|---|---|
| F1 | Créer un compte / se connecter / se déconnecter | Must |
| F2 | Rechercher un trajet (départ, arrivée, date) | Must |
| F3 | Consulter prix, horaires, compagnie et places restantes | Must |
| F4 | Réserver de 1 à 5 passagers avec attribution automatique des sièges | Must |
| F5 | Payer par Mobile Money (**simulé** dans le MVP) | Must |
| F6 | Obtenir un billet avec code de référence, imprimable | Must |
| F7 | Consulter l'historique « Mes billets » | Must |
| F8 | Annuler un billet jusqu'à 2 h avant le départ (places libérées) | Should |

**Hors MVP (évolutions possibles) :** vrai paiement via agrégateur, QR code scanné par le contrôleur, espace compagnie/administrateur, notifications SMS, choix du siège sur plan, aller-retour, avis voyageurs.

## 5. Critères de succès

- Un nouvel utilisateur réserve un billet en **moins de 2 minutes** (inscription incluse).
- Aucune **surréservation** possible (jamais plus de passagers que de places).
- Toutes les fonctionnalités Must (F1 à F7) fonctionnent de bout en bout.
- Le projet s'installe en moins de 5 minutes à partir du README.

## 6. Risques

| Risque | Impact | Parade |
|---|---|---|
| Paiement réel impossible dans le cadre du TD | Moyen | Paiement simulé clairement indiqué à l'utilisateur ; architecture prête pour brancher un agrégateur |
| Réservations simultanées sur la dernière place | Élevé | Transaction SQL verrouillée + vérification des places à l'enregistrement |
| Retard d'un membre | Moyen | Découpage en tâches courtes, point d'avancement hebdomadaire |
| Données de trajets irréalistes | Faible | Jeu de données de démonstration généré par script |
