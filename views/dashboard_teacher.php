<?php
$classes = $teacherClassIds ?? [];
$nbClasses = count($classes);
?>
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Espace professeur</h2>
        <p class="text-gray-600">Saisissez les notes et les devoirs pour vos classes assignees.</p>
    </div>

    <?php if ($nbClasses === 0): ?>
        <div class="bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 rounded-lg">
            <p class="font-semibold">Aucune classe assignee</p>
            <p class="text-sm mt-1">Demandez a l administrateur de vous attribuer des classes (menu Admin).</p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="/index.php?r=notes/bulk" class="stat-card bg-white border-l-4 border-green-600 p-6 rounded shadow block hover:shadow-md transition">
            <p class="text-gray-600 text-sm font-medium">Notes</p>
            <p class="text-xl font-bold mt-2 text-gray-800">Saisie par classe</p>
            <p class="text-green-600 mt-4 font-medium">Ouvrir <i class="fas fa-arrow-right ml-1"></i></p>
        </a>
        <a href="/index.php?r=homework" class="stat-card bg-white border-l-4 border-indigo-600 p-6 rounded shadow block hover:shadow-md transition">
            <p class="text-gray-600 text-sm font-medium">Devoirs</p>
            <p class="text-xl font-bold mt-2 text-gray-800">Publier pour un groupe</p>
            <p class="text-indigo-600 mt-4 font-medium">Ouvrir <i class="fas fa-arrow-right ml-1"></i></p>
        </a>
        <a href="/index.php?r=schedules" class="stat-card bg-white border-l-4 border-blue-600 p-6 rounded shadow block hover:shadow-md transition">
            <p class="text-gray-600 text-sm font-medium">Emploi du temps</p>
            <p class="text-xl font-bold mt-2 text-gray-800">Consultation</p>
            <p class="text-blue-600 mt-4 font-medium">Ouvrir <i class="fas fa-arrow-right ml-1"></i></p>
        </a>
    </div>
</div>
