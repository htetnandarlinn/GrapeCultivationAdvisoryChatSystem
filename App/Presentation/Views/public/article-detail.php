<div class="min-h-screen bg-gradient-to-b from-[#f8faf8] to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-8 py-12">
        <a href="<?= BASE_URL ?>/articles" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-[#15803D] transition mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Articles
        </a>

        <article class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-100 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight"><?= htmlspecialchars($article->getTitle()) ?></h1>
                <div class="text-right shrink-0">
                    <p class="text-xs font-bold text-slate-700"><?= htmlspecialchars($authorName) ?></p>
                    <p class="text-[10px] text-slate-400"><?= htmlspecialchars($article->getCreatedAt()->format('F d, Y')) ?></p>
                </div>
            </div>

            <?php if ($article->getImage()): ?>
                <div class="mt-6 rounded-2xl overflow-hidden border border-slate-100">
                    <img src="<?= BASE_URL . htmlspecialchars($article->getImage()) ?>" alt="<?= htmlspecialchars($article->getTitle()) ?>" class="w-full max-h-96 object-cover">
                </div>
            <?php endif; ?>

            <div class="mt-8 text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">
                <?= htmlspecialchars($article->getContent()) ?>
            </div>
        </article>
    </div>
</div>
