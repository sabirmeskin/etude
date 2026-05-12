<div class="max-w-2xl mx-auto">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier l'étudiant</h2>

        <form method="post" action="/index.php?r=students/edit&id=<?=$student['id']?>" class="space-y-6">
            <!-- Nom -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nom *</label>
                <input name="nom" type="text" value="<?=htmlspecialchars($student['nom'])?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Prénom -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Prénom *</label>
                <input name="prenom" type="text" value="<?=htmlspecialchars($student['prenom'])?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                <input name="email" type="email" value="<?=htmlspecialchars($student['email'])?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Boutons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="btn-primary flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
                <a href="/index.php?r=students" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition text-center">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Photo -->
<div class="max-w-2xl mx-auto mt-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-bold mb-4">Photo</h3>
        <?php if (!empty($student['photo'])): ?>
            <img src="<?=htmlspecialchars(resolvePhotoPath($student['photo']))?>" alt="Photo" class="w-32 h-32 object-cover rounded">
        <?php else: ?>
            <div class="w-32 h-32 bg-gray-100 rounded flex items-center justify-center">Pas de photo</div>
        <?php endif; ?>

        <form method="post" action="/index.php?r=students/edit&id=<?=$student['id']?>" enctype="multipart/form-data" class="mt-4">
            <label class="block mb-2">Changer la photo
                <input type="file" name="photo" accept="image/*" class="block">
            </label>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Uploader</button>
        </form>
    </div>
</div>
