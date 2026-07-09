<?php
$message = $_SESSION['article_message'] ?? '';
unset($_SESSION['article_message']);

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

<main class="max-w-5xl mx-auto px-6 py-10 animate__animated animate__fadeIn">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?= BASE_URL ?>/expert/articles" class="p-2.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900">Article Detail</h1>
            <p class="text-slate-500">Review the article content and manage its status below.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-100 shadow-sm">
        <div class="flex items-start justify-between gap-6 mb-8">
            <div class="min-w-0 flex-1">
                <h2 class="text-3xl font-black text-slate-900"><?= htmlspecialchars($article->getTitle()) ?></h2>
                <div class="flex items-center gap-4 mt-3 text-slate-500">
                    <span class="font-medium">Author ID: #<?= htmlspecialchars($article->getAuthorId()) ?></span>
                    <span class="text-slate-300">•</span>
                    <span><?= htmlspecialchars($article->getCreatedAt()->format('F d, Y \a\t h:i A')) ?></span>
                </div>
            </div>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border <?= $color ?>">
                <?= $label ?>
            </span>
        </div>

        <?php if ($article->getImage()): ?>
            <div class="mb-8 rounded-2xl overflow-hidden border border-slate-100 shadow-inner">
                <img src="<?= BASE_URL . htmlspecialchars($article->getImage()) ?>" alt="Article" class="w-full h-80 object-cover">
            </div>
        <?php endif; ?>

        <div class="prose prose-slate max-w-none text-base text-slate-700 leading-relaxed mb-8">
            <?= nl2br(htmlspecialchars($article->getContent())) ?>
        </div>

        <?php if ($st === 'rejected' && $article->getRejectionNote()): ?>
            <div class="mb-8 p-6 rounded-2xl bg-rose-50 border border-rose-100">
                <div class="flex items-center gap-2 text-sm font-bold text-rose-700 uppercase tracking-widest mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Rejection Reason
                </div>
                <p class="text-rose-800 text-base"><?= htmlspecialchars($article->getRejectionNote()) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($st === 'pending' && can('articles.edit')): ?>
            <div class="pt-8 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <form action="<?= BASE_URL ?>/expert/articles/accept" method="POST">
                        <input type="hidden" name="id" value="<?= $article->getId() ?>">
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-emerald-700 transition shadow-sm">Accept Article</button>
                    </form>
                    <button type="button" onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="bg-rose-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-rose-700 transition shadow-sm">Reject</button>
                </div>
                <form id="rejectForm" action="<?= BASE_URL ?>/expert/articles/reject" method="POST" class="hidden mt-6 p-6 rounded-2xl bg-rose-50 border border-rose-100">
                    <input type="hidden" name="id" value="<?= $article->getId() ?>">
                    <textarea name="rejection_note" rows="4" class="w-full bg-white border border-rose-200 rounded-xl p-4 text-base focus:ring-4 focus:ring-rose-100 outline-none transition" placeholder="Please provide a detailed reason for rejection..." required></textarea>
                    <div class="flex gap-3 mt-4">
                        <button type="submit" class="bg-rose-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-rose-700">Submit Decision</button>
                        <button type="button" onclick="document.getElementById('rejectForm').classList.add('hidden')" class="text-slate-500 font-bold px-6">Cancel</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>