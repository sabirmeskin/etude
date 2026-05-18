<div class="max-w-4xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">Affectations professeurs (matiere + classe)</h2>
    <p class="text-gray-600 text-sm">
        Chaque ligne autorise le professeur a saisir des <strong>notes</strong> et des <strong>devoirs</strong> pour cette matiere dans cette classe.
        Les eleves voient les notes et devoirs sur leur compte selon leur <strong>classe</strong> (assigner les eleves via <a href="/index.php?r=classes/assign" class="text-blue-600 font-semibold underline">Classes &rarr; Assigner un etudiant</a>).
    </p>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded"><?=htmlspecialchars($success)?></div>
    <?php endif; ?>

    <?php if (empty($professeurs)): ?>
        <p class="text-gray-600">Aucun compte professeur.</p>
    <?php else: ?>
        <form method="get" action="/index.php" class="bg-white p-4 rounded shadow flex flex-wrap gap-3 items-end">
            <input type="hidden" name="r" value="admin/teacherAssignments">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Professeur</label>
                <select name="utilisateur_id" class="border rounded-lg px-3 py-2 min-w-[240px]" onchange="this.form.submit()">
                    <option value="">— Choisir —</option>
                    <?php foreach ($professeurs as $p): ?>
                        <option value="<?=(int) $p['id']?>" <?= (int) ($selected_id ?? 0) === (int) $p['id'] ? 'selected' : '' ?>>
                            <?=htmlspecialchars(($p['nom'] ?: '') . ' (' . $p['email'] . ')')?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if (!empty($selected_id)): ?>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-gray-800 mb-4">Affectations actuelles</h3>
                <?php if (empty($assignments)): ?>
                    <p class="text-gray-500 text-sm mb-4">Aucune affectation. Le professeur ne pourra pas saisir de notes ou devoirs tant qu'au moins une ligne n'est pas ajoutée.</p>
                <?php else: ?>
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-left p-2">Classe</th>
                                    <th class="text-left p-2">Matiere</th>
                                    <th class="text-right p-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignments as $a): ?>
                                    <tr class="border-t">
                                        <td class="p-2"><?=htmlspecialchars($a['classe_nom'] ?? '')?></td>
                                        <td class="p-2"><?=htmlspecialchars($a['matiere_nom'] ?? '')?></td>
                                        <td class="p-2 text-right">
                                            <a href="/index.php?r=admin/teacherAssignments&amp;utilisateur_id=<?=(int) $selected_id?>&amp;remove=<?=(int) ($a['id'] ?? 0)?>" class="text-red-600 hover:underline" onclick="return confirm('Supprimer cette affectation ?')">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <h3 class="font-semibold text-gray-800 mb-3">Ajouter une affectation</h3>
                <form method="post" action="/index.php?r=admin/teacherAssignments" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                    <input type="hidden" name="utilisateur_id" value="<?=(int) $selected_id?>">
                    <input type="hidden" name="add_assignment" value="1">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Classe</label>
                        <select name="classe_id" class="w-full border rounded-lg px-2 py-2" required>
                            <option value="">—</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?=(int) $c['id']?>"><?=htmlspecialchars($c['nom'])?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Matiere</label>
                        <select name="matiere_id" class="w-full border rounded-lg px-2 py-2" required>
                            <option value="">—</option>
                            <?php foreach ($matieres as $m): ?>
                                <option value="<?=(int) $m['id']?>"><?=htmlspecialchars($m['nom'])?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 w-full md:w-auto">Ajouter</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
