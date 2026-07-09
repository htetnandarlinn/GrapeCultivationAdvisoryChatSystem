<main class="max-w-7xl mx-auto px-4 sm:px-8 pb-8 pt-10 space-y-6 animate__animated animate__fadeIn">
    <div>
        <h1 class="text-xl font-black text-slate-900">Question Management</h1>
        <p class="text-sm text-slate-500">Monitor and manage all incoming advisory questions.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[9px] uppercase tracking-widest text-slate-400 border-b border-slate-50 bg-slate-50/50">
                        <th class="py-4 pl-6">Farmer</th>
                        <th class="py-4">Category</th>
                        <th class="py-4">Title</th>
                        <th class="py-4">Status</th>
                        <th class="py-4">Expert</th>
                        <th class="py-4">Created</th>
                        <th class="py-4 text-right pr-6">Action</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-slate-700">
                    <?php if (empty($questions)): ?>
                        <tr><td colspan="7" class="py-12 text-center text-slate-400">No questions found.</td></tr>
                    <?php else: foreach ($questions as $question): ?>
                        <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition">
                            <td class="py-4 pl-6 font-bold text-slate-900"><?= htmlspecialchars($question['farmer_name']) ?></td>
                            <td class="py-4"><?= htmlspecialchars($question['category_name']) ?></td>
                            <td class="py-4 font-medium text-slate-900"><?= htmlspecialchars($question['title']) ?></td>
                            <td class="py-4">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600">
                                    <?= htmlspecialchars($question['status_name']) ?>
                                </span>
                            </td>
                            <td class="py-4"><?= htmlspecialchars($question['expert_name'] ?? '-') ?></td>
                            <td class="py-4"><?= htmlspecialchars(date('M d, Y', strtotime($question['created_at']))) ?></td>
                            <td class="py-4 text-right pr-6">
                                <a href="<?= BASE_URL ?>/admin/questions/view?id=<?= $question['question_id'] ?>"
                                   class="bg-[#15803D] hover:bg-green-900 text-white px-4 py-1.5 rounded-lg text-xs font-bold transition">
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>