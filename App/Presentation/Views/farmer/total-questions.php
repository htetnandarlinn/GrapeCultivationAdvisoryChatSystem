<?php
session_start();

// Include your database connection
$pdo = (function () {
    $pdo = null;
    $result = require __DIR__ . '/../../../config/db.php';
    if ($result instanceof PDO) {
        return $result;
    }
    return $pdo;
})();

if (!($pdo instanceof PDO)) {
    die('Database connection error.');
}

// Ensure farmer is logged in; fallback to 1 for testing
$farmer_id = $_SESSION['farmer_id'] ?? 1;

// Fetch data from database
try {
    $stmt = $pdo->prepare("SELECT * FROM consultations WHERE farmer_id = ? ORDER BY created_at DESC");
    $stmt->execute([$farmer_id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Questions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fade-in { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.6s ease-out forwards; }
    </style>
</head>
<body class="bg-slate-50 p-4 sm:p-8">

    <div class="max-w-5xl mx-auto bg-white border border-slate-100 rounded-2xl p-4 sm:p-6 shadow-sm shadow-slate-100/50 space-y-4 animate-fade-in">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">My Questions</h2>
            <a href="?page=all-questions" class="text-xs font-bold text-green-700 hover:text-green-800 transition-colors">View All &rarr;</a>
        </div>

        <div class="overflow-x-auto -mx-2 sm:mx-0">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-50">
                        <th class="pb-3 pl-2">Question</th>
                        <th class="pb-3">Expert</th>
                        <th class="pb-3 text-center">Status</th>
                        <th class="pb-3 text-right pr-2">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    <?php if (count($questions) > 0): ?>
                        <?php foreach ($questions as $q): 
                            // Set status color logic
                            $status = 'Under Review'; 
                            $statusClass = 'bg-amber-50 text-amber-800 ring-amber-600/10';
                        ?>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="py-4 pl-2 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-green-50 text-emerald-700 flex items-center justify-center shrink-0">🌿</div>
                                    <span class="font-semibold text-slate-800 truncate group-hover:text-green-700 transition-colors">
                                        <?= htmlspecialchars($q['title']) ?>
                                    </span>
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-green-100 text-emerald-800 rounded-full flex items-center justify-center text-[10px] font-bold">KM</div>
                                    <div>
                                        <span class="block font-bold text-slate-800 text-[11px]">Dr. Khin Maung</span>
                                        <span class="block text-[9px] text-slate-400">Pathologist</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 text-center">
                                <span class="inline-block px-3 py-1 text-[10px] font-bold rounded-full ring-1 <?= $statusClass ?>">
                                    <?= $status ?>
                                </span>
                            </td>
                            <td class="py-4 text-right pr-2 text-slate-400 text-[11px] font-medium">
                                <?= date('d M Y', strtotime($q['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="py-8 text-center text-slate-400">No questions found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>