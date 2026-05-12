<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Note.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Subject.php';

class NoteController extends BaseController
{
    public function index()
    {
        // If a student ID is provided, show their notes
        $studentId = $_GET['student_id'] ?? null;
        if ($studentId) {
            $this->listByStudent();
            return;
        }

        // Otherwise redirect to bulk entry
        header('Location: /index.php?r=notes/bulk');
        exit;
    }

    public function create()
    {
        // Redirect to bulk entry
        header('Location: /index.php?r=notes/bulk');
        exit;
    }

    public function listByStudent()
    {
        $studentId = $_GET['student_id'] ?? null;
        if (!$studentId) {
            header('Location: /index.php?r=students');
            exit;
        }
        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['page'] ?? 1);
        $pager = Note::paginateByStudent($studentId, $page, 5, $search);
        $notes = $pager['data'];
        $avg = Note::average($studentId);
        $student = Student::find($studentId);
        $stats = Student::getGradeStats($studentId);
        $this->render('notes/list', [
            'notes' => $notes,
            'avg' => $avg,
            'student' => $student,
            'stats' => $stats,
            'pager' => $pager,
            'search' => $search,
        ]);
    }

    public function stats()
    {
        $classId = $_GET['class_id'] ?? null;
        if (!$classId) {
            header('Location: /index.php?r=students');
            exit;
        }
        require_once __DIR__ . '/../models/ClassModel.php';
        $class = ClassModel::find($classId);
        $classStats = Student::getClassStats($classId);
        $this->render('notes/stats', ['class' => $class, 'classStats' => $classStats]);
    }

    public function bulk()
    {
        require_once __DIR__ . '/../models/ClassModel.php';
        $subjects = Subject::all();
        $classes = ClassModel::all();
        $selectedClass = $_GET['class_id'] ?? null;
        $selectedSubject = $_GET['subject_id'] ?? null;
        $students = [];

        if ($selectedClass && $selectedSubject) {
            $students = Student::byClass($selectedClass);
            // Get existing grades for this class/subject combination
            foreach ($students as &$student) {
                $existing = Note::getByStudentAndSubject($student['id'], $selectedSubject);
                $student['existing_grade'] = $existing ? $existing['note'] : '';
            }
        }

        $this->render('notes/bulk', [
            'subjects' => $subjects,
            'classes' => $classes,
            'students' => $students,
            'selectedClass' => $selectedClass,
            'selectedSubject' => $selectedSubject
        ]);
    }

    public function saveBulk()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?r=notes/bulk');
            exit;
        }

        $classId = $_POST['class_id'] ?? null;
        $subjectId = $_POST['subject_id'] ?? null;

        if (!$classId || !$subjectId) {
            header('Location: /index.php?r=notes/bulk');
            exit;
        }

        // Prepare grades data
        $grades = [];
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'grade_') === 0) {
                $studentId = str_replace('grade_', '', $key);
                if (!empty($value) || $value === '0') {
                    $grades[] = [
                        'student_id' => $studentId,
                        'matiere_id' => $subjectId,
                        'note' => $value
                    ];
                }
            }
        }

        if (!empty($grades)) {
            Note::createBulk($grades);
        }

        header('Location: /index.php?r=notes/bulk&class_id=' . $classId . '&subject_id=' . $subjectId . '&saved=1');
        exit;
    }
}

