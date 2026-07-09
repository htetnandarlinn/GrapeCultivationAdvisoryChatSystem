<?php
$articles = $articles ?? [];
?>
<div class="min-h-screen bg-gradient-to-b from-[#f8faf8] to-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-8 py-12">
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Cultivation Articles</h1>
            <p class="text-sm text-slate-500 mt-2 max-w-lg mx-auto">Expert-written guides and articles to help you improve your grape farming.</p>
        </div>

        <?php if (empty($articles)): ?>
            <div class="text-center py-20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <p class="text-sm font-semibold text-slate-400">No articles available yet.</p>
                <p class="text-xs text-slate-300 mt-1">Check back soon for new cultivation guides.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($articles as $article): ?>
                    <a href="<?= BASE_URL ?>/articles/view?id=<?= $article->getId() ?>" class="group bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <?php if ($article->getImage()): ?>
                            <div class="aspect-[16/9] overflow-hidden bg-slate-100">
                                <img src="<?= BASE_URL . htmlspecialchars($article->getImage()) ?>" alt="<?= htmlspecialchars($article->getTitle()) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        <?php else: ?>
                            <div class="aspect-[16/9] bg-gradient-to-br from-[#e8f5e9] to-[#c8e6c9] flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#2e7d32]/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                        <?php endif; ?>
                        <div class="p-5">
                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-[#15803D] transition-colors line-clamp-2"><?= htmlspecialchars($article->getTitle()) ?></h3>
                            <p class="text-xs text-slate-400 mt-2"><?= htmlspecialchars($article->getCreatedAt()->format('F d, Y')) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
