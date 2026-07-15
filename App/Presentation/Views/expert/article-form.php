<?php
$mode = $mode ?? 'create';
$formAction = $formAction ?? '/expert/articles/store';
$submitLabel = $submitLabel ?? 'Publish Article';
$article = $article ?? null;

$defaultValues = [
    'title' => $article ? $article->getTitle() : '',
    'content' => $article ? $article->getContent() : '',
];

$currentImage = $article ? $article->getImage() : null;
$previewSource = $currentImage ? (BASE_URL . $currentImage) : '';
$message = $_SESSION['article_message'] ?? '';
unset($_SESSION['article_message']);
?>
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= BASE_URL ?>/expert/articles" class="p-2.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-[#0F172A] tracking-tight"><?= $mode === 'edit' ? 'Edit Article' : 'New Article' ?></h1>
            <p class="text-sm text-slate-500 mt-0.5"><?= $mode === 'edit' ? 'Update your article content.' : 'Share your knowledge with farmers.' ?></p>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="mb-6 px-5 py-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-sm font-semibold">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden">
        <form method="POST" action="<?= BASE_URL . htmlspecialchars($formAction) ?>" enctype="multipart/form-data" class="p-4 sm:p-5 space-y-4">
            <?php if ($mode === 'edit' && $article): ?>
                <input type="hidden" name="id" value="<?= $article->getId() ?>">
            <?php endif; ?>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($defaultValues['title']) ?>" class="w-full bg-slate-50/60 border border-slate-100 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 placeholder-slate-300 outline-none focus:border-[#15803D]/30 focus:bg-white focus:ring-4 focus:ring-[#15803D]/5 transition-all" placeholder="Enter article title" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Content</label>
                <textarea name="content" rows="6" class="w-full bg-slate-50/60 border border-slate-100 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 placeholder-slate-300 outline-none focus:border-[#15803D]/30 focus:bg-white focus:ring-4 focus:ring-[#15803D]/5 transition-all resize-none" placeholder="Write your article content here..." required><?= htmlspecialchars($defaultValues['content']) ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Featured Image</label>
                <div class="flex items-start gap-4">
                    <div class="relative w-28 h-28 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/50 flex items-center justify-center overflow-hidden group cursor-pointer" onclick="document.getElementById('imageInput').click()">
                        <?php if ($previewSource): ?>
                            <img id="imagePreview" src="<?= htmlspecialchars($previewSource) ?>" alt="Preview" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div id="imagePlaceholder" class="text-center p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                                </svg>
                                <span class="text-[10px] text-slate-400 font-semibold block mt-1">Click to upload</span>
                            </div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white text-[10px] font-bold">Change</span>
                        </div>
                    </div>
                    <div class="text-[11px] text-slate-400 leading-relaxed">
                        <p class="font-semibold">Accepted formats: JPG, PNG, GIF, WebP</p>
                        <p>Max file size: 5MB</p>
                        <p class="mt-1 text-slate-300">Recommended: 1200x630px</p>
                    </div>
                </div>
                <input type="file" id="imageInput" name="image" accept="image/*" class="hidden">
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-bold text-amber-700">This article will be submitted as <span class="uppercase">Pending</span> review by an admin.</span>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-md shadow-emerald-900/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <?= htmlspecialchars($submitLabel) ?>
                </button>
                <a href="<?= BASE_URL ?>/expert/articles" class="px-6 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('imageInput')?.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5MB limit!');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                const placeholder = document.getElementById('imagePlaceholder');
                if (preview) {
                    preview.setAttribute('src', e.target.result);
                } else {
                    const img = document.createElement('img');
                    img.id = 'imagePreview';
                    img.className = 'w-full h-full object-cover';
                    img.setAttribute('src', e.target.result);
                    const container = document.querySelector('.relative.w-28');
                    const oldPlaceholder = document.getElementById('imagePlaceholder');
                    if (oldPlaceholder) oldPlaceholder.remove();
                    container?.appendChild(img);
                }
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
