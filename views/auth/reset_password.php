<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="text-5xl mb-4">🔐</div>
                <h1 class="text-2xl font-bold text-gray-800">Nouveau mot de passe</h1>
            </div>

            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6"><?=htmlspecialchars($error)?></div>
            <?php endif; ?>

            <?php if (!empty($token)): ?>
                <form method="post" action="/index.php?r=auth/reset-password" class="space-y-5">
                    <input type="hidden" name="token" value="<?=htmlspecialchars($token)?>">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nouveau mot de passe</label>
                        <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required minlength="6" autofocus>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Confirmer</label>
                        <input type="password" name="password_confirm" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required minlength="6">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                        Enregistrer
                    </button>
                </form>
            <?php elseif (!empty($error)): ?>
                <p class="text-center"><a href="/index.php?r=auth/forgot-password" class="text-blue-600 font-semibold hover:underline">Demander un nouveau lien</a></p>
            <?php endif; ?>

            <p class="mt-6 text-center text-sm text-gray-600">
                <a href="/index.php?r=auth/login" class="text-blue-600 font-semibold hover:underline">Retour a la connexion</a>
            </p>
        </div>
    </div>
</div>
