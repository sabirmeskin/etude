<?php
// Récupérer la liste des étudiants pour les statistiques
if (!class_exists('Student')) {
    require_once __DIR__ . '/../models/Student.php';
}
$students = Student::all();
// Récupérer la liste des classes pour les statistiques
if (!class_exists('ClassModel')) {
    require_once __DIR__ . '/../models/ClassModel.php';
}
$classes = ClassModel::all();
?>

<div class="space-y-6">
    <!-- Titre -->
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Bienvenue</h2>
        <p class="text-gray-600">Vue d'ensemble de votre établissement</p>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card Etudiants -->
        <div class="stat-card bg-white border-l-4 border-blue-600 p-6 rounded shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Nombre d'étudiants</p>
                    <p class="text-4xl font-bold mt-2 text-gray-800"><?= count($students) ?></p>
                </div>
                <i class="fas fa-users text-4xl text-blue-100"></i>
            </div>
            <a href="/index.php?r=students" class="mt-4 inline-block text-blue-600 hover:text-blue-800 font-medium transition">
                Voir les détails <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Card Classes -->
        <div class="stat-card bg-white border-l-4 border-gray-600 p-6 rounded shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Nombre de classes</p>
                    <p class="text-4xl font-bold mt-2 text-gray-800"><?= count($classes) ?></p>
                </div>
                <i class="fas fa-chalkboard text-4xl text-gray-100"></i>
            </div>
            <a href="/index.php?r=classes" class="mt-4 inline-block text-gray-600 hover:text-gray-800 font-medium transition">
                Gérer les classes <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Card Notes -->
        <div class="stat-card bg-white border-l-4 border-green-600 p-6 rounded shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Ajouter des notes</p>
                    <p class="text-4xl font-bold mt-2 text-gray-800">📊</p>
                </div>
                <i class="fas fa-chart-bar text-4xl text-green-100"></i>
            </div>
            <a href="/index.php?r=notes/create" class="mt-4 inline-block text-green-600 hover:text-green-800 font-medium transition">
                Ajouter une note <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Derniers étudiants -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Derniers étudiants ajoutés</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Nom</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Prénom</th>
                        <th class="text-left py-3 px-4 text-gray-700 font-semibold">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($students, 0, 5) as $s): ?>
                        <tr class="table-row border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4 text-gray-800"><?=htmlspecialchars($s['nom'])?></td>
                            <td class="py-3 px-4 text-gray-800"><?=htmlspecialchars($s['prenom'])?></td>
                            <td class="py-3 px-4 text-gray-600"><?=htmlspecialchars($s['email'])?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
