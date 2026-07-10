<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Article Management</h2>
        <p class="text-xs text-slate-500 mt-1"><?= $isAdmin ? 'Review and manage all expert-submitted articles.' : 'Manage your articles and share knowledge with farmers.' ?></p>
    </div>

    <?php if ($message): ?>
        <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-600"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (empty($articles)): ?>
        <div class="bg-white p-10 rounded-2xl border border-slate-100 shadow-sm text-center">
            <p class="text-sm text-slate-400 font-medium">No articles yet.</p>
        </div>
    <?php else: ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="max-h-[470px] overflow-y-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Image</th>
                        <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Title</th>
                        <?php if ($isAdmin): ?>
                        <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Author</th>
                        <?php endif; ?>
                        <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Created</th>
                        <th class="text-center px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article):
                        $st = $article->getStatus()->getValue();
                        $stColors = [
                            'pending' => 'bg-amber-100 text-amber-700',
                            'accepted' => 'bg-emerald-100 text-emerald-700',
                            'rejected' => 'bg-rose-100 text-rose-700',
                        ];
                        $stLabels = [
                            'pending' => 'Pending',
                            'accepted' => 'Accepted',
                            'rejected' => 'Rejected',
                        ];
                        $color = $stColors[$st] ?? 'bg-slate-100 text-slate-600';
                        $label = $stLabels[$st] ?? ucfirst($st);
                    ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">
                                <?php if ($article->getImage()): ?>
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                        <img src="<?= BASE_URL . htmlspecialchars($article->getImage()) ?>" alt="<?= htmlspecialchars($article->getTitle()) ?>" class="w-full h-full object-cover">
                                    </div>
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-xs font-semibold text-slate-900 max-w-[200px] truncate"><?= htmlspecialchars($article->getTitle()) ?></td>
                            <?php if ($isAdmin): ?>
                            <td class="px-4 py-3 text-xs text-slate-600">#<?= htmlspecialchars($article->getAuthorId()) ?></td>
                            <?php endif; ?>
                            <td class="px-4 py-3"><span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $color ?>"><?= $label ?></span></td>
                            <td class="px-4 py-3 text-[10px] text-slate-400"><?= htmlspecialchars($article->getCreatedAt()->format('M d, Y')) ?></td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="<?= BASE_URL ?>/expert/articles/view?id=<?= $article->getId() ?>" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-lg hover:bg-indigo-100 transition-colors">View</a>
                                    <?php if (can('articles.edit')): ?>
                                    <a href="<?= BASE_URL ?>/expert/articles/edit?id=<?= $article->getId() ?>" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-lg hover:bg-indigo-100 transition-colors">Edit</a>
                                    <?php endif; ?>
                                    <?php if (can('articles.delete')): ?>
                                    <form method="POST" action="<?= BASE_URL ?>/expert/articles/delete" onsubmit="return confirm('Are you sure?')" class="inline">
                                        <input type="hidden" name="id" value="<?= $article->getId() ?>">
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 text-[10px] font-bold rounded-lg hover:bg-red-100 transition-colors">Delete</button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</section>