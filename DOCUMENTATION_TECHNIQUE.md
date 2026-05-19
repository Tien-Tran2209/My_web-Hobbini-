# DOCUMENTATION TECHNIQUE — HOBBINI

---

# 1. Présentation du Projet

## Nom du Projet

Hobbini

## Type de Projet

Application web e-commerce

## Description

Hobbini est une plateforme e-commerce (Montres et Chaussures) développée avec Symfony 7.

L’application permet aux utilisateurs de :

- parcourir les produits
- gérer un panier d’achat
- passer des commandes
- utiliser un système d’authentification sécurisé

Un tableau de bord administrateur est également intégré afin de gérer les produits, le stock et les commandes.

---

# 2. Objectifs

Les principaux objectifs du projet sont :

- Développer une application web moderne avec Symfony
- Appliquer l’architecture MVC
- Utiliser Doctrine ORM pour la gestion de base de données
- Implémenter l’authentification et l’autorisation
- Créer des services métier réutilisables
- Mettre en place des tests unitaires et fonctionnels
- Sécuriser l’authentification et les rôles utilisateurs
- Créer une API REST

---
# 3. Technologies Utilisées

| Technologie | Utilisation |
|---|---|
| PHP 8.5 | Langage backend |
| Symfony 7 | Framework web |
| Doctrine ORM | Gestion de base de données |
| Twig | Moteur de templates |
| Bootstrap 5 | Mise en forme frontend |
| MySQL | Base de données relationnelle |
| PHPUnit | Tests automatisés |
| Stripe API | Intégration des paiements |
| Git & GitHub | Gestion de version |

---
# 4. Architecture de l’Application

Le projet suit l’architecture MVC (Model-View-Controller).

---

## 4.1 Model

La couche Model est représentée par les entités Doctrine.

### Exemples

- User
- Product
- Order
- OrderItem
- Cart
- CartItem

### Responsabilités

- Stocker les données métier
- Représenter les tables de la base de données
- Gérer les relations entre les entités

---

## 4.2 View

La couche View utilise les templates Twig.

### Exemples

```twig
product/index.html.twig
security/login.html.twig
admin/dashboard/index.html.twig
```
### Responsabilités

- Afficher les composants de l’interface utilisateur
- Afficher les données dynamiques
- Gérer la mise en page frontend

---

## 4.3 Controller

Les contrôleurs gèrent les requêtes HTTP et les réponses.

### Examples

- ProductController
- CartController
- OrderController
- SecurityController
- DashboardController

### Responsabilités

- Gérer le routage
- Valider les requêtes
- Interagir avec les services et repositories
- Afficher les vues ou rediriger les utilisateurs

---

# 5. Conception de la Base de Données

L’application utilise **MySQL** avec **Doctrine ORM** pour gérer les données et les relations entre les entités.

---

## Tables Principales

### User

Stocke les comptes utilisateurs.

#### Champs

- `id`
- `email`
- `password`
- `roles`
- `is_verified`

---

### Product

Stocke les informations des produits.

#### Champs

- `id`
- `name`
- `description`
- `price`
- `stock`
- `sold`
- `image`

---

### Cart

Stocke le panier associé à un utilisateur.

---

### CartItem

Stocke les produits présents dans le panier.

#### Champs

- `quantity`
- relation avec `Product`
- relation avec `Cart`

---

### Order

Stocke les commandes des clients.

#### Champs

- `status`
- `payment_status`
- `payment_method`
- `total_price`
- `created_at`

---

### OrderItem

Stocke les produits liés à une commande.

#### Champs

- `quantity`
- `price`
- relation avec `Product`
- relation avec `Order`

---

# 6. Doctrine ORM

Doctrine ORM est utilisé pour gérer les opérations de base de données dans l’application.

---

## Principaux Avantages

- Gestion orientée objet des données
- Mapping des entités relationnelles
- Génération automatique des requêtes SQL
- Abstraction de la base de données
- Gestion simplifiée des migrations

---

## Exemples Utilisés dans le Projet

