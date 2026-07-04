<?php
$activePage = 'dashboard';

$currentUser = $_SESSION['user'] ?? null;
$username = $currentUser['username'] ?? 'Farmer';
$questions = $questions ?? [];
$totalQuestions = $totalQuestions ?? count($questions);

$pendingQuestions = 0;

$answeredQuestions = 0;

$imageCount = 0;

foreach ($questions as $q) {

    if (($q['status_name'] ?? '') === 'Pending') {
        $pendingQuestions++;
    }

    if (($q['status_name'] ?? '') === 'Answered') {
        $answeredQuestions++;
    }

    if (!empty($q['image'])) {
        $imageCount++;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Grape Cultivation Advisory Chat System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-gradient-to-b from-[#eaf3e9] to-[#e3f0e2] text-slate-800 antialiased min-h-screen">

    <div class="flex min-h-screen relative items-start">

        <div class="flex-grow flex flex-col min-h-screen w-full overflow-hidden">
            

            <main class="flex-grow p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 w-full max-w-full">
                
                <div class="animate-fade-in">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        Welcome back, <?= htmlspecialchars($username) ?>! 👋
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">Get expert advice and grow healthy grapes.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5 animate-fade-in" style="animation-delay: 0.05s;">
                    <div class="bg-white border border-green-100/50 p-4 sm:p-5 rounded-2xl shadow-sm border-l-[5px] border-l-[#2e7d32] flex items-center gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-green-50 text-[#2e7d32] flex items-center justify-center font-bold text-xl shrink-0"><span>💬</span></div>
                        <div>
                            <span class="block text-xl sm:text-2xl font-black text-slate-900 tracking-tight"><?= $totalQuestions ?></span>
                            <span class="block text-xs font-bold text-slate-700">Total Questions</span>
                        </div>
                    </div>

                    <div class="bg-white border border-green-100/50 p-4 sm:p-5 rounded-2xl shadow-sm border-l-[5px] border-l-amber-500 flex items-center gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl shrink-0"><span>⏳</span></div>
                        <div>
                            <span class="block text-xl sm:text-2xl font-black text-slate-900 tracking-tight"><?= $pendingQuestions ?></span>
                            <span class="block text-xs font-bold text-slate-700">Pending Questions</span>
                        </div>
                    </div>

                    <div class="bg-white border border-green-100/50 p-4 sm:p-5 rounded-2xl shadow-sm border-l-[5px] border-l-blue-600 flex items-center gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl shrink-0"><span>✓</span></div>
                        <div>
                            <span class="block text-xl sm:text-2xl font-black text-slate-900 tracking-tight"><?= $answeredQuestions ?></span>
                            <span class="block text-xs font-bold text-slate-700">Answered Questions</span>
                        </div>
                    </div>

                    <div class="bg-white border border-green-100/50 p-4 sm:p-5 rounded-2xl shadow-sm border-l-[5px] border-l-purple-600 flex items-center gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl shrink-0"><span>🖼</span></div>
                        <div>
                            <span class="block text-xl sm:text-2xl font-black text-slate-900 tracking-tight"><?= $imageCount ?></span>
                            <span class="block text-xs font-bold text-slate-700">Images Uploaded</span>
                        </div>
                    </div>
                </div>

    <div class="max-w-5xl mx-auto bg-white border border-slate-100 rounded-2xl p-4 sm:p-6 shadow-sm shadow-slate-100/50 space-y-4 animate-fade-in">
        
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">

        <h2 class="text-lg font-extrabold text-slate-900">
            My Questions
        </h2>

        <a href="<?= BASE_URL ?>/farmer-dashboard/ask-question"
        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-semibold">
            + Ask Question
        </a>

    </div>

        <div class="overflow-x-auto -mx-2 sm:mx-0">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-50">
                        <th class="pb-3 pl-2">Question</th>
                        <th class="pb-3">Expert</th>
                        <th class="pb-3">Category</th>
                        <th class="pb-3 text-center">Status</th>
                        <th class="pb-3 text-right pr-2">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">

<?php if (!empty($questions)): ?>

<?php foreach ($questions as $q): ?>

<?php

$status = $q['status_name'] ?? 'Pending';

$statusClass = 'bg-yellow-100 text-yellow-800';

if ($status == 'Answered') {
    $statusClass = 'bg-green-100 text-green-800';
}

?>

<tr class="hover:bg-slate-50">

    <td class="py-4 pl-2">

        <?= htmlspecialchars($q['title']) ?>

    </td>

    <td>

        <?= htmlspecialchars($q['expert_name'] ?? '-') ?>

    </td>

    <td>

        <?= htmlspecialchars($q['category_name'] ?? '-') ?>

    </td>

    <td class="text-center">

        <span class="px-3 py-1 rounded-full <?= $statusClass ?>">

            <?= htmlspecialchars($status) ?>

        </span>

    </td>

    <td class="text-right pr-2">

        <?= date('d M Y', strtotime($q['created_at'])) ?>

    </td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="5" class="py-8 text-center text-gray-400">

No questions found.

</td>

</tr>

<?php endif; ?>

</tbody>
            </table>
        </div>
    </div>
</div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggleBtn = document.getElementById("mobileSidebarToggle");
            const closeBtn = document.getElementById("closeSidebarBtn");
            const drawer = document.getElementById("sidebarDrawer");
            const backdrop = document.getElementById("sidebarBackdrop");

            function openSidebar() {
                backdrop.classList.remove("hidden");
                setTimeout(() => {
                    backdrop.classList.add("opacity-100");
                    drawer.classList.remove("-translate-x-full");
                }, 20);
            }

            function closeSidebar() {
                drawer.classList.add("-translate-x-full");
                backdrop.classList.remove("opacity-100");
                backdrop.classList.add("opacity-0");
                setTimeout(() => backdrop.classList.add("hidden"), 300);
            }

            if (toggleBtn) toggleBtn.addEventListener("click", openSidebar);
            if (closeBtn) closeBtn.addEventListener("click", closeSidebar);
            if (backdrop) backdrop.addEventListener("click", closeSidebar);
        });
    </script>
</body>
</html>