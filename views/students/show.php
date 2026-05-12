<div class="space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center gap-4">
        <a href="/index.php?r=students" class="text-blue-600 hover:text-blue-800 transition">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Profil Étudiant</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Photo -->
                <div class="h-64 bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center relative">
                    <?php if (!empty($student['photo'])): ?>
                        <img src="/index.php?r=media/image&file=<?=htmlspecialchars($student['photo'])?>" alt="<?=htmlspecialchars($student['prenom'] . ' ' . $student['nom'])?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="text-center">
                            <i class="fas fa-user text-6xl text-blue-300"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Student Info -->
                <div class="p-6 space-y-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800"><?=htmlspecialchars($student['prenom'] . ' ' . $student['nom'])?></h3>
                        <p class="text-gray-600 text-sm">ID: #<?=htmlspecialchars($student['id'])?></p>
                    </div>

                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="text-gray-800 font-medium break-all"><?=htmlspecialchars($student['email'])?></p>
                    </div>

                    <?php if ($class): ?>
                        <div class="border-t pt-4">
                            <p class="text-sm text-gray-600">Classe</p>
                            <p class="text-gray-800 font-medium"><?=htmlspecialchars($class['nom'])?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="border-t pt-4 flex gap-2">
                        <a href="/index.php?r=students/edit&id=<?=$student['id']?>" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-center text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i> Modifier
                        </a>
                        <a href="/index.php?r=students/delete&id=<?=$student['id']?>" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-center text-sm font-medium" onclick="return confirm('Confirmer la suppression ?')">
                            <i class="fas fa-trash mr-1"></i> Supprimer
                        </a>
                    </div>
                </div>
            </div>

            <!-- Attendance Overview -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4">Présences</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Présent(es)</span>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold"><?=$attendanceStats['present_count']?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Absent(es)</span>
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full font-bold"><?=$attendanceStats['absent_count']?></span>
                    </div>
                    <div class="flex justify-between items-center border-t pt-3">
                        <span class="text-gray-600 font-medium">Total</span>
                        <span class="font-bold text-gray-800"><?=$attendanceStats['total_count']?></span>
                    </div>
                    <?php if ($attendanceStats['total_count'] > 0): ?>
                        <div class="flex justify-between items-center border-t pt-3">
                            <span class="text-gray-600">Taux</span>
                            <span class="font-bold text-blue-600">
                                <?=round(($attendanceStats['present_count'] / $attendanceStats['total_count']) * 100, 1)?>%
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Grades and Attendance -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Grades Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                    <h4 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-chart-bar"></i> Notes
                    </h4>
                </div>

                <?php if (!empty($notes)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="text-left py-3 px-6 text-gray-700 font-semibold">Matière</th>
                                    <th class="text-center py-3 px-6 text-gray-700 font-semibold">Note</th>
                                    <th class="text-center py-3 px-6 text-gray-700 font-semibold">Appréciation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notes as $note): ?>
                                    <tr class="border-b border-gray-100 hover:bg-purple-50 transition">
                                        <td class="py-4 px-6 text-gray-800"><?=htmlspecialchars($note['matiere_nom'])?></td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-bold text-lg">
                                                <?=htmlspecialchars($note['note'])?>/20
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <?php
                                            $noteValue = (float)$note['note'];
                                            if ($noteValue >= 16) $appreciation = 'Excellent';
                                            elseif ($noteValue >= 14) $appreciation = 'Très bien';
                                            elseif ($noteValue >= 12) $appreciation = 'Bien';
                                            elseif ($noteValue >= 10) $appreciation = 'Satisfaisant';
                                            elseif ($noteValue >= 8) $appreciation = 'Passable';
                                            else $appreciation = 'Insuffisant';
                                            ?>
                                            <span class="text-gray-700 font-medium"><?=$appreciation?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-6 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                        <p>Aucune note enregistrée</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recent Attendance Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                    <h4 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-calendar-check"></i> Absences Récentes
                    </h4>
                </div>

                <?php if (!empty($recentAttendance)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="text-left py-3 px-6 text-gray-700 font-semibold">Date</th>
                                    <th class="text-center py-3 px-6 text-gray-700 font-semibold">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentAttendance as $att): ?>
                                    <tr class="border-b border-gray-100 hover:bg-green-50 transition">
                                        <td class="py-4 px-6 text-gray-800">
                                            <?=date('d/m/Y', strtotime($att['date']))?>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <?php if ($att['present']): ?>
                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                                    <i class="fas fa-check mr-1"></i> Présent
                                                </span>
                                            <?php else: ?>
                                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                                    <i class="fas fa-times mr-1"></i> Absent
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-6 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                        <p>Aucun enregistrement de présence</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
