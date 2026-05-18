<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Devoirs</h2>
        <a href="/index.php?r=homework/create" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            <i class="fas fa-plus mr-2"></i> Nouveau devoir
        </a>
    </div>

    <?php if (!empty($_GET['saved'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-2 rounded">Devoir enregistre.</div>
    <?php endif; ?>

    <?php if (empty($devoirs)): ?>
        <p class="text-gray-600 bg-white p-6 rounded shadow">Aucun devoir. Creez-en un pour une de vos classes.</p>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left p-3">Classe</th>
                        <th class="text-left p-3">Matiere</th>
                        <th class="text-left p-3">Titre</th>
                        <th class="text-left p-3">Date limite</th>
                        <th class="text-center p-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($devoirs as $d): ?>
                        <tr class="border-t">
                            <td class="p-3"><?=htmlspecialchars($d['classe_nom'] ?? '')?></td>
                            <td class="p-3"><?=htmlspecialchars($d['matiere_nom'] ?? '')?></td>
                            <td class="p-3"><?=htmlspecialchars($d['titre'] ?? '')?></td>
                            <td class="p-3"><?=!empty($d['date_limite']) ? htmlspecialchars(date('d/m/Y', strtotime($d['date_limite']))) : '—'?></td>
                            <td class="p-3 text-center">
                                <?php
                                $canDelete = (($currentUser['role'] ?? '') === 'admin')
                                    || (int) ($d['created_by'] ?? 0) === (int) ($currentUser['id'] ?? 0);
                                ?>
                                <?php if ($canDelete): ?>
                                <a href="/index.php?r=homework/delete&id=<?=(int) ($d['id'] ?? 0)?>" class="text-red-600 hover:underline" onclick="return confirm('Supprimer ce devoir ?')">Supprimer</a>
                                <?php else: ?>
                                <span class="text-gray-400 text-sm">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
