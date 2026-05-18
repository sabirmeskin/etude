<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Schedule.php';
require_once __DIR__ . '/../models/Note.php';
require_once __DIR__ . '/../models/Devoir.php';
require_once __DIR__ . '/../models/ClassModel.php';

class PortalController extends BaseController
{
    private function requireEtudiant(): array
    {
        if (!AuthHelper::isEtudiant()) {
            header('Location: /index.php?r=dashboard');
            exit;
        }
        $eid = AuthHelper::etudiantId();
        if (!$eid) {
            header('Location: /index.php?r=auth/logout');
            exit;
        }
        $student = Student::find($eid);
        if (!$student) {
            header('Location: /index.php?r=auth/logout');
            exit;
        }

        return $student;
    }

    public function index()
    {
        $student = $this->requireEtudiant();
        $class = null;
        if (!empty($student['classe_id'])) {
            $class = ClassModel::find((int) $student['classe_id']);
        }
        $avg = Note::average((int) $student['id']);
        $devoirsCount = 0;
        if (!empty($student['classe_id'])) {
            $devoirsCount = count(Devoir::forClass((int) $student['classe_id']));
        }

        $this->render('portal/index', [
            'student' => $student,
            'class' => $class,
            'avg' => $avg,
            'devoirsCount' => $devoirsCount,
        ]);
    }

    public function schedule()
    {
        $student = $this->requireEtudiant();
        $schedules = [];
        if (!empty($student['classe_id'])) {
            $schedules = Schedule::byClass((int) $student['classe_id']);
        }
        $this->render('portal/schedule', ['student' => $student, 'schedules' => $schedules]);
    }

    public function notes()
    {
        $student = $this->requireEtudiant();
        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['page'] ?? 1);
        $pager = Note::paginateByStudent((int) $student['id'], $page, 10, $search);
        $avg = Note::average((int) $student['id']);
        $stats = Student::getGradeStats((int) $student['id']);

        $this->render('portal/notes', [
            'student' => $student,
            'notes' => $pager['data'],
            'pager' => $pager,
            'search' => $search,
            'avg' => $avg,
            'stats' => $stats,
        ]);
    }

    public function homework()
    {
        $student = $this->requireEtudiant();
        $devoirs = [];
        if (!empty($student['classe_id'])) {
            $devoirs = Devoir::forClass((int) $student['classe_id']);
        }
        $this->render('portal/homework', ['student' => $student, 'devoirs' => $devoirs]);
    }
}
