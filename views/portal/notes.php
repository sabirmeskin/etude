<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Mes notes</h2>
            <p class="text-gray-600">Moyenne generale : <strong class="text-lg"><?=$avg !== null ? htmlspecialchars((string) $avg) : '—'?></strong></p>
        </div>
        <form method="get" action="/index.php" class="flex gap-2">
            <input type="hidden" name="r" value="portal/notes">
            <input type="text" name="search" value="<?=htmlspecialchars($search ?? '')?>" placeholder="Filtrer par matiere..." class="px-3 py-2 border rounded-lg">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Filtrer</button>
        </form>
    </div>

    <?php if (!empty($stats)): ?>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-semibold text-gray-800 mb-3">Par matiere</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <?php foreach ($stats as $s): ?>
                    <div class="border rounded p-3 flex justify-between">
                        <span><?=htmlspecialchars($s['matiere'] ?? '')?></span>
                        <span class="font-semibold"><?=htmlspecialchars((string) round((float) ($s['moyenne'] ?? 0), 2))?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left p-3">Matiere</th>
                    <th class="text-left p-3">Note</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($notes)): ?>
                    <tr><td colspan="2" class="p-4 text-gray-500">Aucune note.</td></tr>
                <?php else: ?>
                    <?php foreach ($notes as $n): ?>
                        <tr class="border-t">
                            <td class="p-3"><?=htmlspecialchars($n['matiere_nom'] ?? '')?></td>
                            <td class="p-3 font-semibold"><?=htmlspecialchars((string) ($n['note'] ?? ''))?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (($pager['pages'] ?? 1) > 1): ?>
        <div class="flex gap-2 justify-center">
            <?php for ($p = 1; $p <= (int) $pager['pages']; $p++): ?>
                <a href="/index.php?r=portal/notes&page=<?=$p?>&search=<?=urlencode($search ?? '')?>" class="px-3 py-1 rounded <?= $p === (int) $pager['page'] ? 'bg-blue-600 text-white' : 'bg-gray-200' ?>"><?=$p?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
