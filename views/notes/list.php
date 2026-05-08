<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Notes de <?=htmlspecialchars($student['nom'].' '.$student['prenom'])?></h2>
            <p class="text-gray-600 mt-1">Moyenne: <span class="text-2xl font-bold text-purple-600"><?= $avg ?? 'N/A' ?>/20</span></p>
        </div>
        <a href="/index.php?r=notes/create" class="btn-primary bg-blue-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Ajouter une note
        </a>
    </div>

    <!-- Table des notes -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Matière</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Note</th>
                        <th class="text-left py-4 px-6 text-gray-700 font-semibold">Appréciation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notes as $n): 
                        $note_val = floatval($n['note']);
                        if ($note_val >= 16) $color = 'text-green-600 bg-green-50';
                        elseif ($note_val >= 12) $color = 'text-blue-600 bg-blue-50';
                        elseif ($note_val >= 10) $color = 'text-yellow-600 bg-yellow-50';
                        else $color = 'text-red-600 bg-red-50';
                        
                        if ($note_val >= 16) $appreciation = 'Excellent';
                        elseif ($note_val >= 12) $appreciation = 'Bon';
                        elseif ($note_val >= 10) $appreciation = 'Moyen';
                        else $appreciation = 'À améliorer';
                    ?>
                        <tr class="table-row border-b border-gray-100 hover:bg-purple-50 transition">
                            <td class="py-4 px-6 text-gray-800 font-medium"><?=htmlspecialchars($n['matiere_nom'] ?? 'N/A')?></td>
                            <td class="py-4 px-6"><span class="<?=$color?> px-3 py-1 rounded-full font-bold"><?=htmlspecialchars($n['note'])?>/20</span></td>
                            <td class="py-4 px-6 text-gray-600"><?=$appreciation?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
