<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="<?= BASE_URL ?>/consultation/history" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            Back
        </a>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">New Consultation</h2>
        <p class="text-xs text-slate-500 mt-1">Describe your grape cultivation issue to get expert advice.</p>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-xs font-semibold text-red-600"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/consultation/store" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-5">
        <div>
            <label for="title" class="block text-xs font-bold text-slate-700 mb-1.5">Title</label>
            <input type="text" id="title" name="title" required
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                   placeholder="e.g., Grape leaf discoloration problem">
        </div>

        <div>
            <label for="description" class="block text-xs font-bold text-slate-700 mb-1.5">Description</label>
            <textarea id="description" name="description" rows="6" required
                      class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all resize-y"
                      placeholder="Describe your issue in detail..."></textarea>
        </div>

        <div>
            <label for="images" class="block text-xs font-bold text-slate-700 mb-1.5">Images (optional)</label>
            <input type="file" id="images" name="images[]" multiple accept="image/*"
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            <p class="text-[10px] text-slate-400 mt-1">Upload images of your grape issue for better diagnosis.</p>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-[#15803D] text-white text-sm font-bold rounded-xl hover:bg-green-800 transition-colors">
                Submit Consultation
            </button>
            <a href="<?= BASE_URL ?>/" class="px-6 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</section>
