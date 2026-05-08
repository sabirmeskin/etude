<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Note.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Subject.php';

class NoteController extends BaseController
{
    public function index()
    {
        // Si un étudiant est fourni, afficher ses notes
        $studentId = $_GET['student_id'] ?? null;
        if ($studentId) {
            $this->listByStudent();
            return;
        }

        // Sinon afficher le formulaire de création de note
        $students = Student::all();
        $subjects = Subject::all();
        $this->render('notes/create', ['students' => $students, 'subjects' => $subjects]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Note::create($_POST);
            header('Location: /index.php?r=notes&student_id=' . $_POST['student_id']);
            exit;
        }
        $students = Student::all();
        $subjects = Subject::all();
        $this->render('notes/create', ['students' => $students, 'subjects' => $subjects]);
    }

    public function listByStudent()
    {
        $studentId = $_GET['student_id'] ?? null;
        if (!$studentId) {
            header('Location: /index.php?r=students');
            exit;
        }
        $notes = Note::byStudent($studentId);
        $avg = Note::average($studentId);
        $student = Student::find($studentId);
        $this->render('notes/list', ['notes' => $notes, 'avg' => $avg, 'student' => $student]);
    }
}
