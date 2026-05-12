-- Schéma SQL pour Mini ERP Scolaire

CREATE DATABASE IF NOT EXISTS mini_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mini_erp;

-- classes (créées avant les étudiants car FK)
CREATE TABLE IF NOT EXISTS classes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- utilisateurs (pour l'auth)
CREATE TABLE IF NOT EXISTS utilisateurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) DEFAULT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- etudiants
CREATE TABLE IF NOT EXISTS etudiants (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  email VARCHAR(150) DEFAULT NULL,
  classe_id INT DEFAULT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- notes

-- matieres (sujets)
CREATE TABLE IF NOT EXISTS matieres (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150) NOT NULL,
  description TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- notes (réécrite pour référencer matiere_id)
CREATE TABLE IF NOT EXISTS notes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  etudiant_id INT NOT NULL,
  matiere_id INT NOT NULL,
  note DECIMAL(5,2) NOT NULL,
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  FOREIGN KEY (matiere_id) REFERENCES matieres(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- emploi du temps (horaires des classes)
CREATE TABLE IF NOT EXISTS emploi_du_temps (
  id INT AUTO_INCREMENT PRIMARY KEY,
  classe_id INT NOT NULL,
  matiere_id INT NOT NULL,
  jour VARCHAR(20) NOT NULL,
  heure_debut TIME NOT NULL,
  heure_fin TIME NOT NULL,
  FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE CASCADE,
  FOREIGN KEY (matiere_id) REFERENCES matieres(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- présences (attendance)
CREATE TABLE IF NOT EXISTS presences (
  id INT AUTO_INCREMENT PRIMARY KEY,
  etudiant_id INT NOT NULL,
  date DATE NOT NULL,
  present BOOLEAN DEFAULT TRUE,
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  UNIQUE KEY unique_presence (etudiant_id, date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- annonces (actualites)
CREATE TABLE IF NOT EXISTS annonces (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(200) NOT NULL,
  contenu TEXT NOT NULL,
  date_publication DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  createur VARCHAR(100) DEFAULT 'Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- Fin du schéma

-- Pour créer un utilisateur admin, générer un mot de passe hashé en PHP :
-- <?php echo password_hash('admin123', PASSWORD_DEFAULT);
-- Puis insérer la ligne suivante (remplacer <HASH>) :
-- INSERT INTO utilisateurs (nom, email, password) VALUES ('Admin', 'admin@local', '<HASH>');