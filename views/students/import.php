<div class="max-w-2xl mx-auto">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Importer des étudiants (CSV)</h2>
        <p class="text-sm text-gray-600 mb-4">Le fichier CSV doit contenir un en-tête avec: <strong>nom,prenom,email,classe_id</strong></p>

        <form method="post" action="/index.php?r=students/uploadImport" enctype="multipart/form-data" class="space-y-4">
            <div>
                <input type="file" name="csv" accept="text/csv,application/vnd.ms-excel" required>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Importer</button>
                <a href="/index.php?r=students" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">Annuler</a>
            </div>
        </form>
    </div>
</div>
