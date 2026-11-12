# Parcours d'onboarding — TicketGo

**Objectif :** qu'un nouvel utilisateur comprenne la valeur du service et obtienne **son premier billet en moins de 2 minutes**, sans aide.

**Principe :** on laisse l'utilisateur **chercher avant de s'inscrire**. Le compte n'est demandé qu'au moment de réserver (inscription « au bon moment »).

## Étapes clés

| # | Écran | Ce que voit / fait l'utilisateur | Message clé |
|---|---|---|---|
| 1 | Accueil | Titre « Votre billet de bus, depuis votre canapé », formulaire de recherche prérempli (Abidjan, date du jour) et 4 étapes « Comment ça marche ? » | Comprendre le service en 5 secondes |
| 2 | Résultats | Liste des départs : heures, compagnie, type de bus, prix, places restantes | Voir tout de suite prix et disponibilité |
| 3 | Réservation → connexion | Clic sur « Réserver » : si non connecté, redirection vers la connexion avec le message « Connectez-vous pour continuer » | Pas de perte de contexte |
| 4 | Création de compte | 4 champs seulement : nom, e-mail, téléphone, mot de passe. Après inscription, accueil avec « Bienvenue sur TicketGo ! » | Inscription courte |
| 5 | Réservation | Nom du passager 1 prérempli avec celui du compte, numéro de paiement prérempli, total mis à jour en direct | Peu de saisie |
| 6 | Paiement | Choix Orange Money / MTN MoMo / Moov Money / Wave, mention « paiement simulé » | Confiance et clarté |
| 7 | Billet | Message de succès, billet avec code à présenter au chauffeur, bouton imprimer | Moment de satisfaction (« aha moment ») |
| 8 | Mes billets | Accès à l'historique, statut Valide / Annulé / Voyage effectué | Retrouver ses billets |

## Schéma du parcours

```
Accueil ──► Résultats ──► Réserver ──► (Connexion / Inscription) ──► Passagers + Paiement ──► Billet
                                                                                              │
                                                                                      Mes billets ◄─┘
```

## Points de friction anticipés et réponses

| Friction | Réponse apportée |
|---|---|
| « Je dois créer un compte avant de voir les prix » | Recherche et prix visibles sans compte |
| « Je ne sais pas si le paiement est sûr » | Moyens de paiement connus et mention explicite du mode simulation |
| « Je perds mes infos si je me connecte » | Redirection vers la page de réservation après connexion |
| « Je suis sur mon téléphone » | Interface adaptative (une colonne sous 720 px), champs larges |
| « Je me suis trompé de billet » | Annulation possible jusqu'à 2 h avant le départ |

## Mesure du succès

- Temps entre l'arrivée sur l'accueil et l'affichage du billet : **< 2 min**.
- Nombre de clics de l'accueil au billet pour un utilisateur déjà inscrit : **4** (Rechercher → Réserver → Payer → Billet).