### Requêtes Repository

```php
$productRepo->findAll();
```

### Persistance des Données

```php
$em->persist($product);
$em->flush();
```

### Relations entre Entités

```php
#[ORM\OneToMany]
#[ORM\ManyToOne]
```

---

# 7. Système de Sécurité

Symfony Security est utilisé pour l’authentification et l’autorisation.

---

## Fonctionnalités Implémentées

- Connexion utilisateur
- Déconnexion
- Hachage des mots de passe
- Contrôle d’accès par rôles
- Protection de l’espace administrateur
- Authentification par session
- Fonctionnalité “Remember me”

---

## Rôles Utilisateurs

| Rôle | Description |
|---|---|
| ROLE_USER | Client standard |
| ROLE_ADMIN | Administrateur |

---

## Routes Protégées

```yaml
access_control:
    - { path: ^/admin, roles: ROLE_ADMIN }
```

---

# 8. Couche Services

L’application utilise les Services Symfony afin de séparer la logique métier des contrôleurs.

---

## 8.1 OrderManagerService

### Responsabilités

- Mettre à jour le statut des commandes
- Gérer le nombre de produits vendus
- Vérifier la disponibilité du stock
- Gérer l’annulation des commandes

---

### Exemple

```php
$orderService->updateStatus($order, 'Expédié');
```

---

## 8.2 DashboardService

### Responsabilités

- Générer les statistiques du dashboard
- Compter les produits
- Compter les commandes
- Compter les produits en rupture de stock

---
### Exemple

```php
$dashboardService->getStats();
```

---

## 8.3 CartService

### Responsabilités

- Ajouter des produits au panier
- Augmenter la quantité d’un produit
- Diminuer la quantité d’un produit
- Supprimer un produit du panier

---

### Exemple

```php
$cartService->add($product);
```
---

## 8.4 OrderCheckoutService

### Responsabilités

- Valider le panier utilisateur
- Vérifier la disponibilité du stock
- Créer la commande
- Créer les OrderItems
- Calculer le prix total
- Gérer le paiement Stripe ou Cash on Delivery
- Générer le numéro de commande
- Nettoyer le panier après validation

---

### Exemple

```php
$orderCheckoutService->checkout($user, 'stripe');
```
---

## 8.5 PaymentService

### Responsabilités

- Gérer les paiements Stripe
- Valider les paiements
- Créer les commandes après paiement
- Mettre à jour le statut de paiement
- Supprimer le panier après validation

---

### Exemple

```php
$paymentService->handleStripeSuccess($user);
```
---
## 8.6 ProductService

### Responsabilités

- Créer des produits
- Modifier des produits
- Supprimer des produits
- Vérifier la validité du prix
- Sauvegarder les données produit

---

### Exemple

```php
$productService->save($product);
```
---

## 8.7 ProductServiceClient

### Responsabilités

- Responsabilités
- Récupérer la liste des produits
- Filtrer les produits par catégorie
- Trier les produits
- Gérer la pagination
- Afficher les produits côté client

---

### Exemple

```php
$productServiceClient->getProductsByCategory($category);
```
---

## 8.8 RegistrationService

### Responsabilités

- Gérer l’inscription des utilisateurs
- Hasher les mots de passe
- Attribuer le rôle ROLE_USER
- Sauvegarder les utilisateurs
- Générer les emails de vérification

---

### Exemple

```php
$registrationService->register($user);
```
---
## 8.9 StockService

### Responsabilités

- Mettre à jour le stock des produits
- Éviter les valeurs négatives
- Vérifier la quantité restante
- Sauvegarder les modifications en base de données

---

### Exemple

```php
$stockService->updateStock($product, 5);
```
---

# 9. Système de Panier

---

## Fonctionnalités

- Ajouter des produits au panier
- Supprimer des produits du panier
- Augmenter la quantité
- Diminuer la quantité
- Vérification du stock

---

## Exemple de Vérification du Stock

```php
if ($item->getQuantity() >= $product->getRemaining()) {
    throw new Exception('Stock insuffisant');
}
```

