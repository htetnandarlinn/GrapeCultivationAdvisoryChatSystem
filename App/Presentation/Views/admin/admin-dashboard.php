<?php

$activities = $activities ?? [];

$farmerCount = $farmerCount ?? 0;
$expertCount = $expertCount ?? 0;
$totalConsultations = $totalConsultations ?? 0;
$totalArticles = $totalArticles ?? 0;

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

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Farmers</p>
                <p class="text-3xl font-black text-slate-900 mt-1.5"><?= $farmerCount ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-500 group-hover:bg-rose-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
        <div class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Experts</p>
                <p class="text-3xl font-black text-slate-900 mt-1.5"><?= $expertCount ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-500 group-hover:bg-indigo-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
        <div class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Consultations</p>
                <p class="text-3xl font-black text-slate-900 mt-1.5"><?= $totalConsultations ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-500 group-hover:bg-amber-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
        </div>
        <div class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Articles</p>
                <p class="text-3xl font-black text-slate-900 mt-1.5"><?= $totalArticles ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-500 group-hover:bg-sky-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
        </div>
    </div>

    <?php if ($userRole === 'farmer'): ?>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Consultations</p>
            <p class="text-2xl font-black text-slate-900 mt-0.5"><?= $totalConsultations ?? 0 ?></p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pending</p>
            <p class="text-2xl font-black text-amber-600 mt-0.5"><?= $pendingConsultations ?? 0 ?></p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Accepted</p>
            <p class="text-2xl font-black text-emerald-600 mt-0.5"><?= $acceptedConsultations ?? 0 ?></p>
        </div>
    </div>
    <?php endif; ?>

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
    <?php elseif ($userRole === 'farmer'): ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-50 flex items-center justify-between">
            <h2 class="text-sm font-black text-slate-900">Quick Consultation</h2>
            <a href="<?= BASE_URL ?>/consultation/my-consultations" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700">View All</a>
        </div>
        <div class="p-5">
            <p class="text-xs text-slate-500 mb-4">Describe your grape cultivation issue and an expert will help you.</p>
            <form action="<?= BASE_URL ?>/consultation/store" method="POST" class="space-y-4">
                <div>
                    <input type="text" name="title" required placeholder="e.g., Grape leaf discoloration problem"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                </div>
                <div>
                    <textarea name="description" rows="4" required placeholder="Describe your issue in detail..."
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all resize-y"></textarea>
                </div>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-colors">
                    Submit Consultation
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</section>