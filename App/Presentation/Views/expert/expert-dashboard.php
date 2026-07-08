 <?php

$currentUser = $_SESSION['user'] ?? [];

$username = $currentUser['username'] ?? 'Expert';

$questions = $questions ?? [];


$totalAssigned = $totalAssigned ?? 0;
$pending = $pending ?? 0;
$answered = $answered ?? 0;
$totalConsultations = $totalConsultations ?? 0;

?>            
            <div>
                <h1 class="text-2xl font-extrabold text-[#0F172A] tracking-tight flex items-center gap-2">
                   Welcome back, <?= htmlspecialchars($username) ?>! <span class="animate-bounce origin-bottom inline-block">👋</span>
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">Here's what's happening with your cases today.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] flex items-center gap-4 hover:shadow-md transition-all duration-300">
                    <div class="h-12 w-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <span class="text-2xl font-black text-[#0F172A] tracking-tight"><?= ($totalAssigned) ?></span>
                        <p class="text-xs font-bold text-slate-400 mt-0.5">Total Assigned</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] flex items-center gap-4 hover:shadow-md transition-all duration-300">
                    <div class="h-12 w-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <span class="text-2xl font-black text-[#0F172A] tracking-tight"><?= ($pending) ?></span>
                        <p class="text-xs font-bold text-slate-400 mt-0.5">Pending</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] flex items-center gap-4 hover:shadow-md transition-all duration-300">
                    <div class="h-12 w-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <div>
                        <span class="text-2xl font-black text-[#0F172A] tracking-tight"><?= ($answered) ?></span>
                        <p class="text-xs font-bold text-slate-400 mt-0.5">Answered</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] flex items-center gap-4 hover:shadow-md transition-all duration-300">
                    <div class="h-12 w-12 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <span class="text-2xl font-black text-[#0F172A] tracking-tight"><?= ($totalConsultations) ?></span>
                        <p class="text-xs font-bold text-slate-400 mt-0.5">Total Consultations</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[1.5rem] shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-extrabold text-[#0F172A] tracking-tight">Total Assigned Questions</h3>
                </div>

                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                                <th class="px-6 py-4">Farmer Question</th>
                                <th class="px-6 py-4">Farmer</th>
                                <th class="px-6 py-4">Category</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">

        <?php if (!empty($questions)): ?>

            <?php foreach ($questions as $q): ?>

                <tr class="group hover:bg-slate-50 transition">

                    <td class="px-6 py-4 font-bold text-slate-900">
                        <?= htmlspecialchars($q['title']) ?>
                    </td>

                    <td class="px-6 py-4">
                        <?= htmlspecialchars($q['farmer_name'] ?? '-') ?>
                    </td>

                    <td class="px-6 py-4">

                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold">

                            <?= htmlspecialchars($q['category_name'] ?? '-') ?>

                        </span>

                    </td>

                    <td class="px-6 py-4 text-right">

                        <a
                            href="<?= BASE_URL ?>/expert/questions/answer?id=<?= $q['question_id'] ?>"
                            class="inline-flex items-center bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-xl text-xs font-bold">

                            Answer

                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

        <tr>

        <td colspan="4" class="text-center py-10 text-gray-500">

        No pending questions.

        </td>

        </tr>

        <?php endif; ?>

        </tbody>
                    </table>
                </div>

                <div class="block md:hidden divide-y divide-slate-100">

<?php if (!empty($questions)): ?>

<?php foreach ($questions as $q): ?>

<div class="p-5 space-y-4">

    <div class="flex items-start justify-between gap-3">

        <h4 class="text-sm font-bold text-slate-900">

            <?= htmlspecialchars($q['title']) ?>

        </h4>

        <span class="inline-flex items-center px-2 py-1 text-xs font-bold bg-emerald-50 text-emerald-700 rounded-md">

            <?= htmlspecialchars($q['category_name']) ?>

        </span>

    </div>

    <div class="text-sm text-slate-600">

        Farmer:
        <strong><?= htmlspecialchars($q['farmer_name']) ?></strong>

    </div>

    <a
        href="<?= BASE_URL ?>/expert/questions/answer?id=<?= $q['question_id'] ?>"
        class="w-full inline-flex justify-center bg-green-700 hover:bg-green-800 text-white py-2 rounded-xl text-sm font-bold">

        Answer Question

    </a>

</div>

<?php endforeach; ?>

<?php else: ?>

<div class="p-8 text-center text-gray-500">

No pending questions.

</div>

<?php endif; ?>

</div>

            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('expert-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if(sidebar.classList.contains('translate-x-[-100%]')) {
                sidebar.classList.remove('translate-x-[-100%]');
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('translate-x-[-100%]');
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 30);
            }
        }
    </script>
