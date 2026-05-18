<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Note.php';
require_once __DIR__ . '/../models/Attendance.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../helpers/upload.php';

class StudentController extends BaseController
{
    private string $photoDir;

    public function __construct()
    {
        AuthHelper::requireAdmin();
        $this->photoDir = __DIR__ . '/../storage/uploads/images';
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['page'] ?? 1);
        $pager = Student::paginate($page, 5, $search);
        $students = $pager['data'];
        $this->render('students/list', ['students' => $students, 'search' => $search, 'pager' => $pager]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if ($nom === '') $errors[] = 'Le nom est requis.';
            if ($prenom === '') $errors[] = 'Le prénom est requis.';

            // Handle photo upload
            if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg','image/png','image/webp'];
                $name = uploadFile($_FILES['photo'], $this->photoDir, $allowed, 2 * 1024 * 1024);
                if ($name) $_POST['photo'] = $name;
            }

            if (!empty($errors)) {
                $this->render('students/create', ['errors' => $errors, 'old' => $_POST]);
                return;
            }

            Student::create($_POST);
            header('Location: /index.php?r=students');
            exit;
        }
        $this->render('students/create');
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /index.php?r=students');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get existing student to preserve all fields
            $existingStudent = Student::find($id);
            
            // Merge existing student data with new POST data
            $updateData = $existingStudent;
            
            // Update only the fields that are in the POST (from the info form or photo form)
            if (!empty($_POST['nom'])) $updateData['nom'] = $_POST['nom'];
            if (!empty($_POST['prenom'])) $updateData['prenom'] = $_POST['prenom'];
            if (!empty($_POST['email'])) $updateData['email'] = $_POST['email'];
            
            // Handle photo upload
            if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg','image/png','image/webp'];
                $name = uploadFile($_FILES['photo'], $this->photoDir, $allowed, 2 * 1024 * 1024);
                if ($name) $updateData['photo'] = $name;
            }
            
            Student::update($id, $updateData);
            header('Location: /index.php?r=students');
            exit;
        }
        $student = Student::find($id);
        $this->render('students/edit', ['student' => $student]);
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            // delete photo file if present (and safe)
            $student = Student::find($id);
            if (!empty($student['photo'])) {
                $filePath = $this->photoDir . DIRECTORY_SEPARATOR . basename($student['photo']);
                $uploadsBase = realpath($this->photoDir);
                $realFile = realpath($filePath);
                if ($realFile && $uploadsBase && strpos($realFile, $uploadsBase) === 0) {
                    @unlink($realFile);
                }
            }

            Student::delete($id);
        }
        header('Location: /index.php?r=students');
        exit;
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /index.php?r=students');
            exit;
        }
        $student = Student::find($id);
        if (!$student) {
            header('Location: /index.php?r=students');
            exit;
        }

        // Get student's class
        $class = null;
        if (!empty($student['classe_id'])) {
            $class = ClassModel::find($student['classe_id']);
        }

        // Get student's grades
        $notes = Note::byStudent($id);

        // Get attendance statistics
        $attendanceStats = Attendance::statsForStudent($id);
        $recentAttendance = Attendance::byStudent($id, 5);

        $this->render('students/show', [
            'student' => $student,
            'class' => $class,
            'notes' => $notes,
            'attendanceStats' => $attendanceStats,
            'recentAttendance' => $recentAttendance
        ]);
    }
}
