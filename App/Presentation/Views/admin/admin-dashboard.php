<?php

$activities = $activities ?? [];

$farmerCount = $farmerCount ?? 0;
$expertCount = $expertCount ?? 0;
$totalConsultations = $totalConsultations ?? 0;
$totalArticles = $totalArticles ?? 0;

$userRole = $_SESSION['user_role'] ?? '';

if ($userRole === 'expert') {
    $totalConsultations = $expertConsultations ?? 0;
    $totalArticles = $expertArticles ?? 0;
    $farmerCount = $expertConsultingFarmers ?? 0;
}

$roleDotColors = [

    'ADMIN' => 'bg-red-500',

    'EXPERT' => 'bg-blue-500',

    'FARMER' => 'bg-green-500',

];

$username = $_SESSION['user']['username'] ?? 'User';

?>



<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div class="animate-fadeInDown">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Welcome back, <?= htmlspecialchars($username) ?>! <span class="wave-hand">👋</span></h2>
            <p class="text-xs text-slate-500 mt-1">Here is a quick overview of your system.</p>
        </div>
        <div class="px-4 py-2 rounded-xl bg-white border border-slate-200 shadow-sm text-xs font-bold text-slate-600 animate-fadeIn">
            <?= date('M d, Y') ?>
        </div>
    </div>

<style>
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-12px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes wave {
    0%, 100% { transform: rotate(0deg); }
    20% { transform: rotate(14deg); }
    40% { transform: rotate(-8deg); }
    60% { transform: rotate(14deg); }
    80% { transform: rotate(-4deg); }
}
.animate-fadeInDown { animation: fadeInDown 0.6s ease-out both; }
.animate-fadeIn { animation: fadeIn 0.8s ease-out 0.2s both; }
.wave-hand { display: inline-block; animation: wave 1.5s ease-in-out infinite; transform-origin: 70% 70%; }
</style>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
        <div class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Farmers</p>
                <p class="text-3xl font-black text-slate-900 mt-1.5"><?= $farmerCount ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-500 group-hover:bg-rose-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
<?php if ($userRole === 'expert'): ?>
        <div class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Uploaded Images</p>
                <p class="text-3xl font-black text-slate-900 mt-1.5"><?= $expertArticleImages ?? 0 ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-500 group-hover:bg-purple-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
            </div>
        </div>
<?php else: ?>
        <div class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Experts</p>
                <p class="text-3xl font-black text-slate-900 mt-1.5"><?= $expertCount ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-500 group-hover:bg-indigo-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
<?php endif; ?>
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
        <div class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Total Revenue</p>
                <p class="text-3xl font-black text-emerald-600 mt-1.5">$<?= number_format($adminTotalRevenue ?? 0, 2) ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-500 group-hover:bg-emerald-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <?php if ($userRole === 'expert'): ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                <h2 class="text-sm font-black text-slate-900">Recent Consultation Requests</h2>
                <a href="<?= BASE_URL ?>/consultation/my-consultations" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700">View All</a>
            </div>
            <div class="p-2">
                <?php if (!empty($expertConsultationList)): ?>
                    <?php foreach ($expertConsultationList as $c): ?>
                    <div class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-800 truncate"><?= htmlspecialchars($c->getTitle()) ?></p>
                            <p class="text-[10px] text-slate-400 mt-0.5"><?= $c->getCreatedAt()->format('M d, Y') ?></p>
                        </div>
                        <span class="shrink-0 ml-3 px-2.5 py-1 rounded-full text-[10px] font-bold
                            <?= match ($c->getStatus()->getValue()) {
                                'pending' => 'bg-amber-50 text-amber-600',
                                'assigned' => 'bg-blue-50 text-blue-600',
                                'awaiting_payment' => 'bg-violet-50 text-violet-600',
                                'expert_accepted' => 'bg-sky-50 text-sky-600',
                                'payment_submitted' => 'bg-amber-50 text-amber-600',
                                'accepted' => 'bg-emerald-50 text-emerald-600',
                                'chat_started' => 'bg-teal-50 text-teal-600',
                                'completed' => 'bg-green-50 text-green-600',
                                'closed' => 'bg-slate-50 text-slate-600',
                                'rejected' => 'bg-red-50 text-red-600',
                                'expired' => 'bg-red-50 text-red-600',
                                default => 'bg-slate-50 text-slate-500',
                            } ?>">
                            <?= ucfirst(str_replace('_', ' ', $c->getStatus()->getValue())) ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-xs text-slate-400 text-center py-8">No consultation requests yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                <h2 class="text-sm font-black text-slate-900">Recent Articles</h2>
                <a href="<?= BASE_URL ?>/expert/articles" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700">View All</a>
            </div>
            <div class="p-2">
                <?php if (!empty($expertArticleList)): ?>
                    <?php foreach ($expertArticleList as $a): ?>
                    <div class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-800 truncate"><?= htmlspecialchars($a->getTitle()) ?></p>
                            <p class="text-[10px] text-slate-400 mt-0.5"><?= $a->getCreatedAt()->format('M d, Y') ?></p>
                        </div>
                        <span class="shrink-0 ml-3 px-2.5 py-1 rounded-full text-[10px] font-bold
                            <?= match ($a->getStatus()->getValue()) {
                                'pending' => 'bg-amber-50 text-amber-600',
                                'accepted' => 'bg-emerald-50 text-emerald-600',
                                'rejected' => 'bg-red-50 text-red-600',
                                default => 'bg-slate-50 text-slate-500',
                            } ?>">
                            <?= ucfirst($a->getStatus()->getValue()) ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-xs text-slate-400 text-center py-8">No articles written yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php elseif ($userRole === 'farmer'): ?>
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                <h2 class="text-sm font-black text-slate-900">Recent Consultations</h2>
                <a href="<?= BASE_URL ?>/admin/consultations" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700">View All</a>
            </div>
            <div class="p-2">
                <?php if (!empty($adminConsultationList)): ?>
                    <?php foreach ($adminConsultationList as $c): ?>
                    <div class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-800 truncate"><?= htmlspecialchars($c->getTitle()) ?></p>
                            <p class="text-[10px] text-slate-400 mt-0.5"><?= $c->getCreatedAt()->format('M d, Y') ?></p>
                        </div>
                        <span class="shrink-0 ml-3 px-2.5 py-1 rounded-full text-[10px] font-bold
                            <?= match ($c->getStatus()->getValue()) {
                                'pending' => 'bg-amber-50 text-amber-600',
                                'assigned' => 'bg-blue-50 text-blue-600',
                                'awaiting_payment' => 'bg-violet-50 text-violet-600',
                                'expert_accepted' => 'bg-sky-50 text-sky-600',
                                'payment_submitted' => 'bg-amber-50 text-amber-600',
                                'accepted' => 'bg-emerald-50 text-emerald-600',
                                'chat_started' => 'bg-teal-50 text-teal-600',
                                'completed' => 'bg-green-50 text-green-600',
                                'closed' => 'bg-slate-50 text-slate-600',
                                'rejected' => 'bg-red-50 text-red-600',
                                'expired' => 'bg-red-50 text-red-600',
                                default => 'bg-slate-50 text-slate-500',
                            } ?>">
                            <?= ucfirst(str_replace('_', ' ', $c->getStatus()->getValue())) ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-xs text-slate-400 text-center py-8">No consultations yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
            <h2 class="text-sm font-black text-slate-900">Recent Activity</h2>
            <?php if (($adminUnreadNotifications ?? 0) > 0): ?>
            <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-bold"><?= $adminUnreadNotifications ?> unread</span>
            <?php endif; ?>
        </div>
        <div class="p-2">
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