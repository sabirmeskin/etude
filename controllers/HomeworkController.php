<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';
require_once __DIR__ . '/../models/Devoir.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/ProfessorAssignment.php';

class HomeworkController extends BaseController
{
    public function index()
    {
        AuthHelper::requireProfesseurOuAdmin();

        if (AuthHelper::isAdmin()) {
            $devoirs = Devoir::allOrdered();
        } else {
            $uid = (int) (AuthHelper::user()['id'] ?? 0);
            if (ProfessorAssignment::tableExists() && ProfessorAssignment::countForTeacher($uid) > 0) {
                $devoirs = Devoir::forProfessorAssignments($uid);
            } else {
                $classIds = AuthHelper::teacherClassIds();
                $devoirs = Devoir::forTeacherClasses($classIds);
            }
        }

        $this->render('homework/index', ['devoirs' => $devoirs]);
    }

    public function create()
    {
        AuthHelper::requireProfesseurOuAdmin();

        $classes = ClassModel::all();
        if (AuthHelper::isProfesseur()) {
            $allowed = AuthHelper::teacherClassIds();
            $classes = array_values(array_filter($classes, static function ($c) use ($allowed) {
                return in_array((int) $c['id'], $allowed, true);
            }));
        }

        $prefClasse = (int) ($_GET['classe_id'] ?? $_POST['classe_id'] ?? 0);
        $subjects = Subject::all();
        $reloadOnClassChange = false;

        if (AuthHelper::isProfesseur()) {
            $uid = (int) (AuthHelper::user()['id'] ?? 0);
            if (ProfessorAssignment::tableExists() && ProfessorAssignment::countForTeacher($uid) > 0) {
                $reloadOnClassChange = true;
                if ($prefClasse > 0) {
                    $allowedM = ProfessorAssignment::matiereIdsForTeacherClass($uid, $prefClasse);
                    $subjects = array_values(array_filter($subjects, static function ($s) use ($allowedM) {
                        return in_array((int) $s['id'], $allowedM, true);
                    }));
                } else {
                    $subjects = [];
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classeId = (int) ($_POST['classe_id'] ?? 0);
            $matiereId = (int) ($_POST['matiere_id'] ?? 0);
            $titre = trim($_POST['titre'] ?? '');
            $consigne = trim($_POST['consigne'] ?? '');
            $dateLimite = trim($_POST['date_limite'] ?? '');

            if (!$classeId || !$matiereId || $titre === '') {
                $this->render('homework/create', [
                    'errors' => ['Classe, matiere et titre sont obligatoires.'],
                    'classes' => $classes,
                    'subjects' => $subjects,
                    'old' => $_POST,
                    'prefClasse' => $classeId ?: $prefClasse,
                    'reloadOnClassChange' => $reloadOnClassChange,
                ]);
                return;
            }

            if (!AuthHelper::teacherCanGrade($classeId, $matiereId)) {
                http_response_code(403);
                echo 'Acces refuse';
                exit;
            }

            $uid = (int) (AuthHelper::user()['id'] ?? 0);
            Devoir::create([
                'classe_id' => $classeId,
                'matiere_id' => $matiereId,
                'titre' => $titre,
                'consigne' => $consigne,
                'date_limite' => $dateLimite !== '' ? $dateLimite : null,
                'created_by' => $uid,
            ]);

            header('Location: /index.php?r=homework&saved=1');
            exit;
        }

        $this->render('homework/create', [
            'classes' => $classes,
            'subjects' => $subjects,
            'prefClasse' => $prefClasse,
            'reloadOnClassChange' => $reloadOnClassChange,
        ]);
    }

    public function delete()
    {
        AuthHelper::requireProfesseurOuAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        if (!$id) {
            header('Location: /index.php?r=homework');
            exit;
        }

        $d = Devoir::find($id);
        if (!$d) {
            header('Location: /index.php?r=homework');
            exit;
        }

        if (!AuthHelper::isAdmin()) {
            if (!AuthHelper::teacherCanGrade((int) $d['classe_id'], (int) $d['matiere_id'])) {
                http_response_code(403);
                echo 'Acces refuse';
                exit;
            }
            if ((int) $d['created_by'] !== (int) (AuthHelper::user()['id'] ?? 0)) {
                http_response_code(403);
                echo 'Acces refuse';
                exit;
            }
        }

        Devoir::delete($id);
        header('Location: /index.php?r=homework');
        exit;
    }
}
