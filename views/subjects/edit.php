<div class="max-w-2xl mx-auto">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier la matière</h2>

        <form method="post" action="/index.php?r=matieres/edit&id=<?=$subject['id']?>" class="space-y-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nom *</label>
                <input name="nom" type="text" value="<?=htmlspecialchars($subject['nom'])?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Description</label>
                <textarea name="description" class="w-full px-4 py-3 border border-gray-300 rounded-lg" rows="4"><?=htmlspecialchars($subject['description'])?></textarea>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="btn-primary flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
                <a href="/index.php?r=matieres" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold text-center">Annuler</a>
            </div>
        </form>
    </div>
</div>
