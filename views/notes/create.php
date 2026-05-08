<div class="max-w-2xl mx-auto">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Ajouter une note</h2>

        <form method="post" action="/index.php?r=notes/create" class="space-y-6">
            <!-- Étudiant -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Étudiant *</label>
                <select name="student_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    <option value="">-- Sélectionnez un étudiant --</option>
                    <?php foreach ($students as $s): ?>
                        <option value="<?=$s['id']?>"><?=htmlspecialchars($s['nom'].' '.$s['prenom'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Matière -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Matière *</label>
                <select name="matiere_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    <option value="">-- Sélectionnez une matière --</option>
                    <?php foreach ($subjects as $sub): ?>
                        <option value="<?=$sub['id']?>"><?=htmlspecialchars($sub['nom'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Note -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Note (0-20) *</label>
                <input name="note" type="number" step="0.5" min="0" max="20" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="ex: 15.5" required>
            </div>

            <!-- Boutons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="btn-primary flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i> Ajouter la note
                </button>
                <a href="/index.php?r=dashboard" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition text-center">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
