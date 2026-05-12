<div class="max-w-2xl mx-auto">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier le créneau</h2>

        <form method="post" class="space-y-6">
            <!-- Classe -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Classe *</label>
                <select name="classe_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Sélectionner une classe --</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?=$class['id']?>" <?=$schedule['classe_id'] == $class['id'] ? 'selected' : ''?>>
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
                        <option value="<?=$subject['id']?>" <?=$schedule['matiere_id'] == $subject['id'] ? 'selected' : ''?>>
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
                    <option value="Lundi" <?=$schedule['jour'] == 'Lundi' ? 'selected' : ''?>>Lundi</option>
                    <option value="Mardi" <?=$schedule['jour'] == 'Mardi' ? 'selected' : ''?>>Mardi</option>
                    <option value="Mercredi" <?=$schedule['jour'] == 'Mercredi' ? 'selected' : ''?>>Mercredi</option>
                    <option value="Jeudi" <?=$schedule['jour'] == 'Jeudi' ? 'selected' : ''?>>Jeudi</option>
                    <option value="Vendredi" <?=$schedule['jour'] == 'Vendredi' ? 'selected' : ''?>>Vendredi</option>
                    <option value="Samedi" <?=$schedule['jour'] == 'Samedi' ? 'selected' : ''?>>Samedi</option>
                </select>
            </div>

            <!-- Heure Début -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Heure de début *</label>
                <input type="time" name="heure_debut" value="<?=htmlspecialchars($schedule['heure_debut'])?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Heure Fin -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Heure de fin *</label>
                <input type="time" name="heure_fin" value="<?=htmlspecialchars($schedule['heure_fin'])?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Boutons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
                <a href="/index.php?r=schedules" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition text-center">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
