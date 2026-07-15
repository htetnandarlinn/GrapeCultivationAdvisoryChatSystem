<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-3 mb-8">
        <a href="<?= BASE_URL ?>/dashboard" class="p-2.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Article Images</h2>
            <p class="text-xs text-slate-500 mt-1">All images you have uploaded for your articles.</p>
        </div>
    </div>

    <?php if (empty($images)): ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
        </div>
        <p class="text-sm font-semibold text-slate-600">No images uploaded</p>
        <p class="text-xs text-slate-400 mt-1">Images will appear here once you add them to your articles.</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <?php foreach ($images as $article): ?>
        <a href="<?= BASE_URL ?>/expert/articles/view?id=<?= $article->getId() ?>" class="group block bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">
            <div class="aspect-square bg-slate-50 overflow-hidden">
                <img src="<?= BASE_URL . htmlspecialchars($article->getImage()) ?>" alt="<?= htmlspecialchars($article->getTitle()) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <div class="p-3">
                <p class="text-[11px] font-semibold text-slate-800 truncate"><?= htmlspecialchars($article->getTitle()) ?></p>
                <p class="text-[9px] text-slate-400 mt-0.5"><?= $article->getCreatedAt()->format('M d, Y') ?></p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>
