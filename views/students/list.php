<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestion des Etudiants</h2>
            <p class="text-gray-600 mt-1">Total: <?= count($students) ?> étudiants</p>
        </div>
        <div class="flex gap-3">
            <a href="/index.php?r=students/create" class="btn-primary bg-blue-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Ajouter un étudiant
            </a>
            <a href="/index.php?r=students/import" class="bg-yellow-600 text-white px-4 py-3 rounded-lg hover:bg-yellow-700 transition">
                <i class="fas fa-file-import mr-2"></i> Import CSV
            </a>
            <a href="/index.php?r=students/export" class="bg-gray-200 text-gray-800 px-4 py-3 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-file-csv mr-2"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-lg p-4">
        <form method="get" action="/index.php?r=students" class="flex gap-2">
            <input type="hidden" name="r" value="students">
            <input type="text" name="search" placeholder="Rechercher par nom, prénom ou email..." value="<?=htmlspecialchars($search ?? '')?>" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-search mr-2"></i> Rechercher
            </button>
            <?php if ($search): ?>
                <a href="/index.php?r=students" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                    <i class="fas fa-times mr-2"></i> Réinitialiser
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">ID</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Nom</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Prénom</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Email</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                        <tr class="table-row border-b border-gray-100 hover:bg-blue-50 transition">
                            <td class="py-4 px-6 text-gray-800 font-medium"><?=htmlspecialchars($s['id'])?></td>
                            <td class="py-4 px-6 text-gray-800"><?=htmlspecialchars($s['nom'])?></td>
                            <td class="py-4 px-6 text-gray-800"><?=htmlspecialchars($s['prenom'])?></td>
                            <td class="py-4 px-6 text-gray-600"><?=htmlspecialchars($s['email'])?></td>
                            <td class="py-4 px-6">
                                <div class="flex gap-3">
                                    <a href="/index.php?r=students/show&id=<?=$s['id']?>" class="text-gray-600 hover:text-gray-800 font-medium transition">
                                        <i class="fas fa-user"></i> Profil
                                    </a>
                                    <a href="/index.php?r=students/edit&id=<?=$s['id']?>" class="text-blue-600 hover:text-blue-800 font-medium transition">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="/index.php?r=notes&student_id=<?=$s['id']?>" class="text-purple-600 hover:text-purple-800 font-medium transition">
                                        <i class="fas fa-chart-bar"></i> Notes
                                    </a>
                                    <a href="/index.php?r=students/delete&id=<?=$s['id']?>" class="text-red-600 hover:text-red-800 font-medium transition" onclick="return confirm('Confirmer la suppression ?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (!empty($pager['pages']) && $pager['pages'] > 1): ?>
        <div class="flex justify-center gap-2 flex-wrap">
            <?php for ($page = 1; $page <= $pager['pages']; $page++): ?>
                <a href="/index.php?r=students&search=<?=urlencode($search ?? '')?>&page=<?=$page?>" class="px-4 py-2 rounded <?=((int)($pager['page'] ?? 1) === $page) ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300'?>">
                    <?=$page?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
