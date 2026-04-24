# Hobbini

Plateforme e-commerce développée avec **Symfony 7** dans le cadre d’un projet académique.
L’application permet aux utilisateurs de consulter des produits, gérer un panier, passer des commandes et permet à l’administrateur de gérer les produits, les commandes et le stock.

---

## Présentation du projet

Hobbini est une boutique en ligne moderne conçue avec l’architecture MVC de Symfony.

Le projet met en pratique :

* Symfony Framework
* Doctrine ORM
* Twig Templates
* Sécurité / Authentification
* Services Symfony
* Dashboard Administrateur
* Tests Unitaires PHPUnit

---

## Fonctionnalités principales

### Utilisateur

* Inscription
* Connexion / Déconnexion
* Consultation des produits
* Filtrage par catégorie
* Détail produit
* Gestion du panier
* Modifier quantité panier
* Passer commande
* Paiement Cash on Delivery
* Paiement Stripe
* Historique des commandes
* Annulation commande en attente
* Profil utilisateur

### Administrateur

* Ajouter un produit
* Modifier un produit
* Gestion du stock
* Liste des commandes
* Changer le statut des commandes
* Dashboard statistiques :

  * chiffre d'affaires
  * commandes en attente
  * top produits vendus
  * rupture de stock
  * ventes mensuelles

---

## Technologies utilisées

* PHP 8.2+
* Symfony 7.4
* Doctrine ORM
* Twig
* Bootstrap 5
* MySQL
* PHPUnit 13
* Stripe API

---

## Architecture du projet

```text
src/
├── Controller/
├── DataFixtures/
├── Entity/
├── Form/
├── Repository/
├── Security/
├── Service/
templates/
tests/
public/
```

Le projet respecte l’architecture MVC :

* **Model** : Entity + Repository
* **View** : Twig
* **Controller** : Symfony Controllers

---

## Base de données

Principales entités :

* User
* Product
* Category
* Cart
* CartItem
* Order
* OrderItem

Relations gérées avec Doctrine ORM.

---

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/Tien-Tran2209/My_web-Hobbini-.git

cd My_web-Hobbini-
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer `.env`

Modifier :

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/my_web?serverVersion=8.0&charset=utf8mb4"
```

### 4. Créer la base de données

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. Lancer le serveur

```bash
symfony server:start
```

---

## Comptes de test

### Administrateur

```text
Email : admin@myweb.com
Mot de passe : admin123
```
> ⚠️ Compte administrateur fixe utilisé pour tester le back-office.  
> Il ne peut pas être créé via le formulaire d’inscription.

### Utilisateur

```text
Email : Nii@gmail.com
Mot de passe : 123456789
```
Les utilisateurs peuvent créer un compte librement via la page d’inscription.

> ℹ️ Il n’existe pas de compte utilisateur fixe dans le système de test.

---

## Services Symfony implémentés

### OrderManagerService

Gestion métier des commandes :

* changement de statut
* contrôle du stock
* mise à jour des ventes
* annulation

### CartService

Gestion du panier :

* ajout produit
* augmentation quantité
* diminution quantité
* suppression

### OrderCheckoutService

Transformation du panier en commande :

* validation du panier
* vérification du stock avant commande
* création de la commande (Order)
* création des items de commande (OrderItem)
* calcul du total
* gestion du paiement (COD / Stripe)
* génération numéro de commande utilisateur
* nettoyage du panier après validation

### DashboardService

Gestion des statistiques du tableau de bord admin :

* calcul du chiffre d’affaires total
* nombre de commandes en attente
* nombre total de commandes
* produits en rupture de stock
* produits les plus vendus
* dernières commandes
* ventes mensuelles

### PaymentService

Gestion du paiement Stripe (succès de paiement) :

* vérification du panier utilisateur
* création de la commande (Order)
* définition du statut de paiement (paid)
* définition du statut de commande (Validé)
* création des items de commande (OrderItem)
* calcul du total de la commande
* génération du numéro de commande utilisateur
* suppression du panier après paiement réussi

### ProductService

Gestion des produits :

* validation du prix (interdiction de valeur négative)
* création et mise à jour des produits
* suppression des produits
* persistance des données en base de données

### ProductServiceClient

Gestion de l’affichage des produits côté client :

* récupération de la liste des produits
* filtrage par catégorie
* récupération des catégories disponibles
* tri des produits (du plus récent au plus ancien)
* pagination des résultats (KnpPaginator)
* gestion de la catégorie sélectionnée

### RegistrationService

Gestion de l’inscription utilisateur :

* hash du mot de passe
* attribution du rôle par défaut (ROLE_USER)
* création et sauvegarde de l’utilisateur
* envoi de l’email de vérification
* génération du lien de confirmation email

### StockService

Gestion du stock produit :

* mise à jour du stock produit
* contrôle pour éviter les valeurs négatives
* sécurisation de la quantité (minimum 0)
* persistance des changements en base de données

---

## Tests

Tests unitaires réalisés avec PHPUnit.

Lancer les tests :

```bash
php bin/phpunit
```

Exemples testés :

* mise à jour commande expédiée
* rollback stock
* stock insuffisant
* annulation admin

---

## Sécurité

Gestion des rôles :

* ROLE_USER → browse products, cart, checkout, orders
* ROLE_ADMIN → access dashboard, manage products & orders & stocks  

Protection des routes sensibles :

* Dashboard admin
* Gestion commandes
* Gestion produits

---

## Améliorations futures

* API REST
* Notifications email
* Facture PDF
* Recherche avancée
* Déploiement en production
* Tests fonctionnels

---

## Auteur

Projet réalisé par **TRAN Thi Trieu Tien** dans le cadre d’un projet Symfony.

---

## Conclusion

Hobbini est une application complète mettant en œuvre les compétences demandées :

* Symfony
* Twig
* Doctrine
* Services
* Sécurité
* Tests
* Architecture MVC
