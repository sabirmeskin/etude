<?php
// Front controller
session_start();

require_once __DIR__ . '/../config/database.php';

// Autoloader pour controllers et models
spl_autoload_register(function ($class) {
    $paths = [__DIR__ . '/../controllers/', __DIR__ . '/../models/'];
    foreach ($paths as $p) {
        $file = $p . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

require_once __DIR__ . '/../helpers/AuthHelper.php';

// Simple route handling via param r (controller/method)
$r = $_GET['r'] ?? 'dashboard';
$parts = explode('/', $r);
$action = $parts[1] ?? 'index';

// Map routes to controllers (handle plurals and singulars)
$routeMap = [
    'dashboard' => 'DashboardController',
    'students' => 'StudentController',
    'classes' => 'ClassController',
    'matieres' => 'SubjectController',
    'notes' => 'NoteController',
    'schedules' => 'ScheduleController',
    'announcements' => 'AnnouncementController',
    'media' => 'MediaController',
    'auth' => 'AuthController',
    'portal' => 'PortalController',
    'homework' => 'HomeworkController',
    'admin' => 'AdminController',
];

$controllerName = $routeMap[$parts[0]] ?? (ucfirst($parts[0]) . 'Controller');

if (empty($_SESSION['user']) && $parts[0] !== 'auth') {
    header('Location: /index.php?r=auth/login');
    exit;
}

if (!empty($_SESSION['user'])) {
    $role = AuthHelper::role();
    if ($parts[0] === 'admin' && !AuthHelper::isAdmin()) {
        header('Location: /index.php?r=dashboard');
        exit;
    }
    if ($role === 'etudiant') {
        $allowedRoots = ['portal', 'auth', 'media', 'dashboard'];
        if (!in_array($parts[0], $allowedRoots, true)) {
            header('Location: /index.php?r=portal');
            exit;
        }
    }
    if ($role === 'professeur') {
        if (in_array($parts[0], ['students', 'classes', 'announcements', 'admin'], true)) {
            header('Location: /index.php?r=dashboard');
            exit;
        }
        if ($parts[0] === 'matieres' && $action !== 'index') {
            header('Location: /index.php?r=matieres');
            exit;
        }
        if ($parts[0] === 'schedules' && $action !== 'index') {
            header('Location: /index.php?r=schedules');
            exit;
        }
    }
}

try {
    if ($parts[0] === 'auth') {
        $authController = new AuthController();

        if ($r === 'auth/login') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->login();
            } else {
                $authController->showLogin();
            }
            exit;
        }

        if ($r === 'auth/register') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->register();
            } else {
                $authController->showRegister();
            }
            exit;
        }

        if ($r === 'auth/forgot-password') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->sendForgotPassword();
            } else {
                $authController->showForgotPassword();
            }
            exit;
        }

        if ($r === 'auth/reset-password') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->applyResetPassword();
            } else {
                $authController->showResetPassword();
            }
            exit;
        }

        if ($r === 'auth/logout') {
            $authController->logout();
            exit;
        }
    }

    if (!class_exists($controllerName)) {
        http_response_code(404);
        echo 'Page non trouvée';
        exit;
    }

    $controller = new $controllerName();
    if (method_exists($controller, $action)) {
        $controller->{$action}();
    } else {
        if ($parts[0] === 'notes' && isset($_GET['student_id'])) {
            $nc = new NoteController();
            $nc->listByStudent();
        } else {
            http_response_code(404);
            echo 'Page non trouvée';
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo 'Erreur: ' . htmlspecialchars($e->getMessage());
}