---

# 10. Gestion des Commandes

---

## Fonctionnalités Client

- Passer une commande
- Annuler une commande en attente
- Consulter ses commandes personnelles

---

## Fonctionnalités Administrateur

- Voir toutes les commandes
- Modifier le statut des commandes
- Annuler des commandes
- Gérer l’état des expéditions

---

## Statuts des Commandes

- En attente
- Validé
- Expédié
- Annulé

---

# 11. Intégration des Paiements

Stripe API est intégré afin de gérer les paiements en ligne.

---

## Méthodes de Paiement

- Paiement à la livraison
- Paiement par carte via Stripe

---

## Fonctionnalités Stripe

- Création de session Checkout
- Page de paiement sécurisée
- Redirections succès / annulation

---

# 12. API REST

L’application inclut également une API REST développée avec Symfony.

Cette API permet d’exposer certaines données au format JSON afin d’être consommées par une application frontend ou un service externe.

---

## Endpoints disponibles

| Méthode | Endpoint | Description |
|---|---|---|
| GET | /api/products | Retourne la liste des produits |
| GET | /api/products/{id} | Retourne les détails d’un produit |

---

## Exemple de réponse JSON

```json
[
    {
        "id": 1,
        "name": "Nike Air Max",
        "description": "White",
        "price": 120,
        "stock": 10,
        "sold": 2
    }
]

```

## Technologies utilisées

- Symfony JsonResponse
- Routing API
- Doctrine ORM
- Repository Pattern

## Objectifs de l’API

- Fournir des données au format JSON
- Préparer une architecture backend moderne
- Permettre une future connexion avec React, Vue.js ou une application mobile
- Développer des compétences REST API

## Consommation d’API externe

Le projet consomme également l’API Stripe pour :

- la création de sessions de paiement
- la redirection sécurisée vers Stripe Checkout
- la validation des paiements en ligne

---

# 13. Tests Automatisés

Le projet inclut des tests automatisés avec PHPUnit.

---

## 13.1 Tests Unitaires

### OrderManagerServiceTest

#### Tests réalisés

- Augmentation du nombre de produits vendus
- Restauration du compteur “sold”
- Prévention des valeurs négatives
- Détection du stock insuffisant
- Vérification des annulations administrateur

---

### Commande

```bash
php bin/phpunit
```

---

## 13.2 Tests Fonctionnels

### SecurityControllerTest

#### Tests réalisés

- Accès à la page de connexion
- Système d’authentification
- Contrôle d’accès administrateur
- Accès au dashboard administrateur

---

### Commande

```bash
php bin/phpunit tests/Controller/SecurityControllerTest.php
```

---

# 14. Gestion de Version Git

Git et GitHub sont utilisés pour la gestion du code source.

---

## Commandes Principales

```bash
git add .
git commit -m "message"
git push
```

---

# 15. Structure du Projet

```text
src/
 ├── Controller/
 ├── DataFixtures/
 ├── Entity/
 ├── Repository/
 ├── Service/
 ├── Form/
 └── Security/

templates/
config/
tests/
public/
```

---

# 16. Améliorations Futures

Les améliorations possibles du projet incluent :

- Déploiement
- Vérification email
- Moteur de recherche produit
- Gestion des catégories
- Intégration des webhooks Stripe
- Conteneurisation Docker
- Pipeline CI/CD
- Système d’avis produits
- Wishlist
- Support multilingue

---

# 17. Conclusion

Hobbini démontre le développement d’une application e-commerce moderne avec Symfony en appliquant une architecture backend professionnelle et les bonnes pratiques de développement.

---

## Le projet inclut

- Architecture MVC
- Doctrine ORM
- Symfony Security
- Logique métier orientée services
- Tests automatisés
- Intégration des paiements
- API REST

---

Ce projet a permis de renforcer les compétences pratiques en :

- développement backend Symfony
- gestion de base de données
- architecture logicielle
- sécurité web
- tests automatisés
- développement API REST
