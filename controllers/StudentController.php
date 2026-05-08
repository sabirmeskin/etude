<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../helpers/upload.php';

class StudentController extends BaseController
{
    public function index()
    {
        $students = Student::all();
        $this->render('students/list', ['students' => $students]);
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
                $name = uploadFile($_FILES['photo'], __DIR__ . '/../public/uploads/images', $allowed, 2 * 1024 * 1024);
                if ($name) $_POST['photo'] = '/uploads/images/' . $name;
            }

            if (!empty($errors)) {
                $this->render('students/create', ['errors' => $errors, 'old' => $_POST]);
                return;
            }

            $studentId = Student::create($_POST);

            // Handle single document upload on creation (optional)
            if (!empty($_FILES['doc']) && $_FILES['doc']['error'] === UPLOAD_ERR_OK) {
                require_once __DIR__ . '/../models/Document.php';
                $allowed = ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','image/jpeg','image/png'];
                $docName = uploadFile($_FILES['doc'], __DIR__ . '/../public/uploads/docs', $allowed, 10 * 1024 * 1024);
                if ($docName) {
                    $path = '/uploads/docs/' . $docName;
                    Document::create($studentId, $_FILES['doc']['name'], $path);
                }
            }

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
            // Handle photo upload
            if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg','image/png','image/webp'];
                $name = uploadFile($_FILES['photo'], __DIR__ . '/../public/uploads/images', $allowed, 2 * 1024 * 1024);
                if ($name) $_POST['photo'] = '/uploads/images/' . $name;
            }
            Student::update($id, $_POST);
            header('Location: /index.php?r=students');
            exit;
        }
        $student = Student::find($id);
        // load documents
        require_once __DIR__ . '/../models/Document.php';
        $documents = Document::byStudent($id);
        $this->render('students/edit', ['student' => $student, 'documents' => $documents]);
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            // delete photo file if present (and safe)
            $student = Student::find($id);
            if (!empty($student['photo'])) {
                $publicDir = realpath(__DIR__ . '/../public');
                $filePath = $publicDir . $student['photo'];
                $uploadsBase = realpath(__DIR__ . '/../public/uploads');
                $realFile = realpath($filePath);
                if ($realFile && $uploadsBase && strpos($realFile, $uploadsBase) === 0) {
                    @unlink($realFile);
                }
            }

            // delete related documents (files + records)
            require_once __DIR__ . '/../models/Document.php';
            $docs = Document::byStudent($id);
            foreach ($docs as $d) {
                Document::delete($d['id']);
            }

            Student::delete($id);
        }
        header('Location: /index.php?r=students');
        exit;
    }
}
