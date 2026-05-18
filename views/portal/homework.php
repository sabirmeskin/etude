<div class="max-w-5xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">Devoirs demandes</h2>
    <?php if (empty($devoirs)): ?>
        <p class="text-gray-600 bg-white p-6 rounded shadow">Aucun devoir pour le moment.</p>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($devoirs as $d): ?>
                <div class="bg-white p-5 rounded-lg shadow border-l-4 border-indigo-500">
                    <div class="flex flex-wrap justify-between gap-2">
                        <h3 class="text-lg font-bold text-gray-800"><?=htmlspecialchars($d['titre'] ?? '')?></h3>
                        <?php if (!empty($d['date_limite'])): ?>
                            <span class="text-sm bg-indigo-50 text-indigo-800 px-2 py-1 rounded">Pour le <?=htmlspecialchars(date('d/m/Y', strtotime($d['date_limite'])))?></span>
                        <?php endif; ?>
                    </div>
                    <p class="text-sm text-gray-500 mt-1"><?=htmlspecialchars($d['matiere_nom'] ?? '')?> — <?=htmlspecialchars($d['auteur_nom'] ?? '')?></p>
                    <?php if (!empty($d['consigne'])): ?>
                        <p class="text-gray-700 mt-3 whitespace-pre-wrap"><?=htmlspecialchars($d['consigne'])?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
