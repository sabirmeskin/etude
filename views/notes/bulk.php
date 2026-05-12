<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Saisie groupée des notes</h2>
            <p class="text-gray-600 mt-1">Entrez les notes pour une matière et une classe à la fois</p>
        </div>
        <a href="/index.php?r=notes" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <!-- Success Message -->
    <?php if (!empty($_GET['saved'])): ?>
        <div class="bg-green-50 border-l-4 border-green-600 p-4 rounded-lg flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600 text-xl"></i>
            <div>
                <p class="font-semibold text-green-800">Notes sauvegardées avec succès!</p>
                <p class="text-green-700 text-sm">Les notes ont été enregistrées dans le système.</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Selection Form -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form method="get" action="/index.php?r=notes/bulk" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="hidden" name="r" value="notes/bulk">

            <!-- Class Selection -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Classe *</label>
                <select name="class_id" id="classSelect" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="this.form.submit()">
                    <option value="">-- Sélectionnez une classe --</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?=$c['id']?>" <?=$selectedClass == $c['id'] ? 'selected' : ''?>>
                            <?=htmlspecialchars($c['nom'])?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Subject Selection -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Matière *</label>
                <select name="subject_id" id="subjectSelect" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="this.form.submit()">
                    <option value="">-- Sélectionnez une matière --</option>
                    <?php foreach ($subjects as $s): ?>
                        <option value="<?=$s['id']?>" <?=$selectedSubject == $s['id'] ? 'selected' : ''?>>
                            <?=htmlspecialchars($s['nom'])?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    <i class="fas fa-filter mr-2"></i> Afficher
                </button>
            </div>
        </form>
    </div>

    <!-- Grades Table -->
    <?php if (!empty($students) && $selectedClass && $selectedSubject): ?>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h3 class="text-lg font-bold text-white">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Entrez les notes
                </h3>
                <p class="text-blue-100 text-sm mt-1">
                    <?php
                    $class = null;
                    $subject = null;
                    foreach ($classes as $c) {
                        if ($c['id'] == $selectedClass) {
                            $class = $c;
                            break;
                        }
                    }
                    foreach ($subjects as $s) {
                        if ($s['id'] == $selectedSubject) {
                            $subject = $s;
                            break;
                        }
                    }
                    ?>
                    <?=htmlspecialchars($subject['nom'] ?? '')?> - <?=htmlspecialchars($class['nom'] ?? '')?>
                </p>
            </div>

            <form method="post" action="/index.php?r=notes/saveBulk" class="p-6">
                <input type="hidden" name="class_id" value="<?=htmlspecialchars($selectedClass)?>">
                <input type="hidden" name="subject_id" value="<?=htmlspecialchars($selectedSubject)?>">

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-200">
                                <th class="text-left py-4 px-6 text-gray-700 font-semibold">ID</th>
                                <th class="text-left py-4 px-6 text-gray-700 font-semibold">Nom</th>
                                <th class="text-left py-4 px-6 text-gray-700 font-semibold">Prénom</th>
                                <th class="text-center py-4 px-6 text-gray-700 font-semibold">Note actuelle</th>
                                <th class="text-center py-4 px-6 text-gray-700 font-semibold">Nouvelle note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr class="border-b border-gray-100 hover:bg-blue-50 transition">
                                    <td class="py-4 px-6 text-gray-800 font-medium"><?=htmlspecialchars($student['id'])?></td>
                                    <td class="py-4 px-6 text-gray-800"><?=htmlspecialchars($student['nom'])?></td>
                                    <td class="py-4 px-6 text-gray-800"><?=htmlspecialchars($student['prenom'])?></td>
                                    <td class="py-4 px-6 text-center">
                                        <?php if (!empty($student['existing_grade'])): ?>
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                                <?=htmlspecialchars($student['existing_grade'])?>/20
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-sm">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <input type="number" 
                                               name="grade_<?=$student['id']?>" 
                                               step="0.5" 
                                               min="0" 
                                               max="20" 
                                               placeholder="0-20"
                                               value="<?=htmlspecialchars($student['existing_grade'] ?? '')?>"
                                               class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-6 border-t">
                    <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Sauvegarder toutes les notes
                    </button>
                    <a href="/index.php?r=notes/bulk" class="flex-1 bg-gray-300 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-400 transition font-semibold text-center flex items-center justify-center gap-2">
                        <i class="fas fa-redo"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded-lg">
            <p class="text-blue-800">
                <i class="fas fa-info-circle mr-2"></i>
                <strong><?=count($students)?> étudiant(es)</strong> dans cette classe. Entrez les notes et cliquez sur <strong>"Sauvegarder toutes les notes"</strong>.
            </p>
        </div>
    <?php elseif ($selectedClass && !$selectedSubject): ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-600 p-4 rounded-lg">
            <p class="text-yellow-800">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Veuillez sélectionner une matière pour continuer.
            </p>
        </div>
    <?php elseif (!$selectedClass): ?>
        <div class="bg-gray-50 border-l-4 border-gray-600 p-4 rounded-lg">
            <p class="text-gray-700">
                <i class="fas fa-arrow-up mr-2"></i>
                Sélectionnez une classe et une matière pour afficher les étudiants.
            </p>
        </div>
    <?php endif; ?>
</div>

<script>
// Helper function for array_find (since PHP doesn't have it in older versions)
// This is just for the template helper, actual find is done in PHP
</script>
