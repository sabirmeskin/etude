<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="text-5xl mb-4">🎓</div>
                <h1 class="text-3xl font-bold text-gray-800">Creer un compte</h1>
                <p class="text-gray-600 mt-2">Accedez a Mini ERP avec votre propre compte</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?=htmlspecialchars($error)?>
                </div>
            <?php endif; ?>

            <form method="post" action="/index.php?r=auth/register" class="space-y-5">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-id-badge mr-2"></i> Type de compte
                    </label>
                    <select name="role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="professeur" <?= (($role ?? 'professeur') === 'professeur') ? 'selected' : '' ?>>Professeur</option>
                        <option value="etudiant" <?= (($role ?? '') === 'etudiant') ? 'selected' : '' ?>>Eleve</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Eleve : utilisez exactement l email enregistre sur votre fiche eleve par l administration.</p>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-user mr-2"></i> Nom complet
                    </label>
                    <input type="text" name="nom" value="<?=htmlspecialchars($nom ?? '')?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Votre nom" required autofocus>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-envelope mr-2"></i> Email
                    </label>
                    <input type="email" name="email" value="<?=htmlspecialchars($email ?? '')?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="vous@example.com" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2"></i> Mot de passe
                    </label>
                    <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Au moins 6 caracteres" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2"></i> Confirmer le mot de passe
                    </label>
                    <input type="password" name="password_confirm" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Confirmez votre mot de passe" required>
                </div>

                <button type="submit" class="btn-primary w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition mt-6 hover:bg-blue-700">
                    <i class="fas fa-user-plus mr-2"></i> Creer le compte
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Vous avez deja un compte ?
                <a href="/index.php?r=auth/login" class="text-blue-600 font-semibold hover:underline">Se connecter</a>
            </p>
            <p class="mt-2 text-center text-sm">
                <a href="/index.php?r=auth/forgot-password" class="text-gray-600 hover:text-blue-600 hover:underline">Mot de passe oublie ?</a>
            </p>
        </div>
    </div>
</div>
