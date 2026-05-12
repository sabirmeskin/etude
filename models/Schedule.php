<?php
require_once __DIR__ . '/../config/database.php';

class Schedule
{
    public static function all()
    {
        $pdo = db();
        $sql = 'SELECT e.*, c.nom as classe_nom, m.nom as matiere_nom 
                FROM emploi_du_temps e
                LEFT JOIN classes c ON e.classe_id = c.id
                LEFT JOIN matieres m ON e.matiere_id = m.id
                ORDER BY e.classe_id, FIELD(e.jour, "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"), e.heure_debut';
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function byClass($classId)
    {
        $pdo = db();
        $sql = 'SELECT e.*, m.nom as matiere_nom 
                FROM emploi_du_temps e
                LEFT JOIN matieres m ON e.matiere_id = m.id
                WHERE e.classe_id = ?
                ORDER BY FIELD(e.jour, "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"), e.heure_debut';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$classId]);
        return $stmt->fetchAll();
    }

    public static function create(array $data)
    {
        $pdo = db();
        $stmt = $pdo->prepare('INSERT INTO emploi_du_temps (classe_id, matiere_id, jour, heure_debut, heure_fin) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['classe_id'],
            $data['matiere_id'],
            $data['jour'],
            $data['heure_debut'],
            $data['heure_fin']
        ]);
    }

    public static function update($id, array $data)
    {
        $pdo = db();
        $stmt = $pdo->prepare('UPDATE emploi_du_temps SET classe_id = ?, matiere_id = ?, jour = ?, heure_debut = ?, heure_fin = ? WHERE id = ?');
        return $stmt->execute([
            $data['classe_id'],
            $data['matiere_id'],
            $data['jour'],
            $data['heure_debut'],
            $data['heure_fin'],
            $id
        ]);
    }

    public static function delete($id)
    {
        $pdo = db();
        $stmt = $pdo->prepare('DELETE FROM emploi_du_temps WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function find($id)
    {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM emploi_du_temps WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
