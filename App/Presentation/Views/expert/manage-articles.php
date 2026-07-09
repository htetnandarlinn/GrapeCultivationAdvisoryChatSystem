<?php
$articles = $articles ?? [];
$message = $_SESSION['article_message'] ?? '';
unset($_SESSION['article_message']);
$isAdmin = $isAdmin ?? false;
?>
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Article Management</h1>
            <p class="text-sm text-slate-500 mt-0.5"><?= $isAdmin ? 'Review and manage all expert-submitted articles.' : 'Manage your articles and share knowledge with farmers.' ?></p>
        </div>
        <?php if (can('articles.create')): ?>
            <a href="<?= BASE_URL ?>/expert/articles/create" class="inline-flex items-center gap-2 bg-[#15803D] hover:bg-[#116631] text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-md shadow-emerald-900/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Article
            </a>
        <?php endif; ?>
    </div>

    <?php if ($message): ?>
        <div class="mb-6 px-5 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-[0_4px_25px_rgba(0,0,0,0.01)] border border-slate-100 overflow-hidden">
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Title</th>
                        <?php if ($isAdmin): ?>
                            <th class="px-6 py-4">Author</th>
                        <?php endif; ?>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    <?php if (!empty($articles)): ?>
                        <?php foreach ($articles as $article): ?>
                            <?php
                            $st = $article->getStatus()->getValue();
                            $stColors = [
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'accepted' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                            ];
                            $stLabels = [
                                'pending' => 'Pending',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                            ];
                            $color = $stColors[$st] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                            $label = $stLabels[$st] ?? ucfirst($st);
                            ?>
                            <tr class="group hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <?php if ($article->getImage()): ?>
                                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                            <img src="<?= BASE_URL . htmlspecialchars($article->getImage()) ?>" alt="<?= htmlspecialchars($article->getTitle()) ?>" class="w-full h-full object-cover">
                                        </div>
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900 max-w-xs truncate">
                                    <?= htmlspecialchars($article->getTitle()) ?>
                                </td>
                                <?php if ($isAdmin): ?>
                                    <td class="px-6 py-4 text-slate-600">#<?= htmlspecialchars($article->getAuthorId()) ?></td>
                                <?php endif; ?>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border <?= $color ?>">
                                        <?= $label ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    <?= htmlspecialchars($article->getCreatedAt()->format('M d, Y')) ?>
                                </td>
                                <td class="py-5 text-right pr-2">
                                    <div class="flex justify-end gap-3">
                                        <a href="<?= BASE_URL ?>/expert/articles/view?id=<?= $article->getId() ?>" class="text-slate-400 hover:text-[#15803D] transition" title="View details">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <?php if (can('articles.edit')): ?>
                                            <a href="<?= BASE_URL ?>/expert/articles/edit?id=<?= $article->getId() ?>" class="text-slate-400 hover:text-blue-600 transition" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (can('articles.delete')): ?>
                                            <form method="POST" action="<?= BASE_URL ?>/expert/articles/delete" onsubmit="return confirm('Are you sure you want to delete this article?')" class="inline">
                                                <input type="hidden" name="id" value="<?= $article->getId() ?>">
                                                <button type="submit" class="text-slate-400 hover:text-rose-600 transition" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= $isAdmin ? 6 : 5 ?>" class="text-center py-16 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <p class="text-sm font-semibold">No articles yet</p>
                                <p class="text-xs mt-1"><?= $isAdmin ? 'No articles have been submitted by experts.' : 'Create your first article to share knowledge with farmers.' ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="block md:hidden divide-y divide-slate-100">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $article): ?>
                    <?php
                    $st = $article->getStatus()->getValue();
                    $stColors = [
                        'pending' => 'bg-amber-50 text-amber-700',
                        'accepted' => 'bg-emerald-50 text-emerald-700',
                        'rejected' => 'bg-rose-50 text-rose-700',
                    ];
                    $stLabels = [
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ];
                    $color = $stColors[$st] ?? 'bg-slate-50 text-slate-700';
                    $label = $stLabels[$st] ?? ucfirst($st);
                    ?>
                    <div class="p-5 space-y-3">
                        <div class="flex items-start gap-3">
                            <?php if ($article->getImage()): ?>
                                <div class="w-14 h-14 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0">
                                    <img src="<?= BASE_URL . htmlspecialchars($article->getImage()) ?>" alt="" class="w-full h-full object-cover">
                                </div>
                            <?php endif; ?>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-sm font-bold text-slate-900 truncate"><?= htmlspecialchars($article->getTitle()) ?></h4>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold <?= $color ?>">
                                    <?= $label ?>
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <a href="<?= BASE_URL ?>/expert/articles/view?id=<?= $article->getId() ?>" class="flex-1 text-center text-xs font-semibold text-slate-600 bg-slate-50 py-2 rounded-lg hover:bg-slate-100 transition">View</a>
                            <?php if (can('articles.edit')): ?>
                                <a href="<?= BASE_URL ?>/expert/articles/edit?id=<?= $article->getId() ?>" class="flex-1 text-center text-xs font-semibold text-blue-600 bg-blue-50 py-2 rounded-lg hover:bg-blue-100 transition">Edit</a>
                            <?php endif; ?>
                            <?php if (can('articles.delete')): ?>
                                <form method="POST" action="<?= BASE_URL ?>/expert/articles/delete" onsubmit="return confirm('Delete this article?')" class="flex-1">
                                    <input type="hidden" name="id" value="<?= $article->getId() ?>">
                                    <button type="submit" class="w-full text-xs font-semibold text-rose-600 bg-rose-50 py-2 rounded-lg hover:bg-rose-100 transition">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-8 text-center text-slate-400">
                    <p class="text-sm font-semibold">No articles yet</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
