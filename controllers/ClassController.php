<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Attendance.php';

class ClassController extends BaseController
{
    public function index()
    {
        $classes = ClassModel::all();
        $this->render('classes/list', ['classes' => $classes]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            ClassModel::create($_POST);
            header('Location: /index.php?r=classes');
            exit;
        }
        $this->render('classes/create');
    }

    public function assign()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classId = $_POST['class_id'];
            $studentId = $_POST['student_id'];
            ClassModel::assignStudent($classId, $studentId);
            header('Location: /index.php?r=classes');
            exit;
        }
        $classes = ClassModel::all();
        $students = Student::all();
        $this->render('classes/assign', ['classes' => $classes, 'students' => $students]);
    }

    public function roster()
    {
        $classId = $_GET['id'] ?? null;
        if (!$classId) {
            header('Location: /index.php?r=classes');
            exit;
        }

        $class = ClassModel::find($classId);
        if (!$class) {
            header('Location: /index.php?r=classes');
            exit;
        }

        $date = $_POST['date'] ?? ($_GET['date'] ?? date('Y-m-d'));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Attendance::saveForClassAndDate($classId, $date, $_POST['attendance'] ?? []);
            header('Location: /index.php?r=classes/roster&id=' . $classId . '&date=' . urlencode($date));
            exit;
        }

        $students = Attendance::rosterByClassAndDate($classId, $date);
        $summary = Attendance::summaryForClassAndDate($classId, $date);
        $this->render('classes/roster', [
            'class' => $class,
            'students' => $students,
            'date' => $date,
            'summary' => $summary,
        ]);
    }
}
