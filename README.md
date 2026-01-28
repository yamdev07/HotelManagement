# 🏨 Hotel Management System (Laravel)

Application web de **gestion hôtelière** développée avec **Laravel**, destinée à gérer efficacement les opérations quotidiennes d’un hôtel : chambres, réservations, clients, paiements et facturation.

---

## ✨ Fonctionnalités

### 🔐 Authentification & utilisateurs
- Connexion / déconnexion sécurisée
- Gestion des utilisateurs
- Attribution de rôles (administrateur, réception, etc.)

### 🛏️ Gestion des chambres
- Création, modification et suppression de chambres
- Gestion des types de chambres
- Suivi de la disponibilité
- Définition des tarifs par chambre

### 📅 Réservations
- Recherche de chambres disponibles par date (check-in / check-out)
- Création et modification de réservations
- Annulation de réservations
- Historique des réservations

### 👤 Gestion des clients
- Enregistrement des clients
- Association client ↔ réservation
- Historique des séjours

### 💳 Paiements & facturation
- Enregistrement des paiements
- Paiements partiels ou complets
- Suivi des statuts de paiement
- Génération de factures
- Historique des transactions

### 📊 Tableau de bord
- Vue globale des activités de l’hôtel
- Statistiques des réservations
- Suivi des revenus
- Taux d’occupation des chambres

### ⚙️ Administration
- Interface d’administration
- CRUD complet sur les entités principales
- Gestion centralisée des données
- Sécurité et validation des formulaires

---

## 🧱 Technologies utilisées

- **Laravel** (PHP Framework)
- **Blade** (templating)
- **Eloquent ORM**
- **MySQL / MariaDB**
- **Bootstrap / CSS**
- **JavaScript**
- **Vite**

---

## 📦 Installation

### Prérequis
- PHP >= 8.x
- Composer
- Node.js & npm
- MySQL

### Étapes

```bash
git clone https://github.com/yamdev07/HotelManagement.git
cd HotelManagement
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run dev
php artisan serve
````
Configurer la base de données dans le fichier .env avant de lancer les migrations.

### 🗂️ Structure du projet
````
app/                → Logique métier
routes/             → Routes web
resources/views/    → Templates Blade
database/migrations → Schéma de la base de données
public/             → Fichiers publics
````
---

### 🚧 Évolutions prévues

- Module de gestion de caisse

- Rapports financiers détaillés

- Export PDF / Excel

- Notifications avancées

- API REST / Mobile

---
### 🤝 Contribution

Les contributions sont les bienvenues !

- Fork le projet

- Crée une branche (feature/ma-fonctionnalite)

- Commit tes changements

- Ouvre une Pull Request

--- 
### 📄 Licence

Projet sous licence Apache 2.0.

---
### 👨‍💻 Auteur

Yoann Yamd
Développeur Web & Logiciel
📧 yoannyamd@gmail.com

🌐 https://github.com/yamdev07
