<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Statistiques de la classe: <?=htmlspecialchars($class['nom'] ?? 'N/A')?></h2>
            <p class="text-gray-600 mt-1">Performance des étudiants</p>
        </div>
        <a href="/index.php?r=students" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Étudiant</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Moyenne Générale</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Classement</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    foreach ($classStats as $stat): 
                        $moyenne = $stat['moyenne'] ?? 0;
                        if ($moyenne >= 16) $color = 'text-green-600 bg-green-50';
                        elseif ($moyenne >= 12) $color = 'text-blue-600 bg-blue-50';
                        elseif ($moyenne >= 10) $color = 'text-yellow-600 bg-yellow-50';
                        else $color = 'text-red-600 bg-red-50';
                    ?>
                        <tr class="table-row border-b border-gray-100 hover:bg-purple-50 transition">
                            <td class="py-4 px-6 text-gray-800 font-medium"><?=htmlspecialchars($stat['nom'] . ' ' . $stat['prenom'])?></td>
                            <td class="py-4 px-6"><span class="<?=$color?> px-3 py-1 rounded-full font-bold"><?=round($moyenne, 2)?>/20</span></td>
                            <td class="py-4 px-6 text-gray-600"><span class="font-bold">#<?=$rank?></span></td>
                        </tr>
                    <?php $rank++; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
