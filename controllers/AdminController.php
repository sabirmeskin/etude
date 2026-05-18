<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/ProfessorAssignment.php';

class AdminController extends BaseController
{
    /** @deprecated Utiliser teacherAssignments */
    public function teacherClasses()
    {
        header('Location: /index.php?r=admin/teacherAssignments');
        exit;
    }

    /**
     * Affectations : professeur + matiere + classe.
     * Les classes du prof (professeur_classe) sont synchronisees automatiquement.
     */
    public function teacherAssignments()
    {
        AuthHelper::requireAdmin();

        $professeurs = User::allProfesseurs();
        $classes = ClassModel::all();
        $matieres = Subject::all();

        $selectedId = (int) ($_GET['utilisateur_id'] ?? 0);

        if (isset($_GET['remove']) && $selectedId > 0) {
            $rid = (int) $_GET['remove'];
            if ($rid > 0 && ProfessorAssignment::remove($rid, $selectedId)) {
                $_SESSION['flash_success'] = 'Affectation supprimee.';
            }
            header('Location: /index.php?r=admin/teacherAssignments&utilisateur_id=' . $selectedId);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_assignment'])) {
            $userId = (int) ($_POST['utilisateur_id'] ?? 0);
            $classeId = (int) ($_POST['classe_id'] ?? 0);
            $matiereId = (int) ($_POST['matiere_id'] ?? 0);

            $target = User::findById($userId);
            if (!$target || ($target['role'] ?? '') !== 'professeur') {
                $this->render('admin/teacher_assignments', [
                    'professeurs' => $professeurs,
                    'classes' => $classes,
                    'matieres' => $matieres,
                    'error' => 'Professeur invalide.',
                    'selected_id' => $userId,
                    'assignments' => $userId > 0 ? ProfessorAssignment::allForTeacher($userId) : [],
                ]);
                return;
            }

            if ($classeId < 1 || $matiereId < 1) {
                $this->render('admin/teacher_assignments', [
                    'professeurs' => $professeurs,
                    'classes' => $classes,
                    'matieres' => $matieres,
                    'error' => 'Choisissez une classe et une matiere.',
                    'selected_id' => $userId,
                    'assignments' => ProfessorAssignment::allForTeacher($userId),
                ]);
                return;
            }

            ProfessorAssignment::add($userId, $classeId, $matiereId);
            $_SESSION['flash_success'] = 'Affectation ajoutee (classe + matiere).';
            header('Location: /index.php?r=admin/teacherAssignments&utilisateur_id=' . $userId);
            exit;
        }

        $assignments = [];
        if ($selectedId > 0) {
            $assignments = ProfessorAssignment::allForTeacher($selectedId);
        }

        $success = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);

        $this->render('admin/teacher_assignments', [
            'professeurs' => $professeurs,
            'classes' => $classes,
            'matieres' => $matieres,
            'selected_id' => $selectedId,
            'assignments' => $assignments,
            'success' => $success,
        ]);
    }
}
