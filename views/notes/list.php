<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Notes de <?=htmlspecialchars($student['nom'].' '.$student['prenom'])?></h2>
            <p class="text-gray-600 mt-1">Moyenne générale: <span class="text-2xl font-bold text-purple-600"><?= $avg ?? 'N/A' ?>/20</span></p>
        </div>
        <a href="/index.php?r=notes/create" class="btn-primary bg-blue-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Ajouter une note
        </a>
    </div>

    <form method="get" action="/index.php" class="bg-white rounded-lg shadow-lg p-4 flex flex-col md:flex-row gap-3 items-stretch md:items-end">
        <input type="hidden" name="r" value="notes">
        <input type="hidden" name="student_id" value="<?=htmlspecialchars($student['id'])?>">
        <div class="flex-1">
            <label class="block text-gray-700 font-semibold mb-2">Recherche rapide</label>
            <input type="text" name="search" value="<?=htmlspecialchars($search ?? '')?>" placeholder="Filtrer par matière..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>
        <button type="submit" class="bg-purple-600 text-white px-5 py-3 rounded-lg hover:bg-purple-700">Filtrer</button>
        <?php if (!empty($search)): ?>
            <a href="/index.php?r=notes&student_id=<?=$student['id']?>" class="bg-gray-200 text-gray-800 px-5 py-3 rounded-lg hover:bg-gray-300 text-center">Réinitialiser</a>
        <?php endif; ?>
    </form>

    <!-- Grade Statistics by Subject -->
    <?php if (!empty($stats)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($stats as $stat): ?>
            <div class="bg-white rounded-lg shadow-lg p-4">
                <h3 class="font-bold text-gray-800 mb-3"><?=htmlspecialchars($stat['matiere'] ?? 'N/A')?></h3>
                <div class="space-y-2 text-sm">
                    <p class="text-gray-600">Moyenne: <span class="font-bold text-purple-600"><?=round($stat['moyenne'] ?? 0, 2)?>/20</span></p>
                    <p class="text-gray-600">Max: <span class="font-bold text-green-600"><?=htmlspecialchars($stat['max_note'])?></span></p>
                    <p class="text-gray-600">Min: <span class="font-bold text-red-600"><?=htmlspecialchars($stat['min_note'])?></span></p>
                    <p class="text-gray-600">Nombre de notes: <span class="font-bold"><?=htmlspecialchars($stat['count'])?></span></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Table des notes -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Matière</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Note</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Appréciation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notes as $n): 
                        $note_val = floatval($n['note']);
                        if ($note_val >= 16) $color = 'text-green-600 bg-green-50';
                        elseif ($note_val >= 12) $color = 'text-blue-600 bg-blue-50';
                        elseif ($note_val >= 10) $color = 'text-yellow-600 bg-yellow-50';
                        else $color = 'text-red-600 bg-red-50';
                        
                        if ($note_val >= 16) $appreciation = 'Excellent';
                        elseif ($note_val >= 12) $appreciation = 'Bon';
                        elseif ($note_val >= 10) $appreciation = 'Moyen';
                        else $appreciation = 'À améliorer';
                    ?>
                        <tr class="table-row border-b border-gray-100 hover:bg-purple-50 transition">
                            <td class="py-4 px-6 text-gray-800 font-medium"><?=htmlspecialchars($n['matiere_nom'] ?? 'N/A')?></td>
                            <td class="py-4 px-6"><span class="<?=$color?> px-3 py-1 rounded-full font-bold"><?=htmlspecialchars($n['note'])?>/20</span></td>
                            <td class="py-4 px-6 text-gray-600"><?=$appreciation?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (!empty($pager['pages']) && $pager['pages'] > 1): ?>
        <div class="flex justify-center gap-2 flex-wrap">
            <?php for ($page = 1; $page <= $pager['pages']; $page++): ?>
                <a href="/index.php?r=notes&student_id=<?=$student['id']?>&search=<?=urlencode($search ?? '')?>&page=<?=$page?>" class="px-4 py-2 rounded <?=((int)($pager['page'] ?? 1) === $page) ? 'bg-purple-600 text-white' : 'bg-white text-gray-700 border border-gray-300'?>">
                    <?=$page?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
