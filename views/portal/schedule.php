<div class="max-w-5xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">Emploi du temps</h2>
    <?php if (empty($schedules)): ?>
        <p class="text-gray-600 bg-white p-6 rounded shadow">Aucun creneau pour votre classe.</p>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-3 text-left">Jour</th>
                        <th class="border p-3 text-left">Matiere</th>
                        <th class="border p-3 text-left">Debut</th>
                        <th class="border p-3 text-left">Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $row): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border p-3"><?=htmlspecialchars($row['jour'] ?? '')?></td>
                            <td class="border p-3"><?=htmlspecialchars($row['matiere_nom'] ?? '')?></td>
                            <td class="border p-3"><?=htmlspecialchars(substr($row['heure_debut'] ?? '', 0, 5))?></td>
                            <td class="border p-3"><?=htmlspecialchars(substr($row['heure_fin'] ?? '', 0, 5))?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
