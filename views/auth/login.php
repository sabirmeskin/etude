<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600">
    <div class="max-w-md w-full mx-4">
        <!-- Card de connexion -->
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="text-5xl mb-4">🎓</div>
                <h1 class="text-3xl font-bold text-gray-800">Mini ERP</h1>
                <p class="text-gray-600 mt-2">Gestion Scolaire</p>
            </div>

            <?php if (!empty($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?=htmlspecialchars($success)?>
                </div>
            <?php endif; ?>

            <!-- Messages d'erreur -->
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?=htmlspecialchars($error)?>
                </div>
            <?php endif; ?>

            <!-- Formulaire -->
            <form method="post" action="/index.php?r=auth/login" class="space-y-5">
                <!-- Email -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-envelope mr-2"></i> Email
                    </label>
                    <input type="email" name="email" value="<?=htmlspecialchars($email ?? '')?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="admin@local" required autofocus>
                </div>

                <!-- Mot de passe -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2"></i> Mot de passe
                    </label>
                    <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Votre mot de passe" required>
                </div>

                <!-- Bouton connexion -->
                <button type="submit" class="btn-primary w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition mt-6 hover:bg-blue-700">
                    <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                </button>
            </form>

            <p class="mt-4 text-center text-sm">
                <a href="/index.php?r=auth/forgot-password" class="text-blue-600 font-semibold hover:underline">Mot de passe oublie ?</a>
            </p>

            <p class="mt-4 text-center text-sm text-gray-600">
                Pas encore de compte ?
                <a href="/index.php?r=auth/register" class="text-blue-600 font-semibold hover:underline">Creer un compte</a>
            </p>

            <!-- Info -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg text-sm text-gray-700">
                <p class="font-semibold mb-2"><i class="fas fa-info-circle mr-1"></i> Comptes de test (apres <code class="bg-gray-200 px-1 rounded">php db/seed.php</code>) :</p>
                <p><strong>Admin</strong> — Email: <code class="bg-gray-200 px-2 py-1 rounded">admin@local</code> / Mot de passe: <code class="bg-gray-200 px-2 py-1 rounded">admin123</code></p>
                <p class="mt-2"><strong>Eleve</strong> — Inscription avec role Eleve et email <code class="bg-gray-200 px-2 py-1 rounded">eleve.demo@scolaire.local</code> (fiche eleve du seed), puis connexion.</p>
            </div>
        </div>
    </div>
</div>
