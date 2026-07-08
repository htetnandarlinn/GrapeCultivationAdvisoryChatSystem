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

<section class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Welcome back, <?= htmlspecialchars($username) ?>! 👋</h2>
            <p class="text-sm text-slate-500 mt-1">Here's what's happening in the system today.</p>
        </div>
        <div class="px-4 py-2 rounded-full bg-white border border-slate-200 text-sm font-medium text-slate-600">
            <?= date('M d, Y') ?>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <?php if ($userRole === 'admin'): ?>
        <div class="bg-white p-6 rounded-[28px] border border-slate-200 shadow-sm stagger-item delay-1">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Farmers</p>
            <p class="text-3xl font-black text-slate-900 mt-2"><?= $farmerCount ?></p>
            <div class="w-8 h-1 bg-emerald-100 mt-4 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-[28px] border border-slate-200 shadow-sm stagger-item delay-2">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Experts</p>
            <p class="text-3xl font-black text-slate-900 mt-2"><?= $expertCount ?></p>
            <div class="w-8 h-1 bg-emerald-100 mt-4 rounded-full"></div>
        </div>
        <?php endif; ?>
        <div class="bg-white p-6 rounded-[28px] border border-slate-200 shadow-sm stagger-item delay-3">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total Questions</p>
            <p class="text-3xl font-black text-slate-900 mt-2"><?= $totalQuestions ?></p>
            <div class="w-8 h-1 bg-indigo-100 mt-4 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-[28px] border border-slate-200 shadow-sm stagger-item delay-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Pending</p>
            <p class="text-3xl font-black text-slate-900 mt-2"><?= $pendingQuestions ?></p>
            <div class="w-8 h-1 bg-amber-100 mt-4 rounded-full"></div>
        </div>
        <div class="bg-white p-6 rounded-[28px] border border-slate-200 shadow-sm stagger-item delay-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Answered</p>
            <p class="text-3xl font-black text-slate-900 mt-2"><?= $answeredQuestions ?></p>
            <div class="w-8 h-1 bg-blue-100 mt-4 rounded-full"></div>
        </div>
    </div>

    <?php if ($userRole === 'admin'): ?>
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2 bg-white rounded-[32px] p-8 border border-slate-200">
            <h2 class="text-xl font-bold mb-6">Recent Activity</h2>
            <div class="space-y-4">
                <?php if (empty($activities)): ?>
                    <p class="text-sm text-slate-400 text-center py-8">No recent activity.</p>
                <?php else: ?>
                    <?php foreach ($activities as $act):
                        $role = $act['user_role'] ?? '';
                        $dotClass = $roleDotColors[strtoupper($role)] ?? 'bg-gray-400';
                    ?>
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-[#15803D]/20 transition">
                            <span class="w-3 h-3 rounded-full <?= $dotClass ?>"></span>
                            <p class="text-sm font-medium text-slate-700 flex-grow"><?= htmlspecialchars($act['activity'] ?? '') ?></p>
                            <span class="text-xs text-slate-400 font-mono"><?= date('h:i A', strtotime($act['created_at'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <aside class="bg-slate-900 rounded-[32px] p-8 text-white">
            <h2 class="text-xl font-bold">Quick Access</h2>
            <p class="text-slate-400 text-sm mt-2 mb-6">Manage system wide configurations.</p>
            <a href="<?= BASE_URL ?>/admin/users" class="w-full block text-center bg-[#15803D] hover:bg-[#166534] py-4 rounded-2xl font-bold transition-all active:scale-95">Manage Users</a>
        </aside>
    </div>
    <?php endif; ?>

    <?php if ($userRole === 'farmer'): ?>
    <div class="bg-white rounded-[32px] p-8 border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <h2 class="text-lg font-extrabold text-slate-900">My Questions</h2>
            <a href="<?= BASE_URL ?>/farmer-dashboard/ask-question" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-semibold text-sm">+ Ask Question</a>
        </div>
        <div class="overflow-x-auto">
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
                        <?php foreach ($questions as $q):
                            $status = $q['status_name'] ?? 'Pending';
                            $statusClass = $status === 'Answered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                        ?>
                        <tr class="hover:bg-slate-50">
                            <td class="py-4 pl-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-100 flex items-center justify-center border border-slate-200">
                                        <?php if (!empty($q['image'])): ?>
                                            <img src="<?= BASE_URL ?>/uploads/questions/<?= htmlspecialchars($q['image']) ?>" alt="" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <span class="text-[10px] text-slate-500">No image</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-slate-900 truncate"><?= htmlspecialchars($q['title']) ?></div>
                                        <div class="mt-1 text-xs text-slate-500 truncate"><?= htmlspecialchars($q['category_name'] ?? 'Uncategorized') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-2 text-sm text-slate-700"><?= htmlspecialchars($q['category_name'] ?? '-') ?></td>
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full overflow-hidden bg-slate-100 flex items-center justify-center border border-slate-200">
                                        <?php if (!empty($q['expert_avatar'])): ?>
                                            <img src="<?= BASE_URL ?>/uploads/profiles/<?= htmlspecialchars($q['expert_avatar']) ?>" alt="" class="w-full h-full object-cover">
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
                            <td class="text-center"><span class="px-3 py-1 rounded-full text-xs font-bold <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span></td>
                            <td class="text-right pr-2 text-sm text-slate-500"><?= date('d M Y', strtotime($q['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="py-8 text-center text-slate-400">No questions found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($userRole === 'expert'): ?>
    <div class="bg-white rounded-[32px] border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex items-center justify-between">
            <h2 class="text-lg font-extrabold text-slate-900">Assigned Questions</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Question</th>
                        <th class="px-6 py-4">Farmer</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    <?php if (!empty($questions)): ?>
                        <?php foreach ($questions as $q): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900"><?= htmlspecialchars($q['title']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($q['farmer_name'] ?? '-') ?></td>
                            <td class="px-6 py-4"><span class="inline-flex items-center px-2 py-1 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold"><?= htmlspecialchars($q['category_name'] ?? '-') ?></span></td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?= BASE_URL ?>/expert/questions/answer?id=<?= $q['question_id'] ?>" class="inline-flex items-center bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-xl text-xs font-bold">Answer</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-10 text-slate-400">No pending questions.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</section>
