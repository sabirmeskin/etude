<div class="max-w-3xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">Assigner des classes aux professeurs</h2>
    <p class="text-gray-600 text-sm">Les professeurs ne voient et ne saisissent des notes et devoirs que pour les classes listees ici.</p>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded"><?=htmlspecialchars($success)?></div>
    <?php endif; ?>

    <?php if (empty($professeurs)): ?>
        <p class="text-gray-600">Aucun compte professeur. Les enseignants peuvent s inscrire avec le role Professeur sur la page d inscription.</p>
    <?php else: ?>
        <form method="get" action="/index.php" class="bg-white p-4 rounded shadow flex flex-wrap gap-3 items-end">
            <input type="hidden" name="r" value="admin/teacherClasses">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Professeur</label>
                <select name="utilisateur_id" class="border rounded-lg px-3 py-2 min-w-[220px]" onchange="this.form.submit()">
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
            <form method="post" action="/index.php?r=admin/teacherClasses" class="bg-white p-6 rounded shadow space-y-4">
                <input type="hidden" name="utilisateur_id" value="<?=(int) $selected_id?>">
                <h3 class="font-semibold text-gray-800">Classes pour ce professeur</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <?php foreach ($classes as $c): ?>
                        <label class="flex items-center gap-2 border rounded p-3 cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" name="classe_ids[]" value="<?=(int) $c['id']?>" <?= in_array((int) $c['id'], $selected_classes ?? [], true) ? 'checked' : '' ?>>
                            <span><?=htmlspecialchars($c['nom'])?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Enregistrer</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>
