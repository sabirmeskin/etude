<div class="max-w-2xl mx-auto">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Assigner un étudiant à une classe</h2>

        <form method="post" action="/index.php?r=classes/assign" class="space-y-6">
            <!-- Classe -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Classe *</label>
                <select name="class_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Sélectionnez une classe --</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?=$c['id']?>"><?=htmlspecialchars($c['nom'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Étudiant -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Étudiant *</label>
                <select name="student_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Sélectionnez un étudiant --</option>
                    <?php foreach ($students as $s): ?>
                        <option value="<?=$s['id']?>"><?=htmlspecialchars($s['nom'].' '.$s['prenom'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Boutons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="btn-primary flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition hover:bg-blue-700">
                    <i class="fas fa-link mr-2"></i> Assigner
                </button>
                <a href="/index.php?r=classes" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition text-center">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
