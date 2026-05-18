<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Mon espace eleve</h2>
        <p class="text-gray-600">
            <?=htmlspecialchars(($student['prenom'] ?? '') . ' ' . ($student['nom'] ?? ''))?>
            <?php if (!empty($class)): ?>
                <span class="text-gray-500"> — <?=htmlspecialchars($class['nom'] ?? '')?></span>
            <?php endif; ?>
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="/index.php?r=portal/schedule" class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-600 hover:shadow-md transition block">
            <h3 class="font-bold text-gray-800">Emploi du temps</h3>
            <p class="text-sm text-gray-600 mt-2">Voir les creneaux de votre classe</p>
        </a>
        <a href="/index.php?r=portal/notes" class="bg-white p-6 rounded-lg shadow border-l-4 border-green-600 hover:shadow-md transition block">
            <h3 class="font-bold text-gray-800">Mes notes</h3>
            <p class="text-sm text-gray-600 mt-2">Moyenne : <strong><?=$avg !== null ? htmlspecialchars((string) $avg) : '—'?></strong></p>
        </a>
        <a href="/index.php?r=portal/homework" class="bg-white p-6 rounded-lg shadow border-l-4 border-indigo-600 hover:shadow-md transition block">
            <h3 class="font-bold text-gray-800">Devoirs</h3>
            <p class="text-sm text-gray-600 mt-2"><?= (int) $devoirsCount ?> devoir(s) pour votre classe</p>
        </a>
    </div>
</div>
