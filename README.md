# ERP JR Computer - Système de Gestion Intégré

## Description
ERP modulaire pour **JR Computer Sarl** (Douala, Cameroun) spécialisé dans la vente et maintenance informatique.

## Modules implémentés
- ✅ **Module 0** : Core (Authentification, RBAC, Activity Log)
- ✅ **Module 1** : Produits & Stock (CRUD, mouvements, alertes)
- ✅ **Module 2** : Achats & Fournisseurs (BC, réceptions)
- ✅ **Module 3** : Ventes & POS (Devis, factures, encaissements)
- ✅ **Module 5** : SAV (Tickets, interventions, pièces)
- ✅ **Module 8** : Analytics & Dashboard (KPIs, graphiques)

## Technologies
- **Backend** : Laravel 12
- **Frontend** : Livewire + Alpine.js + Bootstrap 5
- **Base de données** : MySQL 8
- **Cache/Queue** : Redis + Predis
- **Temps réel** : Laravel Reverb
- **Mobile** : React Native (Expo)
- **PDF** : DomPDF
- **Excel** : Maatwebsite Excel

## Installation

### Prérequis
- PHP 8.2+
- Composer
- MySQL 8+
- Node.js 18+
- Redis

### Étapes d'installation

```bash
# Cloner le projet
git clone https://github.com/votre-username/jrcomputer-erp.git
cd jrcomputer-erp

# Installer les dépendances PHP
composer install

# Installer les dépendances Node
npm install

# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé application
php artisan key:generate

# Configurer la base de données dans .env puis migrer
php artisan migrate --seed

# Compiler les assets
npm run build

# Démarrer le serveur
php artisan serve --host=0.0.0.0 --port=8000

# Démarrer le worker de queue (autre terminal)
php artisan queue:work

# Démarrer Reverb (notifications temps réel)
php artisan reverb:start