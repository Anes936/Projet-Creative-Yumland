# Creative-Yumland

Site web d'une **boulangerie en ligne** — application multi-utilisateurs développée dans le cadre du projet du module de cours d'Informatique 4 (HTML/CSS, PHP, JavaScript).

Le site gère toute la chaîne d'une commande, du choix des produits jusqu'à la livraison, avec quatre profils d'utilisateurs : **client**, **boulanger (restaurateur)**, **livreur** et **administrateur**.

---

## Prérequis

- **PHP** installé

Aucune base de données à installer : toutes les données sont stockées en fichiers **JSON** dans le dossier `Data/`.

---

## Lancer le site en local

### Option 1 — Serveur PHP intégré (le plus simple)

Depuis le dossier du projet :

```bash
php -S localhost:8000
```

Puis ouvrir dans le navigateur :

```
http://localhost:8000/Accueil.php
```

### Option 2 — WAMP / XAMPP

1. Placer le dossier du projet dans `www/` (WAMP) ou `htdocs/` (XAMPP).
2. Démarrer Apache.
3. Ouvrir : `http://localhost/Projet-Creative-Yumland/Accueil.php`

> La page d'entrée du site est **`Accueil.php`**.

---

## Comptes de test

Des comptes de démonstration sont déjà enregistrés pour tester le site sans passer par l'inscription.
La connexion se fait avec l'**identifiant OU l'email**, accompagné du mot de passe.

| Rôle | Identifiant | Email | Mot de passe |
|------|-------------|-------|--------------|
| Client | `Utilisateur` | `utilisateur@gmail.com` | `Azert93` |
| Restaurateur | `Restaurateur` | `restaurateur@gmail.com` | `Azert93` |
| Administrateur | `Admin` | `admin@gmail.com` | `Azert93` |
| Livreur | `Livreur` | `livreur@gmail.com` | `Azert93` |

D'autres comptes clients sont disponibles pour simuler plusieurs utilisateurs : `client1`, `client2`, `client3`, `client4` (tous avec le mot de passe `Azert93`).

> Ce sont des comptes de démonstration. Les mots de passe réels sont stockés **hachés (bcrypt)** dans les fichiers.

---

## Fonctionnalités principales

- **Client** : parcours de la carte (viennoiseries, pâtisseries, tartes, cookies) avec recherche, filtres (catégorie, goût) et tri (prix, plus commandés) ; panier avec quantités et « Box » ; paiement via l'API **CYBank** ; historique des commandes ; modification du profil ; notation après livraison.
- **Boulanger (restaurateur)** : suivi des commandes et changement de statut (en préparation → prête → assignée à un livreur).
- **Livreur** : page de livraison et validation des courses effectuées.
- **Administrateur** : gestion des utilisateurs, blocage / déblocage des comptes.
- **Transverses** : mode clair / sombre mémorisé par cookie, validation des formulaires côté client, recherche avec suggestions.

---

## Structure du projet

```
.
├── Accueil.php, Carte.php, Panier.php, Connexion.php,
│   Inscription.php, Profil.php, Commandes.php,
│   CommandeRestaurateur.php, Livraison.php, Avis.php,
│   Administrateur.php, Paiement.php, ...   → les vues (pages)
├── Traitements/   → logique côté serveur (sécurité, inscription, connexion, panier, paiement…)
├── Data/          → données en JSON (utilisateurs, plats, menus, commandes, avis)
├── JS/            → scripts client (theme.js, recherche.js, validation.js, compteur.js, carte.js)
├── images/        → visuels du site
├── Commun.css     → styles partagés (+ une feuille CSS par page)
```

---

## Sécurité

- Mots de passe **hachés avec bcrypt** (`password_hash`), jamais stockés en clair.
- Contrôle des accès selon le rôle de l'utilisateur connecté (sessions + `Traitements/securite.php`).
- Échappement des données affichées avec `htmlspecialchars` pour éviter l'injection de code.

---

## Auteur

**Oualid Anes** — (MI-3), CY Tech — 2025-2026
