# KLASK — Application Web Événementielle

Application Symfony permettant aux élèves de découvrir des métiers lors d'un événement,
avec suivi en temps réel par les accompagnateurs.

---

## Stack technique

| Composant        | Version                       |
| ---------------- | ----------------------------- |
| PHP              | 8.4.x                         |
| Symfony          | 7.4.x                         |
| Doctrine ORM     | 3.x                           |
| EasyAdmin        | 5.x                           |
| MySQL (via WAMP) | 8.x                           |
| WAMP             | 3.x (Windows)                 |
| phpMyAdmin       | inclus avec WAMP              |
| Twig             | 3.x                           |
| AssetMapper      | 7.4.x (pas de Node.js requis) |

---

## Prérequis

- **WAMP** démarré (icône verte dans la barre des tâches)
- **Symfony CLI** installé (`symfony` disponible en ligne de commande)
- **Composer** installé
- PHP 8.2+ dans le PATH

---

## Installation

```bash
# 1. Cloner le projet
git clone <url-du-repo> klask-dev - branche dev
cd klask-dev

# 2. Installer les dépendances PHP
composer install

# 3. Installer les assets front (pas de npm nécessaire)
php bin/console importmap:install
php bin/console assets:install public

# 4. Copier et configurer le fichier d'environnement
cp .env .env.local
# Éditer .env.local : renseigner DATABASE_URL
# Exemple : DATABASE_URL="mysql://root:@127.0.0.1:3306/klask?serverVersion=8.0"
```

---

## Base de données — Reset complet

> ** Ces commandes suppriment et recréent toutes les données.**
> À faire lors de l'installation initiale ou après modification de fixtures.

### Méthode rapide (script intégré)

```bash
composer db-reset
```

Ce script exécute dans l'ordre :

1. Supprime la base si elle existe
2. La recrée
3. Applique toutes les migrations
4. Charge les fixtures

### Méthode manuelle étape par étape

```bash
# Supprimer les bases (prod + test)
php bin/console doctrine:database:drop --force --if-exists
php bin/console doctrine:database:drop --env=test --force --if-exists

# Créer les bases
php bin/console doctrine:database:create
php bin/console doctrine:database:create --env=test

# Générer une migration si le schéma a changé (optionnel si déjà à jour)
php bin/console make:migration

# Appliquer les migrations
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:migrations:migrate --env=test --no-interaction

# Charger les données de test
php bin/console doctrine:fixtures:load --no-interaction
```

### Vérifier l'état des migrations

```bash
php bin/console doctrine:migrations:status
```

---

## Comptes créés par les fixtures

| Rôle           | Email                               | Mot de passe            |
| -------------- | ----------------------------------- | ----------------------- |
| Admin          | `admin@klask.fr`                    | `AdminKlask2026!`       |
| Accompagnateur | `accompagnateur@klask.fr`           | `AccKlask2026!`         |
| Élèves de test | _(pas d'email — pseudo uniquement)_ | _(pas de mot de passe)_ |

Les élèves de test ont pour pseudos : `Renard vif`, `Lapin gris`, `Tigre calme`, `Aigle fier`, `Lynx agile`.
Ils sont tous dans le groupe **GRP0002**, qui correspond au groupe de l'accompagnateur.

---

## Lancer l'application

### Option 1 — Symfony CLI (recommandé)

Ouvrir un terminal **séparé** (CMD ou PowerShell hors Cursor) et lancer :

```bash
cd c:\wamp64\www\klask-dev
symfony server:start --port=8000 --no-tls
```

> **Important :** laisser ce terminal ouvert pendant toute la session de test.
> L'app est accessible sur **http://127.0.0.1:8000**

### Option 2 — Via WAMP / Apache

Configurer un Virtual Host Apache pointant vers `c:\wamp64\www\klask-dev\public`.
Accessible sur `http://localhost` ou un domaine local configuré.

### Nettoyer le cache si nécessaire

```bash
# Suppression manuelle (plus rapide que cache:clear)
Remove-Item -Recurse -Force var\cache\dev
```

---

## Tester l'application

### Côté étudiant (ROLE_STUDENT)

**Flux complet :**

| Étape | URL                        | Description                               |
| ----- | -------------------------- | ----------------------------------------- |
| 1     | `GET /inscription`         | Formulaire d'inscription                  |
| 2     | `POST /inscription/save`   | Soumission du formulaire                  |
| 3     | `GET /bienvenue`           | Page de bienvenue avec pseudo et avatar   |
| 4     | `GET /questionnaire`       | Choix des 6 sphères (notes 1 à 6 uniques) |
| 5     | `POST /questionnaire/save` | Soumission du questionnaire               |
| 6     | `GET /map`                 | Carte interactive avec les stands         |

**Sur le formulaire d'inscription :**

- Sélectionner un établissement (ex. _Lycée Jean-Marie Le Bris - Douarnenez_)
- Sélectionner un niveau (ex. _Première_)
- Saisir le code de groupe : **GRP0002**
- Le pseudo est généré automatiquement (animal + adjectif)

**Sur la carte :**

- Cliquer sur un pin (stand) pour voir sa description
- Molette ou pinch pour zoomer
- Clic + glisser pour se déplacer
- Toutes les 30s : vérification automatique d'un éventuel poke de l'accompagnateur

---

### Côté accompagnateur (ROLE_ACCOMPANYING)

**Flux complet :**

| Étape | URL                     | Description                                               |
| ----- | ----------------------- | --------------------------------------------------------- |
| 1     | `GET /login`            | Page de connexion                                         |
| 2     | `POST /login`           | Connexion avec email + mot de passe                       |
| 3     | `GET /instructions`     | Page d'instructions (affichée une seule fois par session) |
| 4     | `GET /instructions/map` | Valide la lecture et redirige vers la carte               |
| 5     | `GET /map`              | Carte avec sidebar groupe                                 |

**Sur la carte :**

- Ouvrir le sidebar (flèche `◀` à droite)
- Les élèves du groupe **GRP0002** apparaissent avec pseudo et score
- Rafraîchissement automatique toutes les 15 secondes
- Cliquer sur l'avatar d'un élève → confirm → poke envoyé
- L'élève concerné voit une notification « COUCOU ! » dans les 30 secondes

**API disponibles (authentification requise) :**

```
GET  /accompanying/group-scores   → JSON des élèves du groupe avec scores
POST /accompanying/poke/{id}      → Envoyer un poke à l'élève {id}
GET  /map/poke-check              → Vérifie si l'élève connecté a reçu un poke
```

---

## Structure des dossiers principaux

```
src/
  Controller/       — Controllers Symfony (carte, inscription, questionnaire, admin…)
  Entity/           — Entités Doctrine (User, Group, Activity, Sphere…)
  Repository/       — Requêtes DQL
  Service/          — Logique métier
  DTO/              — Data Transfer Objects
  DataFixtures/     — Données de test
  Security/         — Provider utilisateur + RoleSecurity enum

templates/
  map/              — Carte interactive + sidebar
  inscription/      — Formulaire d'inscription
  questionnaire/    — Choix des sphères
  instructions/     — Page d'instructions accompagnateur
  admin/            — Dashboard EasyAdmin
  bienvenue/        — Page de bienvenue post-inscription

assets/
  js/               — map.js, sidebarUserMap.js
  styles/           — CSS par page
```

---

## Qualité de code

```bash
# Analyse statique PHPStan
vendor/bin/phpstan analyse

# Formatage PHP CS Fixer
vendor/bin/php-cs-fixer fix

# Tests unitaires
php bin/phpunit
```
