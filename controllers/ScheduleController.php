<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Schedule.php';

class ScheduleController extends BaseController
{
    public function index()
    {
        $schedules = Schedule::all();
        $this->render('schedules/list', ['schedules' => $schedules]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $classe_id = $_POST['classe_id'] ?? null;
            $matiere_id = $_POST['matiere_id'] ?? null;
            $jour = $_POST['jour'] ?? '';
            $heure_debut = $_POST['heure_debut'] ?? '';
            $heure_fin = $_POST['heure_fin'] ?? '';

            if (!$classe_id) $errors[] = 'La classe est requise.';
            if (!$matiere_id) $errors[] = 'La matière est requise.';
            if (!$jour) $errors[] = 'Le jour est requis.';
            if (!$heure_debut) $errors[] = 'L\'heure de début est requise.';
            if (!$heure_fin) $errors[] = 'L\'heure de fin est requise.';

            if (!empty($errors)) {
                require_once __DIR__ . '/../models/ClassModel.php';
                require_once __DIR__ . '/../models/Subject.php';
                $classes = ClassModel::all();
                $subjects = Subject::all();
                $this->render('schedules/create', ['errors' => $errors, 'classes' => $classes, 'subjects' => $subjects, 'old' => $_POST]);
                return;
            }

            Schedule::create($_POST);
            header('Location: /index.php?r=schedules');
            exit;
        }

        require_once __DIR__ . '/../models/ClassModel.php';
        require_once __DIR__ . '/../models/Subject.php';
        $classes = ClassModel::all();
        $subjects = Subject::all();
        $this->render('schedules/create', ['classes' => $classes, 'subjects' => $subjects]);
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /index.php?r=schedules');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Schedule::update($id, $_POST);
            header('Location: /index.php?r=schedules');
            exit;
        }

        $schedule = Schedule::find($id);
        require_once __DIR__ . '/../models/ClassModel.php';
        require_once __DIR__ . '/../models/Subject.php';
        $classes = ClassModel::all();
        $subjects = Subject::all();
        $this->render('schedules/edit', ['schedule' => $schedule, 'classes' => $classes, 'subjects' => $subjects]);
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Schedule::delete($id);
        }
        header('Location: /index.php?r=schedules');
        exit;
    }
}
