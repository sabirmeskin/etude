<?php
// Front controller
session_start();

require_once __DIR__ . '/../config/database.php';

// Autoloader pour controllers et models
spl_autoload_register(function ($class) {
    $paths = [__DIR__ . '/../controllers/', __DIR__ . '/../models/'];
    foreach ($paths as $p) {
        $file = $p . $class . '.php';
        if (file_exists($file)) require_once $file;
    }
});

// Simple route handling via param r (controller/method)
$r = $_GET['r'] ?? 'dashboard';
$parts = explode('/', $r);
$action = $parts[1] ?? 'index';

// Map routes to controllers (handle plurals and singulars)
$routeMap = [
    'students' => 'StudentController',
    'classes' => 'ClassController',
    'matieres' => 'SubjectController',
    'notes' => 'NoteController',
    'documents' => 'DocumentController',
    'auth' => 'AuthController',
];

$controllerName = $routeMap[$parts[0]] ?? (ucfirst($parts[0]) . 'Controller');

// Map some friendly routes
if ($r === 'auth/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $c = new AuthController();
    $c->login();
    exit;
}

try {
    if (!class_exists($controllerName)) {
        if ($parts[0] === 'dashboard') {
            require_once __DIR__ . '/../controllers/BaseController.php';
            $bc = new BaseController();
            $bc->render('dashboard');
            exit;
        }
    }

    $controller = new $controllerName();
    // map action names to methods
    if (method_exists($controller, $action)) {
        $controller->{$action}();
    } else {
        // custom route mapping
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
