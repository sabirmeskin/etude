<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Document.php';
require_once __DIR__ . '/../helpers/upload.php';

class DocumentController extends BaseController
{
    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Method not allowed';
            exit;
        }
        $studentId = $_POST['student_id'] ?? null;
        if (!$studentId) {
            header('Location: /index.php?r=students');
            exit;
        }
        if (!empty($_FILES['doc']) && $_FILES['doc']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','image/jpeg','image/png'];
            $name = uploadFile($_FILES['doc'], __DIR__ . '/../public/uploads/docs', $allowed, 10 * 1024 * 1024);
            if ($name) {
                $path = '/uploads/docs/' . $name;
                Document::create($studentId, $_FILES['doc']['name'], $path);
            }
        }
        header('Location: /index.php?r=students/edit&id=' . $studentId);
        exit;
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /index.php?r=students');
            exit;
        }
        $doc = Document::get($id);
        if ($doc) {
            $studentId = $doc['etudiant_id'];
            Document::delete($id);
            header('Location: /index.php?r=students/edit&id=' . $studentId);
            exit;
        }
        header('Location: /index.php?r=students');
        exit;
    }
}
