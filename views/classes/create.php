<div class="max-w-2xl mx-auto">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Ajouter une classe</h2>

        <form method="post" action="/index.php?r=classes/create" class="space-y-6">
            <!-- Nom de la classe -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nom de la classe *</label>
                <input name="nom" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="ex: 1ère A, Terminale B" required>
            </div>

            <!-- Boutons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" class="btn-primary flex-1 bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition hover:bg-green-700">
                    <i class="fas fa-save mr-2"></i> Créer la classe
                </button>
                <a href="/index.php?r=classes" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition text-center">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
