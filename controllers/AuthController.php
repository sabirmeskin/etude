<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/PasswordReset.php';

class AuthController extends BaseController
{
    private function requestBaseUrl(): string
    {
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (string) ($_SERVER['SERVER_PORT'] ?? '') === '443';
        $scheme = $https ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return $scheme . '://' . $host;
    }

    public function showLogin()
    {
        if (!empty($_SESSION['user'])) {
            header('Location: /index.php?r=dashboard');
            exit;
        }

        $success = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);

        $this->render('auth/login', ['success' => $success]);
    }

    public function showForgotPassword()
    {
        if (!empty($_SESSION['user'])) {
            header('Location: /index.php?r=dashboard');
            exit;
        }

        $this->render('auth/forgot_password', [
            'email' => $_GET['email'] ?? '',
        ]);
    }

    public function sendForgotPassword()
    {
        if (!empty($_SESSION['user'])) {
            header('Location: /index.php?r=dashboard');
            exit;
        }

        $email = strtolower(trim($_POST['email'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render('auth/forgot_password', [
                'error' => 'Veuillez saisir une adresse email valide.',
                'email' => $email,
            ]);
            return;
        }

        $user = User::findByEmail($email);
        $resetUrl = null;
        if ($user) {
            try {
                $token = PasswordReset::createForUser((int) $user['id']);
                if (PASSWORD_RESET_SHOW_LINK) {
                    $resetUrl = $this->requestBaseUrl() . '/index.php?r=auth/reset-password&token=' . rawurlencode($token);
                }
            } catch (Throwable $e) {
                $this->render('auth/forgot_password', [
                    'error' => 'Service temporairement indisponible. Reessayez plus tard ou contactez l administrateur.',
                    'email' => $email,
                ]);
                return;
            }
        }

        $this->render('auth/forgot_password', [
            'email' => $email,
            'sent' => true,
            'resetUrl' => $resetUrl,
            'accountFound' => (bool) $user,
        ]);
    }

    public function showResetPassword()
    {
        if (!empty($_SESSION['user'])) {
            header('Location: /index.php?r=dashboard');
            exit;
        }

        $token = trim($_GET['token'] ?? '');
        if ($token === '') {
            $this->render('auth/reset_password', ['error' => 'Lien invalide ou expire.']);
            return;
        }

        $userId = PasswordReset::findUserIdByPlainToken($token);
        if (!$userId) {
            $this->render('auth/reset_password', ['error' => 'Lien invalide ou expire. Demandez un nouveau lien.']);
            return;
        }

        $this->render('auth/reset_password', ['token' => $token]);
    }

    public function applyResetPassword()
    {
        if (!empty($_SESSION['user'])) {
            header('Location: /index.php?r=dashboard');
            exit;
        }

        $token = trim($_POST['token'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

        if ($token === '') {
            $this->render('auth/reset_password', ['error' => 'Jeton manquant.']);
            return;
        }

        $userId = PasswordReset::findUserIdByPlainToken($token);
        if (!$userId) {
            $this->render('auth/reset_password', ['error' => 'Lien invalide ou expire.']);
            return;
        }

        if (strlen($password) < 6) {
            $this->render('auth/reset_password', [
                'error' => 'Le mot de passe doit contenir au moins 6 caracteres.',
                'token' => $token,
            ]);
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->render('auth/reset_password', [
                'error' => 'Les mots de passe ne correspondent pas.',
                'token' => $token,
            ]);
            return;
        }

        User::updatePassword($userId, $password);
        PasswordReset::deleteForUser($userId);

        $_SESSION['flash_success'] = 'Mot de passe mis a jour. Vous pouvez vous connecter.';
        header('Location: /index.php?r=auth/login');
        exit;
    }

    public function showRegister()
    {
        if (!empty($_SESSION['user'])) {
            header('Location: /index.php?r=dashboard');
            exit;
        }

        $this->render('auth/register');
    }

    public function login()
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $this->render('auth/login', [
                'error' => 'Veuillez remplir tous les champs.',
                'email' => $email,
            ]);
            return;
        }

        $user = User::findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $etudiantId = isset($user['etudiant_id']) && $user['etudiant_id'] !== null && $user['etudiant_id'] !== ''
                ? (int) $user['etudiant_id']
                : null;
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'nom' => $user['nom'] ?? '',
                'role' => $user['role'] ?? 'admin',
                'etudiant_id' => $etudiantId,
            ];
            header('Location: /index.php?r=dashboard');
            exit;
        }

        $this->render('auth/login', [
            'error' => 'Identifiants invalides',
            'email' => $email,
        ]);
    }

    public function register()
    {
        $name = trim($_POST['nom'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');
        $role = $_POST['role'] ?? 'professeur';
        if (!in_array($role, ['etudiant', 'professeur'], true)) {
            $role = 'professeur';
        }

        if ($name === '' || $email === '' || $password === '' || $passwordConfirm === '') {
            $this->render('auth/register', [
                'error' => 'Veuillez remplir tous les champs.',
                'nom' => $name,
                'email' => $email,
                'role' => $role,
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render('auth/register', [
                'error' => 'Adresse email invalide.',
                'nom' => $name,
                'email' => $email,
                'role' => $role,
            ]);
            return;
        }

        if (strlen($password) < 6) {
            $this->render('auth/register', [
                'error' => 'Le mot de passe doit contenir au moins 6 caracteres.',
                'nom' => $name,
                'email' => $email,
                'role' => $role,
            ]);
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->render('auth/register', [
                'error' => 'Les mots de passe ne correspondent pas.',
                'nom' => $name,
                'email' => $email,
                'role' => $role,
            ]);
            return;
        }

        if (User::findByEmail($email)) {
            $this->render('auth/register', [
                'error' => 'Un compte existe deja avec cet email.',
                'nom' => $name,
                'email' => $email,
                'role' => $role,
            ]);
            return;
        }

        $etudiantId = null;
        if ($role === 'etudiant') {
            $student = Student::findByEmail($email);
            if (!$student) {
                $this->render('auth/register', [
                    'error' => 'Aucun eleve enregistre avec cet email. L administration doit d abord creer votre fiche eleve avec la meme adresse email.',
                    'nom' => $name,
                    'email' => $email,
                    'role' => $role,
                ]);
                return;
            }
            if (empty($student['email'])) {
                $this->render('auth/register', [
                    'error' => 'Votre fiche eleve n a pas d email. Contactez l administration.',
                    'nom' => $name,
                    'email' => $email,
                    'role' => $role,
                ]);
                return;
            }
            if (User::countUsersLinkedToStudent((int) $student['id']) > 0) {
                $this->render('auth/register', [
                    'error' => 'Un compte existe deja pour cet eleve.',
                    'nom' => $name,
                    'email' => $email,
                    'role' => $role,
                ]);
                return;
            }
            $etudiantId = (int) $student['id'];
        }

        User::create($name, $email, $password, $role, $etudiantId);

        $_SESSION['flash_success'] = 'Compte cree avec succes. Vous pouvez maintenant vous connecter.';
        header('Location: /index.php?r=auth/login');
        exit;
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /index.php?r=auth/login');
        exit;
    }
}
