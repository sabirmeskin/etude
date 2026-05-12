<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Classe: <?=htmlspecialchars($class['nom'])?></h2>
            <p class="text-gray-600 mt-1">Gestion du roster et des présences</p>
        </div>
        <a href="/index.php?r=classes" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">Retour</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-sm text-gray-600">Présents</p>
            <p class="text-3xl font-bold text-green-600"><?=htmlspecialchars($summary['present_count'] ?? 0)?></p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-sm text-gray-600">Absents</p>
            <p class="text-3xl font-bold text-red-600"><?=htmlspecialchars($summary['absent_count'] ?? 0)?></p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-sm text-gray-600">Date</p>
            <p class="text-3xl font-bold text-gray-800"><?=htmlspecialchars($date)?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <form method="get" action="/index.php" class="flex gap-3 items-end mb-6">
            <input type="hidden" name="r" value="classes/roster">
            <input type="hidden" name="id" value="<?=$class['id']?>">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Date</label>
                <input type="date" name="date" value="<?=htmlspecialchars($date)?>" class="border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">Afficher</button>
        </form>

        <form method="post" action="/index.php?r=classes/roster&id=<?=$class['id']?>&date=<?=urlencode($date)?>">
            <input type="hidden" name="date" value="<?=htmlspecialchars($date)?>">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4">Étudiant</th>
                        <th class="text-left py-3 px-4">Email</th>
                        <th class="text-left py-3 px-4">Présence</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4"><?=htmlspecialchars($student['nom'] . ' ' . $student['prenom'])?></td>
                            <td class="py-3 px-4 text-gray-600"><?=htmlspecialchars($student['email'])?></td>
                            <td class="py-3 px-4">
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="attendance[<?=$student['id']?>]" value="1" <?=((int)($student['present'] ?? 0) === 1) ? 'checked' : ''?> class="h-4 w-4">
                                    <span>Présent</span>
                                </label>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">Enregistrer les présences</button>
            </div>
        </form>
    </div>
</div>