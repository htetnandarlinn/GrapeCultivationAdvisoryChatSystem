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
<main class="flex-grow px-4 sm:px-8 pb-8 pt-28 space-y-6 animate__animated animate__fadeIn">
    <div class="flex items-center gap-3 mb-2">
        <a href="<?= BASE_URL ?>/expert/articles" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-900">Article Detail</h1>
            <p class="text-sm text-slate-500">Review the article content below.</p>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4 text-sm text-emerald-700">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-white/50 shadow-sm max-w-4xl">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-black text-slate-900"><?= htmlspecialchars($article->getTitle()) ?></h2>
                <div class="flex items-center gap-3 mt-2 text-sm text-slate-500">
                    <span>Author ID: #<?= htmlspecialchars($article->getAuthorId()) ?></span>
                    <span class="text-slate-300">|</span>
                    <span><?= htmlspecialchars($article->getCreatedAt()->format('F d, Y \a\t h:i A')) ?></span>
                </div>
            </div>
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold border flex-shrink-0 <?= $color ?>">
                <?= $label ?>
            </span>
        </div>

        <?php if ($article->getImage()): ?>
            <div class="mb-6 rounded-2xl overflow-hidden border border-slate-200">
                <img src="<?= BASE_URL . htmlspecialchars($article->getImage()) ?>" alt="<?= htmlspecialchars($article->getTitle()) ?>" class="w-full max-h-96 object-cover">
            </div>
        <?php endif; ?>

        <div class="prose prose-slate max-w-none text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">
            <?= htmlspecialchars($article->getContent()) ?>
        </div>

        <?php if ($st === 'rejected' && $article->getRejectionNote()): ?>
            <div class="mt-6 p-4 rounded-xl bg-rose-50 border border-rose-200">
                <div class="flex items-center gap-2 text-xs font-bold text-rose-700 uppercase tracking-wider mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Rejection Reason
                </div>
                <p class="text-sm text-rose-700"><?= htmlspecialchars($article->getRejectionNote()) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($st === 'pending' && can('articles.edit')): ?>
            <div class="mt-8 pt-6 border-t border-slate-100">
                <div class="flex items-center gap-3">
                    <form action="<?= BASE_URL ?>/expert/articles/accept" method="POST">
                        <input type="hidden" name="id" value="<?= $article->getId() ?>">
                        <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-emerald-700 transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Accept
                        </button>
                    </form>
                    <span class="text-slate-300 text-xs font-bold">OR</span>
                    <div>
                        <button type="button" onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="inline-flex items-center gap-2 bg-rose-600 text-white px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-rose-700 transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reject
                        </button>
                    </div>
                </div>
                <form id="rejectForm" action="<?= BASE_URL ?>/expert/articles/reject" method="POST" class="hidden mt-4 p-4 rounded-xl bg-rose-50 border border-rose-200">
                    <input type="hidden" name="id" value="<?= $article->getId() ?>">
                    <label class="block text-xs font-bold text-rose-700 uppercase tracking-wider mb-2">Rejection Note</label>
                    <textarea name="rejection_note" rows="3" class="w-full bg-white border border-rose-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-300 outline-none focus:border-rose-400 focus:ring-4 focus:ring-rose-100 transition-all resize-none" placeholder="Explain why this article is being rejected..." required></textarea>
                    <div class="flex gap-2 mt-3">
                        <button type="submit" class="bg-rose-600 text-white px-5 py-2 rounded-xl text-xs font-bold hover:bg-rose-700 transition">Confirm Reject</button>
                        <button type="button" onclick="document.getElementById('rejectForm').classList.add('hidden')" class="border border-slate-200 text-slate-600 px-5 py-2 rounded-xl text-xs font-bold hover:bg-slate-50 transition">Cancel</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>
