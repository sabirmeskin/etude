<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends BaseController
{
    public function showLogin()
    {
        $this->render('auth/login');
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'nom' => $user['nom'] ?? '',
            ];
            header('Location: /index.php?r=dashboard');
            exit;
        }
        $this->render('auth/login', ['error' => 'Identifiants invalides']);
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /index.php?r=auth/login');
        exit;
    }
}
