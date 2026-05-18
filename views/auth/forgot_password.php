<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="text-5xl mb-4">🔑</div>
                <h1 class="text-2xl font-bold text-gray-800">Mot de passe oublie</h1>
                <p class="text-gray-600 mt-2 text-sm">Indiquez l email de votre compte</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6"><?=htmlspecialchars($error)?></div>
            <?php endif; ?>

            <?php if (!empty($sent)): ?>
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-6">
                    <p class="font-semibold">Demande enregistree</p>
                    <p class="text-sm mt-2">Si un compte existe pour cet email, vous pouvez reinitialiser votre mot de passe.</p>
                </div>
                <?php if (!empty($resetUrl)): ?>
                    <div class="bg-amber-50 border border-amber-300 text-amber-900 px-4 py-3 rounded mb-6 text-sm">
                        <p class="font-semibold mb-2">Mode demonstration (sans email)</p>
                        <p class="mb-2">Cliquez sur le lien ci-dessous ou copiez-le dans votre navigateur. En production, ce lien serait envoye uniquement par email.</p>
                        <a href="<?=htmlspecialchars($resetUrl)?>" class="text-blue-700 font-semibold break-all underline"><?=htmlspecialchars($resetUrl)?></a>
                    </div>
                <?php elseif (!empty($accountFound) && !PASSWORD_RESET_SHOW_LINK): ?>
                    <p class="text-sm text-gray-600">Un email de confirmation vous a ete envoye (configurez SMTP pour activer l envoi).</p>
                <?php endif; ?>
                <a href="/index.php?r=auth/login" class="block text-center mt-6 text-blue-600 font-semibold hover:underline">Retour a la connexion</a>
            <?php else: ?>
                <form method="post" action="/index.php?r=auth/forgot-password" class="space-y-5">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" name="email" value="<?=htmlspecialchars($email ?? '')?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required autofocus>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                        Envoyer le lien de reinitialisation
                    </button>
                </form>
                <p class="mt-6 text-center text-sm text-gray-600">
                    <a href="/index.php?r=auth/login" class="text-blue-600 font-semibold hover:underline">Retour a la connexion</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
