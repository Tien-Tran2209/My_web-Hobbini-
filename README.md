# Hobbini

Plateforme e-commerce développée avec **Symfony 7** 
L’application permet aux utilisateurs de consulter des produits, gérer un panier, passer des commandes et permet à l’administrateur de gérer les produits, les commandes et le stock.

![Symfony](https://img.shields.io/badge/Symfony-7-black)
![PHP](https://img.shields.io/badge/PHP-8.5-blue)
![Tests](https://img.shields.io/badge/Tests-PHPUnit-green)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange)

---
# Présentation du Projet

Hobbini est une plateforme e-commerce développée avec **Symfony 7**  
L’application permet aux utilisateurs de :

- consulter des produits
- gérer un panier d’achat
- passer des commandes
- effectuer des paiements
- gérer leur profil utilisateur

Une interface d’administration permet également de :

- gérer les produits
- gérer les commandes
- gérer le stock
- consulter des statistiques commerciales

---

# Objectifs du Projet

Le projet a pour objectif de mettre en pratique :

- Symfony Framework
- Architecture MVC
- Doctrine ORM
- Twig
- Authentification et sécurité
- Services Symfony
- API REST
- Tests automatisés

---

# Fonctionnalités Principales

## Utilisateur

- Inscription
- Connexion / Déconnexion
- Consultation des produits
- Filtrage par catégorie
- Détail produit
- Gestion du panier
- Modifier la quantité du panier
- Passer une commande
- Paiement Cash on Delivery
- Paiement Stripe
- Historique des commandes
- Annulation de commande en attente
- Gestion du profil utilisateur

---

## Administrateur

- Ajouter un produit
- Modifier un produit
- Supprimer un produit
- Gestion du stock
- Gestion des catégories
- Liste des commandes
- Changer le statut des commandes
- Dashboard statistiques

### Statistiques Dashboard

- chiffre d’affaires
- commandes en attente
- top produits vendus
- rupture de stock
- ventes mensuelles

---

# Technologies Utilisées

| Technologie | Utilisation |
|---|---|
| PHP 8.5 | Backend |
| Symfony 7 | Framework |
| Doctrine ORM | Base de données |
| Twig | Templates |
| Bootstrap 5 | Frontend |
| MySQL | Base de données relationnelle |
| PHPUnit 13 | Tests automatisés |
| Stripe API | Paiement |
| JWT | Authentification API |
| Swagger / OpenAPI | Documentation API |
| Git & GitHub | Versioning |
| Redis | Cache NoSQL |
| MongoDB | Base NoSQL |
| Docker | Conteneurisation |
| Docker Compose | Orchestration containers |

# Architecture du Projet

```text
src/
├── Controller/
├── DataFixtures/
├── Entity/
├── Form/
├── Repository/
├── Security/
├── Service/
├── Api/
templates/
tests/
config/
public/
```

Le projet respecte l’architecture MVC :

- **Model** → Entity + Repository
- **View** → Twig
- **Controller** → Symfony Controllers

---

# Base de Données

Principales entités :

- User
- Product
- Category
- Cart
- CartItem
- Order
- OrderItem

Relations gérées avec Doctrine ORM.

---
# API REST

Le projet contient également une API REST développée avec Symfony.

## Endpoints disponibles

| Méthode | Endpoint | Description |
|---|---|---|
| GET | `/api/products` | Liste des produits |
| GET | `/api/products/{id}` | Détail d’un produit |

---

## Exemple JSON

```json
[
  {
    "id": 1,
    "name": "Nike Air Max",
    "description": "white",
    "price": 120,
    "stock": 15
  }
]
```

---

## Technologies API

- Symfony JsonResponse
- Routing API
- REST architecture
- Serialization JSON

---
# Sécurisation API JWT

L’API REST du projet est sécurisée avec JWT (JSON Web Token).

## Fonctionnalités

* authentification API sécurisée
* génération de token JWT
* accès protégé aux endpoints sensibles
* gestion des rôles utilisateurs
* sécurité stateless

---

## Authentification

### Endpoint login

```http
POST /api/login_check
```

---

## Exemple de réponse JWT

```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

---

## Utilisation du token

```http
Authorization: Bearer TOKEN
```

---

# Installation

## 1. Cloner le projet

```bash
git clone https://github.com/Tien-Tran2209/My_web-Hobbini-.git

cd My_web-Hobbini-
```

---

## 2. Installer les dépendances PHP

```bash
composer install
```

---

## 3. Installer les dépendances frontend

```bash
npm install
npm run build
```

---

## 4. Configurer le fichier `.env`

Modifier :

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/my_web?serverVersion=8.0&charset=utf8mb4"
```

---

## 5. Créer la base de données

```bash
php bin/console doctrine:database:create
```

---

## 6. Exécuter les migrations

```bash
php bin/console doctrine:migrations:migrate
```

---

## 7. Charger les données de test (optionnel)

```bash
php bin/console doctrine:fixtures:load
```

---

## 8. Lancer le serveur Symfony

```bash
symfony server:start
```

Application disponible sur :

```text
http://127.0.0.1:8000
```

---

# Comptes de Test

## Administrateur

```text
Email : admin@myweb.com
Mot de passe : admin123
```

⚠️ Compte administrateur fixe utilisé pour tester le back-office.

---

## Utilisateur

```text
Email : user@test.com
Mot de passe : user123
```

Les utilisateurs peuvent également créer un compte via l’inscription.

---

# Services Symfony Implémentés

## OrderManagerService

Gestion métier des commandes :

- changement de statut
- contrôle du stock
- mise à jour des ventes
- annulation

---

## CartService

Gestion du panier :

- ajout produit
- augmentation quantité
- diminution quantité
- suppression produit

---

## OrderCheckoutService

Transformation du panier en commande :

- validation panier
- vérification stock
- création commande
- création OrderItem
- calcul total
- paiement Stripe / COD
- génération numéro commande
- nettoyage panier

---

## DashboardService

Gestion des statistiques administrateur :

- chiffre d’affaires
- commandes en attente
- total commandes
- rupture de stock
- top ventes
- ventes mensuelles

---

## PaymentService

Gestion des paiements Stripe :

- validation paiement
- création commande
- mise à jour statut paiement
- suppression panier après paiement

---

## ProductService

Gestion des produits :

- validation prix
- création produit
- modification produit
- suppression produit

---

## ProductServiceClient

Gestion affichage produits :

- liste produits
- filtrage catégories
- pagination
- tri produits

---

## RegistrationService

Gestion inscription utilisateur :

- hash mot de passe
- rôle utilisateur
- sauvegarde utilisateur
- email de vérification

---

## StockService

Gestion du stock :

- mise à jour stock
- protection contre valeurs négatives
- persistance base de données

---
## Cache Redis

Le projet utilise Redis afin d’optimiser les performances.

### Fonctionnalités mises en cache

- liste des produits
- pagination produits
- filtrage catégories

### Avantages

* réduction des requêtes SQL
* amélioration des performances
* optimisation du temps de réponse

---

# Sécurité

Le projet utilise Symfony Security.

## Fonctionnalités de sécurité

- Authentification sécurisée
- Hashage des mots de passe
- Gestion des rôles
- Protection des routes sensibles
- Sessions sécurisées
- Remember Me
- Contrôle d’accès administrateur

---

## Rôles disponibles

| Rôle | Description |
|---|---|
| ROLE_USER | Utilisateur standard |
| ROLE_ADMIN | Administrateur |

---

## Routes protégées

```yaml
access_control:
    - { path: ^/admin, roles: ROLE_ADMIN }
```

---

# Tests Automatisés

Le projet contient des tests unitaires et fonctionnels réalisés avec PHPUnit.

---

## Tests Unitaires

### OrderManagerServiceTest

Tests :

- mise à jour commandes
- rollback stock
- contrôle stock insuffisant
- annulation commande admin

---

## Tests Fonctionnels

### SecurityControllerTest

Tests :

- accès page login
- authentification utilisateur
- accès dashboard admin
- sécurité routes admin

---

## Lancer tous les tests

```bash
php bin/phpunit
```

---

## Lancer un test spécifique

```bash
php bin/phpunit tests/Controller/SecurityControllerTest.php
```

---

# Documentation Technique

Documentation complète disponible dans :

```text
Documentation-technique.md
```
---

# Git Workflow

Principales commandes Git utilisées :

```bash
git add .
git commit -m "message"
git push origin main
```
---
# Documentation API Swagger

Le projet utilise Swagger / OpenAPI afin de documenter automatiquement les endpoints API.

## Accès Swagger UI

```text
http://localhost:8000/api/doc
```

---

## Avantages

- documentation interactive
- test des endpoints
- visualisation des routes API
- préparation intégration frontend/mobile

---
# Docker & Conteneurisation

Le projet a été conteneurisé avec Docker afin de faciliter :

- le déploiement
- la portabilité
- la reproductibilité de l’environnement
- l’isolation des services

Le projet utilise :

- Docker
- Docker Compose

## Services Docker

| Service | Description           |
| ------- | --------------------- |
| app     | Application Symfony   |
| mysql   | Base de données MySQL |
| redis   | Cache Redis           |
| mongo   | Base NoSQL MongoDB    |

---

## Lancer le projet avec Docker

```bash
docker compose up
```

Application disponible sur :

```text
http://localhost:8000
```

---
# Variables d’Environnement

Le projet utilise des variables d’environnement pour la configuration.

## Exemple `.env`

```env
APP_ENV=dev

DATABASE_URL="mysql://root:root@mysql:3306/hobbini?serverVersion=8.0"

REDIS_URL=redis://redis:6379

MONGODB_URL=mongodb://mongo:27017
```

Ces variables permettent :

- la connexion à MySQL
- l’utilisation du cache Redis
- la connexion MongoDB
- la configuration de l’environnement Symfony

---
# MongoDB

Le projet intègre également MongoDB comme base NoSQL.

MongoDB est utilisé pour préparer l’évolution du projet vers :

- logs applicatifs
- analytics
- recommandations produits
- données non relationnelles

Cette intégration permet de démontrer l’utilisation d’une architecture hybride SQL / NoSQL.

---

# Améliorations Futures

- Déploiement
- Notifications email
- Facture PDF
- Recherche avancée
- CI/CD
- Wishlist
- Système d’avis clients
- Multi-langue

---

# Auteur

Projet réalisé par :

**TRAN Thi Trieu Tien**

Projet académique Symfony 7.

---

# Conclusion

Hobbini est une application e-commerce complète développée avec Symfony 7 mettant en œuvre une architecture moderne et professionnelle.

Le projet intègre :

- Architecture MVC
- Doctrine ORM
- Twig
- Symfony Security
- API REST
- Authentification JWT
- Documentation Swagger / OpenAPI
- Paiement Stripe
- Services métier Symfony
- Tests automatisés
- Cache Redis
- Architecture SQL / NoSQL
- Docker et Docker Compose

L’application permet de gérer :

- l’authentification utilisateur
- la gestion des produits
- le panier d’achat
- les commandes
- les paiements
- l’administration du stock
- les statistiques commerciales
- les endpoints API sécurisés

Le projet a permis de renforcer les compétences en :

- développement backend avec Symfony
- architecture logicielle
- conception API REST
- sécurité web et JWT
- gestion de base de données relationnelle et NoSQL
- optimisation des performances avec Redis
- tests automatisés
- conteneurisation Docker
- préparation au déploiement d’une application professionnelle

Cette application constitue une base solide pour une évolution vers une plateforme e-commerce scalable et déployable en environnement de production.
