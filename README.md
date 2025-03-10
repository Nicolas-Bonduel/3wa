
# My Electronics

Projet de validation du titre RNCP 37273 - formation développeur full-stack - 3W Academy


## Apercu

Application de **site e-commerce** - SPA (Single Page Application) **Laravel 11** & **Livewire 3**.

[démo en ligne](https://nicolas.dauba.net/)

## Contexte

Le contexte choisi pour cette application est celui d'une prestation fournie à une entreprise possédant un **ERP de gestion** de ses produits, commandes, clients, factures, etc.., il est donc question d'**importer et d'utiliser ces données dans l'application**

Les données utilisées pour cette démo étant réelle, **elle ne sont pas fournies dans ce dépôt**. Installer ce projet en local à partir du dépôt est possible mais l'application de contiendra aucun produit.

La [démo de l'application en ligne](https://nicolas.dauba.net/) contient toutes les données nécessaires.

## Configuration Système Requise

- PHP >= 8.2
- Laravel 11.x
- Livewire 3.x
- NodeJs
- npm
- composer
- MySQL / MariaDB / InnoDB / PgSQL

## Installation (local)

### 1. Cloner le dépôt git

```bash
git clone https://github.com/Nicolas-Bonduel/3wa.git
cd 3wa
```

### 2. Installation dépendances php

```bash
composer install
```

### 3. Installation dépendances Javascript & build

```bash
npm install
npm run build
```

### 4. Variables d'environnement

Copier le fichier `.env.example` vers `.env`:

```bash
cp .env.example .env
```

### 5. Générer la clé d'encryption

```bash
php artisan key:generate
```

### 6. Créer le lien symbolique vers `storage`

```bash
php artisan storage:link
```

### 7. Configurer la base de données

Modifier dans le fichier `.env` :

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Lancer les migrations :

```bash
php artisan migrate
```

### 8. Lancer le serveur de développement

```bash
php artisan serve
```

Par défaut, l'application sera accessible à l'adresse `http://127.0.0.1:8000`.
