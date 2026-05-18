<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';

class DashboardController extends BaseController
{
    public function index()
    {
        if (AuthHelper::isEtudiant()) {
            header('Location: /index.php?r=portal');
            exit;
        }

        if (AuthHelper::isProfesseur()) {
            $this->render('dashboard_teacher', [
                'teacherClassIds' => AuthHelper::teacherClassIds(),
            ]);
            return;
        }

        $this->render('dashboard');
    }
}
