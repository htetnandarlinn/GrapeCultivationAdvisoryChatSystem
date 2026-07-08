<?php
$activePage = 'dashboard';

$currentUser = $_SESSION['user'] ?? null;
$username = $currentUser['username'] ?? 'Farmer';
$userAvatar = $currentUser['avatar'] ?? null;
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

</head>
<body class="bg-gradient-to-b from-[#eaf3e9] to-[#e3f0e2] text-slate-800 antialiased min-h-screen">

    <div class="flex min-h-screen relative items-start">

        <div class="flex-grow flex flex-col min-h-screen w-full overflow-hidden">
            

            <main class="flex-grow p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 w-full max-w-full">
                
                <div class="animate-fade-in flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                            Welcome back, <?= htmlspecialchars($username) ?>! 👋
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">Get expert advice and grow healthy grapes.</p>
                    </div>
                    <div class="flex items-center gap-3 bg-white/80 border border-slate-200 rounded-2xl px-3 py-2 shadow-sm">
    
            
                    </div>
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
                        <th class="pb-3">Category</th>
                        <th class="pb-3">Expert</th>
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
        <div class="flex items-center gap-3">
            <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-100 flex items-center justify-center border border-slate-200">
                <?php if (!empty($q['image'])): ?>
                    <img src="<?= BASE_URL ?>/uploads/questions/<?= htmlspecialchars($q['image']) ?>" alt="Question image" class="w-full h-full object-cover">
                <?php else: ?>
                    <span class="text-[10px] text-slate-500">No image</span>
                <?php endif; ?>
            </div>
            <div class="min-w-0">
                <div class="text-sm font-semibold text-slate-900 truncate"><?= htmlspecialchars($q['title']) ?></div>
                <div class="mt-1 text-xs text-slate-500 truncate">Category: <?= htmlspecialchars($q['category_name'] ?? 'Uncategorized') ?></div>
            </div>
        </div>
    </td>

    <td class="py-4 px-2 text-sm text-slate-700">
        <?= htmlspecialchars($q['category_name'] ?? '-') ?>
    </td>

    <td class="py-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full overflow-hidden bg-slate-100 flex items-center justify-center border border-slate-200">
                <?php if (!empty($q['expert_avatar'])): ?>
                    <img src="<?= BASE_URL ?>/uploads/profiles/<?= htmlspecialchars($q['expert_avatar']) ?>" alt="Expert avatar" class="w-full h-full object-cover">
                <?php else: ?>
                    <span class="text-[10px] text-slate-500">E</span>
                <?php endif; ?>
            </div>
            <div class="min-w-0">
                <div class="text-sm font-semibold text-slate-900 truncate"><?= htmlspecialchars($q['expert_name'] ?? 'Awaiting Expert') ?></div>
                <div class="text-[10px] uppercase tracking-[0.18em] text-slate-400">Expert</div>
            </div>
        </div>
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
