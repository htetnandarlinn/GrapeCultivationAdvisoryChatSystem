<?php

$activities = $activities ?? [];

$farmerCount = $farmerCount ?? 0;

$expertCount = $expertCount ?? 0;

$totalQuestions = $totalQuestions ?? 0;

$pendingQuestions = $pendingQuestions ?? 0;

$answeredQuestions = $answeredQuestions ?? 0;

$imageCount = $imageCount ?? 0;

$questions = $questions ?? [];



$roleDotColors = [

    'ADMIN' => 'bg-red-500',

    'EXPERT' => 'bg-blue-500',

    'FARMER' => 'bg-green-500',

];

$userRole = $_SESSION['user_role'] ?? '';

$username = $_SESSION['user']['username'] ?? 'User';

?>



<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Welcome back, <?= htmlspecialchars($username) ?>! 👋</h2>
            <p class="text-xs text-slate-500 mt-1">Here is a quick overview of your system.</p>
        </div>
        <div class="px-4 py-2 rounded-xl bg-white border border-slate-200 shadow-sm text-xs font-bold text-slate-600">
            <?= date('M d, Y') ?>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <?php 
        $stats = [
            ['label' => 'Farmers', 'value' => $farmerCount, 'color' => 'text-rose-500', 'bg' => 'bg-rose-50', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
            ['label' => 'Total Questions', 'value' => $totalQuestions, 'color' => 'text-indigo-500', 'bg' => 'bg-indigo-50', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
            ['label' => 'Pending', 'value' => $pendingQuestions, 'color' => 'text-amber-500', 'bg' => 'bg-amber-50', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Answered', 'value' => $answeredQuestions, 'color' => 'text-emerald-500', 'bg' => 'bg-emerald-50', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z']
        ];
        foreach ($stats as $stat): ?>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400"><?= $stat['label'] ?></p>
                <p class="text-2xl font-black text-slate-900 mt-0.5"><?= $stat['value'] ?></p>
            </div>
            <div class="w-10 h-10 rounded-xl <?= $stat['bg'] . ' ' . $stat['color'] ?> flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $stat['icon'] ?>"></path></svg>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($userRole === 'admin'): ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
            <h2 class="text-sm font-black text-slate-900">Recent Activity</h2>
        </div>
        <div class="max-h-[300px] overflow-y-auto p-2 scrollbar-thin">
            <div class="space-y-0.5">
                <?php foreach ($activities as $act): ?>
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <span class="w-1.5 h-1.5 rounded-full <?= $roleDotColors[strtoupper($act['user_role'] ?? '')] ?? 'bg-slate-300' ?>"></span>
                        <p class="text-xs font-medium text-slate-600 flex-grow"><?= htmlspecialchars($act['activity']) ?></p>
                        <span class="text-[10px] text-slate-400 font-mono"><?= date('h:i A', strtotime($act['created_at'])) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>