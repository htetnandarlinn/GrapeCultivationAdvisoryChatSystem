<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php $isAdmin = $isAdmin ?? false; ?>
    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/dashboard" class="p-2.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Article Management</h2>
                <p class="text-xs text-slate-500 mt-1"><?= $isAdmin ? 'Review and manage all expert-submitted articles.' : 'Manage your articles and share knowledge with farmers.' ?></p>
            </div>
        </div>
        <?php if (can('articles.create')): ?>
        <a href="<?= BASE_URL ?>/expert/articles/create" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-emerald-700 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add New Article
        </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($message ?? null)): ?>
        <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-600"><?= htmlspecialchars($message ?? '') ?></div>
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
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="<?= BASE_URL ?>/expert/articles/view?id=<?= $article->getId() ?>" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <?php if (can('articles.edit')): ?>
                                    <a href="<?= BASE_URL ?>/expert/articles/edit?id=<?= $article->getId() ?>" class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (can('articles.delete')): ?>
                                    <form method="POST" action="<?= BASE_URL ?>/expert/articles/delete" onsubmit="return confirm('Are you sure?')" class="inline">
                                        <input type="hidden" name="id" value="<?= $article->getId() ?>">
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
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