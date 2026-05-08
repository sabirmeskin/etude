# Mini ERP Scolaire (PHP natif + MySQL + Tailwind)

Petit projet académique en français.

Pré-requis
- PHP 7.4+ avec PDO MySQL
- MySQL / MariaDB

Installation rapide
1. Importer le schéma SQL :

```sql
-- depuis le dossier du projet
mysql -u root -p < db/schema.sql
```

2. Modifier `config/database.php` avec vos paramètres MySQL.
3. Créer un utilisateur admin : ouvrez un script PHP pour générer le hash :

```php
<?php
echo password_hash('admin123', PASSWORD_DEFAULT);
```

Copiez la valeur retournée et insérez-la dans la table `utilisateurs` :

```sql
INSERT INTO utilisateurs (nom, email, password) VALUES ('Admin','admin@local','<HASH>');
```

4. Lancer le serveur PHP intégré depuis la racine du projet :

```bash
php -S localhost:8000 -t public
```

5. Ouvrir `http://localhost:8000/index.php`.

Structure du projet
- `config/` : configuration (PDO)
- `controllers/` : contrôleurs
- `models/` : logique d'accès aux données (PDO)
- `views/` : templates (Tailwind)
- `public/` : point d'entrée (front controller) et assets

Notes
- Utilise PDO et requêtes préparées.
- Code simple et didactique, adapté pour un projet d'étude.

Si vous voulez, je peux :
- Ajouter la validation côté serveur plus complète
- Générer un script d'initialisation pour créer l'admin automatiquement
- Ajouter des tests ou commentaires supplémentaires
