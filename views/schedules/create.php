<div class="max-w-2xl mx-auto">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Ajouter un créneau d'emploi du temps</h2>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5">
                    <?php foreach ($errors as $error): ?>
                        <li><?=htmlspecialchars($error)?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-6">
            <!-- Classe -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Classe *</label>
                <select name="classe_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Sélectionner une classe --</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?=$class['id']?>" <?=($old['classe_id'] ?? '') == $class['id'] ? 'selected' : ''?>>
                            <?=htmlspecialchars($class['nom'])?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Matière -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Matière *</label>
                <select name="matiere_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Sélectionner une matière --</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?=$subject['id']?>" <?=($old['matiere_id'] ?? '') == $subject['id'] ? 'selected' : ''?>>
                            <?=htmlspecialchars($subject['nom'])?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Jour -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Jour *</label>
                <select name="jour" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Sélectionner un jour --</option>
                    <option value="Lundi" <?=($old['jour'] ?? '') == 'Lundi' ? 'selected' : ''?>>Lundi</option>
                    <option value="Mardi" <?=($old['jour'] ?? '') == 'Mardi' ? 'selected' : ''?>>Mardi</option>
                    <option value="Mercredi" <?=($old['jour'] ?? '') == 'Mercredi' ? 'selected' : ''?>>Mercredi</option>
                    <option value="Jeudi" <?=($old['jour'] ?? '') == 'Jeudi' ? 'selected' : ''?>>Jeudi</option>
                    <option value="Vendredi" <?=($old['jour'] ?? '') == 'Vendredi' ? 'selected' : ''?>>Vendredi</option>
                    <option value="Samedi" <?=($old['jour'] ?? '') == 'Samedi' ? 'selected' : ''?>>Samedi</option>
                </select>
            </div>

            <!-- Heure Début -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Heure de début *</label>
                <input type="time" name="heure_debut" value="<?=$old['heure_debut'] ?? ''?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Heure Fin -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Heure de fin *</label>
                <input type="time" name="heure_fin" value="<?=$old['heure_fin'] ?? ''?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Boutons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Ajouter
                </button>
                <a href="/index.php?r=schedules" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition text-center">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
