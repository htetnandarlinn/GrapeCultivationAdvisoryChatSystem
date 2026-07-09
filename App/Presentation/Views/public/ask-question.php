<?php
$errors  = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? null;
unset($_SESSION['errors'], $_SESSION['success']);
?>
<div class="min-h-screen bg-gradient-to-b from-[#f8faf8] to-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-8 py-12">
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-100 shadow-sm">
            <div class="mb-8">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Ask an Expert</h1>
                <p class="text-sm text-slate-500 mt-1">Struggling with a crop issue? Submit details directly to our experts.</p>
            </div>

            <?php if ($success): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium"><?= htmlspecialchars($success) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl">
                    <div class="flex items-center gap-3 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-bold">Please correct the following errors:</span>
                    </div>
                    <ul class="list-disc pl-8 space-y-1 text-xs font-medium">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/consultation/ask" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:border-[#15803D]/30 focus:bg-white focus:ring-4 focus:ring-[#15803D]/5 transition-all appearance-none">
                        <option value="" disabled selected hidden>Select category...</option>
                        <?php if (!empty($categories) && is_array($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['label']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No categories available</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">What is the main issue? <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="e.g. White powdery spots on leaves" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-300 outline-none focus:border-[#15803D]/30 focus:bg-white focus:ring-4 focus:ring-[#15803D]/5 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Detailed Description <span class="text-red-500">*</span></label>
                    <textarea name="description" required rows="5" placeholder="Describe symptoms, crop age, and treatments applied..." class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 placeholder-slate-300 outline-none focus:border-[#15803D]/30 focus:bg-white focus:ring-4 focus:ring-[#15803D]/5 transition-all resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Image <span class="text-slate-300 font-normal">(Optional)</span></label>
                    <div class="border-2 border-dashed border-slate-200 hover:border-[#15803D]/40 bg-slate-50/50 rounded-2xl p-6 transition-all text-center cursor-pointer relative" onclick="document.getElementById('imageInput').click()">
                        <input type="file" name="image" id="imageInput" accept="image/*" class="hidden">
                        <div id="uploadPlaceholder">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <p class="text-sm font-semibold text-slate-500">Click to upload an image</p>
                            <p class="text-xs text-slate-400 mt-1">PNG, JPG, WEBP (Max 5MB)</p>
                        </div>
                        <div id="uploadPreview" class="hidden">
                            <img id="previewImg" src="#" alt="Preview" class="w-32 h-32 object-cover mx-auto rounded-xl border border-slate-200">
                            <p id="fileName" class="text-xs font-medium text-slate-600 mt-2"></p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#15803D] hover:bg-[#116631] text-white py-3.5 rounded-xl text-sm font-bold transition-all shadow-md shadow-emerald-900/10 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Submit to Expert
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('imageInput')?.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        if (file.size > 5 * 1024 * 1024) {
            alert('File size exceeds 5MB limit.');
            this.value = '';
            return;
        }
        const reader = new FileReader();
        document.getElementById('fileName').textContent = file.name;
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('uploadPlaceholder').classList.add('hidden');
            document.getElementById('uploadPreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
