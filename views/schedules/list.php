<div class="max-w-6xl mx-auto">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Emploi du Temps</h2>
            <a href="/index.php?r=schedules/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Ajouter un créneaux
            </a>
        </div>

        <?php if (empty($schedules)): ?>
            <p class="text-gray-600 text-center py-8">Aucun créneau d'emploi du temps. <a href="/index.php?r=schedules/create" class="text-blue-600 hover:underline">Créer un</a></p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 p-3 text-left">Classe</th>
                            <th class="border border-gray-300 p-3 text-left">Matière</th>
                            <th class="border border-gray-300 p-3 text-left">Jour</th>
                            <th class="border border-gray-300 p-3 text-left">Heure Début</th>
                            <th class="border border-gray-300 p-3 text-left">Heure Fin</th>
                            <th class="border border-gray-300 p-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedules as $schedule): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-300 p-3"><?=htmlspecialchars($schedule['classe_nom'] ?? 'N/A')?></td>
                                <td class="border border-gray-300 p-3"><?=htmlspecialchars($schedule['matiere_nom'] ?? 'N/A')?></td>
                                <td class="border border-gray-300 p-3"><?=htmlspecialchars($schedule['jour'])?></td>
                                <td class="border border-gray-300 p-3"><?=htmlspecialchars($schedule['heure_debut'])?></td>
                                <td class="border border-gray-300 p-3"><?=htmlspecialchars($schedule['heure_fin'])?></td>
                                <td class="border border-gray-300 p-3 text-center">
                                    <a href="/index.php?r=schedules/edit&id=<?=$schedule['id']?>" class="text-blue-600 hover:underline mr-2">Modifier</a>
                                    <a href="/index.php?r=schedules/delete&id=<?=$schedule['id']?>" class="text-red-600 hover:underline" onclick="return confirm('Êtes-vous sûr?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
