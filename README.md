# Mini ERP Scolaire

Application PHP native pour la gestion d’un établissement scolaire : élèves, classes, matières, notes, emplois du temps, annonces et authentification.

## Prérequis

- PHP 7.4+ avec extension PDO MySQL
- MySQL ou MariaDB
- Un serveur web local ou le serveur intégré de PHP

## Installation

1. Créez la base de données en important le schéma :

```bash
mysql -u root -p < db/schema.sql
```

2. Renseignez vos accès MySQL dans [config/database.php](config/database.php).
3. Créez un compte administrateur. Générez d’abord un hash de mot de passe :

```php
<?php
echo password_hash('admin123', PASSWORD_DEFAULT);
```

Puis insérez l’utilisateur dans la table `utilisateurs` :

```sql
INSERT INTO utilisateurs (nom, email, password) VALUES ('Admin', 'admin@local', '<HASH>');
```

4. Lancez l’application depuis la racine du projet :

```bash
php -S localhost:8000 -t public
```

5. Ouvrez ensuite [http://localhost:8000/index.php?r=auth/login](http://localhost:8000/index.php?r=auth/login).

## Structure

- [config/](config/) : configuration de la base de données
- [controllers/](controllers/) : contrôleurs de l’application
- [models/](models/) : accès aux données et logique métier
- [views/](views/) : vues PHP de l’interface
- [public/](public/) : point d’entrée, assets et fichiers publics
- [db/](db/) : schéma SQL et scripts de reset/seed
- [storage/](storage/) : stockage interne des fichiers uploadés

## Fonctionnalités

- Authentification utilisateur
- Gestion des classes
- Gestion des élèves
- Gestion des matières
- Saisie et consultation des notes
- Gestion des emplois du temps
- Publication d’annonces

## Notes techniques

- Le point d’entrée principal est [public/index.php](public/index.php).
- Les routes passent par le paramètre `r`, par exemple `auth/login`, `students/list` ou `media/image`.
- Les photos d’élèves sont stockées dans [storage/uploads/images](storage/uploads/images) et servies via le contrôleur média.
- Le dossier [public/uploads/images](public/uploads/images) doit rester vide.
- L’application utilise PDO et des requêtes préparées.

## Données de départ

- [db/schema.sql](db/schema.sql) crée toutes les tables principales.
- [db/seed.php](db/seed.php) peut être utilisé pour charger des données de test.
- [db/reset.php](db/reset.php) sert à réinitialiser la base si besoin.

## Remarques

Ce projet est volontairement simple et pédagogique, adapté à un travail d’étude.
