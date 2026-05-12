<?php
// $content is rendered by included view files via render
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mini ERP Scolaire</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .sidebar { background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8c 100%); }
        .nav-link { transition: all 0.3s ease; }
        .nav-link:hover { transform: translateX(5px); background: rgba(255,255,255,0.1); }
        .btn-primary { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); transition: all 0.3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(59, 130, 246, 0.2); }
        .table-row:hover { background-color: #f3f4f6; }
        .stat-card { box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    </style>

</head>
<body class="bg-gray-50">
<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="sidebar w-64 text-white shadow-lg overflow-y-auto">
        <div class="p-6 border-b border-purple-400">
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i class="fas fa-graduation-cap"></i> Mini ERP
            </h2>
            <p class="text-sm text-purple-200 mt-1">Gestion Scolaire</p>
        </div>
        <nav class="p-4 space-y-2">
            <a href="/index.php?r=dashboard" class="nav-link block px-4 py-3 rounded-lg text-white hover:bg-purple-500">
                <i class="fas fa-chart-line mr-2"></i> Tableau de bord
            </a>
            <a href="/index.php?r=students" class="nav-link block px-4 py-3 rounded-lg text-white hover:bg-purple-500">
                <i class="fas fa-users mr-2"></i> Etudiants
            </a>
            <a href="/index.php?r=classes" class="nav-link block px-4 py-3 rounded-lg text-white hover:bg-purple-500">
                <i class="fas fa-chalkboard mr-2"></i> Classes
            </a>
            <a href="/index.php?r=notes/create" class="nav-link block px-4 py-3 rounded-lg text-white hover:bg-purple-500">
                <i class="fas fa-file-alt mr-2"></i> Notes
            </a>
            <a href="/index.php?r=schedules" class="nav-link block px-4 py-3 rounded-lg text-white hover:bg-purple-500">
                <i class="fas fa-calendar-alt mr-2"></i> Emploi du temps
            </a>
            <a href="/index.php?r=matieres" class="nav-link block px-4 py-3 rounded-lg text-white hover:bg-purple-500">
                <i class="fas fa-book mr-2"></i> Matières
            </a>
            <hr class="border-purple-400 my-3">
            <a href="/index.php?r=auth/logout" class="nav-link block px-4 py-3 rounded-lg text-white hover:bg-red-500">
                <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
        <!-- Top Header -->
        <div class="bg-white shadow-sm border-b border-gray-200 p-6">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-800">Mini ERP Scolaire</h1>
                <div class="text-gray-600">
                    <i class="fas fa-user-circle mr-2"></i> Admin
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <?php
            // include the requested view
            if (isset($viewFile) && file_exists($viewFile)) {
                include $viewFile;
            } else {
                echo '<h1 class="text-2xl font-bold">Page</h1>';
            }
            ?>
        </div>
    </main>
</div>
<script src="/assets/js/app.js"></script>
</body>
</html>

