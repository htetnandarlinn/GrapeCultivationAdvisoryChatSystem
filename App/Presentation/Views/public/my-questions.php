<?php
$questions = $questions ?? [];
?>
<div class="min-h-screen bg-gradient-to-b from-[#f8faf8] to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-8 py-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">My Questions</h1>
                <p class="text-sm text-slate-500 mt-1">Track your submitted questions and expert responses.</p>
            </div>
            <a href="<?= BASE_URL ?>/consultation/ask" class="inline-flex items-center gap-2 bg-[#15803D] text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-[#116631] transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Question
            </a>
        </div>

        <?php if (empty($questions)): ?>
            <div class="text-center py-20 bg-white rounded-3xl border border-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <p class="text-sm font-semibold text-slate-400">No questions yet.</p>
                <a href="<?= BASE_URL ?>/consultation/ask" class="inline-block mt-3 text-xs font-bold text-[#15803D] hover:underline">Ask your first question</a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($questions as $q): ?>
                    <?php
                    $statusName = $q['status_name'] ?? 'Under Review';
                    $statusColors = [
                        'Pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'Answered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    ];
                    $statusColor = $statusColors[$statusName] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                    ?>
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-bold text-slate-900"><?= htmlspecialchars($q['title']) ?></h3>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2"><?= htmlspecialchars($q['description']) ?></p>
                                <div class="flex items-center gap-3 mt-2 text-[11px] text-slate-400">
                                    <span><?= htmlspecialchars($q['category_name'] ?? 'General') ?></span>
                                    <span>&middot;</span>
                                    <span><?= date('M d, Y', strtotime($q['created_at'])) ?></span>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border flex-shrink-0 <?= $statusColor ?>">
                                <?= htmlspecialchars($statusName) ?>
                            </span>
                        </div>
                        <?php if (!empty($q['answer'])): ?>
                            <div class="mt-4 pt-4 border-t border-slate-50">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#15803D]/10 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[10px] font-bold text-[#15803D]"><?= strtoupper(substr($q['expert_name'] ?? 'E', 0, 1)) ?></span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-700"><?= htmlspecialchars($q['expert_name'] ?? 'Expert') ?></p>
                                        <p class="text-xs text-slate-600 mt-1"><?= nl2br(htmlspecialchars($q['answer'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
