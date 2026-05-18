<div class="max-w-2xl mx-auto">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Nouveau devoir</h2>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php foreach ($errors as $err): ?>
                    <p><?=htmlspecialchars($err)?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($reloadOnClassChange) && empty($prefClasse)): ?>
            <div class="bg-blue-50 border border-blue-200 text-blue-900 px-4 py-3 rounded mb-4 text-sm">
                Choisissez d'abord une <strong>classe</strong> : la liste des matières correspond à vos affectations admin pour cette classe.
            </div>
        <?php endif; ?>

        <form method="post" action="/index.php?r=homework/create" class="space-y-4">
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Classe</label>
                <select name="classe_id" id="hwClasse" class="w-full border rounded-lg px-3 py-2" required
                    <?= !empty($reloadOnClassChange) ? ' onchange="if(this.value){window.location=\'/index.php?r=homework/create&classe_id=\'+this.value;}"' : '' ?>>
                    <option value="">— Choisir —</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?=(int) $c['id']?>" <?= (int) ($prefClasse ?? 0) === (int) $c['id'] || (string)($old['classe_id'] ?? '') === (string) $c['id'] ? 'selected' : '' ?>><?=htmlspecialchars($c['nom'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Matiere</label>
                <select name="matiere_id" class="w-full border rounded-lg px-3 py-2" required>
                    <option value="">— Choisir —</option>
                    <?php foreach ($subjects as $s): ?>
                        <option value="<?=(int) $s['id']?>" <?= (string)($old['matiere_id'] ?? '') === (string) $s['id'] ? 'selected' : '' ?>><?=htmlspecialchars($s['nom'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Titre</label>
                <input type="text" name="titre" value="<?=htmlspecialchars($old['titre'] ?? '')?>" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Consigne (optionnel)</label>
                <textarea name="consigne" rows="4" class="w-full border rounded-lg px-3 py-2"><?=htmlspecialchars($old['consigne'] ?? '')?></textarea>
            </div>
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Date limite (optionnel)</label>
                <input type="date" name="date_limite" value="<?=htmlspecialchars($old['date_limite'] ?? '')?>" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Enregistrer</button>
                <a href="/index.php?r=homework" class="px-6 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Annuler</a>
            </div>
        </form>
    </div>
</div>
