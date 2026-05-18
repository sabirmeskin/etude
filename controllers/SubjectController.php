<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';
require_once __DIR__ . '/../models/Subject.php';

class SubjectController extends BaseController
{
    public function index()
    {
        $subjects = Subject::all();
        $this->render('subjects/list', ['subjects' => $subjects]);
    }

    public function create()
    {
        AuthHelper::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Subject::create($_POST);
            header('Location: /index.php?r=matieres');
            exit;
        }
        $this->render('subjects/create');
    }

    public function edit()
    {
        AuthHelper::requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /index.php?r=matieres');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Subject::update($id, $_POST);
            header('Location: /index.php?r=matieres');
            exit;
        }
        $subject = Subject::find($id);
        $this->render('subjects/edit', ['subject' => $subject]);
    }

    public function delete()
    {
        AuthHelper::requireAdmin();
        $id = $_GET['id'] ?? null;
        if ($id) Subject::delete($id);
        header('Location: /index.php?r=matieres');
        exit;
    }
}
