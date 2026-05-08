<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestion des Matières</h2>
            <p class="text-gray-600 mt-1">Liste des matières</p>
        </div>
        <a href="/index.php?r=matieres/create" class="btn-primary bg-green-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i> Ajouter une matière
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">ID</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Nom</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Description</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $s): ?>
                        <tr class="table-row border-b border-gray-100 hover:bg-green-50 transition">
                            <td class="py-4 px-6 text-gray-800 font-medium"><?=htmlspecialchars($s['id'])?></td>
                            <td class="py-4 px-6 text-gray-800"><?=htmlspecialchars($s['nom'])?></td>
                            <td class="py-4 px-6 text-gray-600"><?=htmlspecialchars($s['description'])?></td>
                            <td class="py-4 px-6">
                                <div class="flex gap-3">
                                    <a href="/index.php?r=matieres/edit&id=<?=$s['id']?>" class="text-blue-600 hover:text-blue-800 font-medium transition">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="/index.php?r=matieres/delete&id=<?=$s['id']?>" class="text-red-600 hover:text-red-800 font-medium transition" onclick="return confirm('Confirmer la suppression ?')">
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
</div>
