<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Announcement.php';

class AnnouncementController extends BaseController
{
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?r=dashboard');
            exit;
        }

        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');

        if ($titre !== '' && $contenu !== '') {
            Announcement::create([
                'titre' => $titre,
                'contenu' => $contenu,
                'createur' => 'Admin'
            ]);
        }

        header('Location: /index.php?r=dashboard');
        exit;
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Announcement::delete($id);
        }
        header('Location: /index.php?r=dashboard');
        exit;
    }
}
