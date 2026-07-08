<?php
$mode = $mode ?? 'create';
$formAction = $formAction ?? '/admin/experts/store';
$submitLabel = $submitLabel ?? 'Save Record';
$expert = $expert ?? null;
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

$defaultValues = [
    'username' => $expert ? $expert->getUsername() : ($old['username'] ?? ''),
    'email' => $expert ? $expert->getEmail()->getValue() : ($old['email'] ?? ''),
    'phone' => $expert ? $expert->getPhoneNumber() : ($old['phone'] ?? ''),
    'address' => $expert ? $expert->getAddress() : ($old['address'] ?? ''),
    'password' => $old['password'] ?? '',
];

$currentImage = $expert ? $expert->getProfileImage() : null;
$previewSource = $currentImage ? (BASE_URL . $currentImage) : "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2315803D'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z'/></svg>";
?>

<main class="flex-grow px-4 pb-8 pt-16 sm:px-6 lg:px-8 bg-slate-50/50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-xl bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 overflow-hidden transform animate-fade-in-up">
        
        <div class="h-24 bg-[#15803D] relative px-6 sm:px-8 flex items-end">
            <div class="absolute right-6 top-5 text-white/40 font-bold tracking-widest text-sm uppercase selection:bg-transparent pointer-events-none">
                EXPERT SYSTEM
            </div>
        </div>

        <div class="px-6 sm:px-8 pb-4 relative flex flex-col sm:flex-row sm:items-end gap-4 -mt-10 mb-2">
            <div class="relative w-20 h-20 bg-white p-1.5 rounded-xl shadow-md border border-slate-100 group/avatar inline-block flex-shrink-0">
                <div class="w-full h-full rounded-lg overflow-hidden bg-emerald-50 flex items-center justify-center">
                    <img id="avatarPreview" src="<?= htmlspecialchars($previewSource) ?>" alt="Preview" class="w-full h-full object-cover">
                </div>
                <label for="profile_image" class="absolute inset-0 bg-black/40 rounded-xl flex items-center justify-center opacity-0 group-hover/avatar:opacity-100 cursor-pointer transition-opacity duration-200 text-white text-[10px] font-medium">
                    Change
                </label>
            </div>
            
            <div class="mb-1">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-[#15803D] uppercase tracking-wider border border-emerald-200/50"><?= $mode === 'edit' ? 'EDIT RECORD' : 'NEW RECORD' ?></span>
                </div>
                <p class="text-[11px] text-slate-400 font-mono mt-0.5">System Object Reference: <?= $mode === 'edit' ? 'UPDATE-EXPERT' : 'PENDING-GEN' ?></p>
            </div>
        </div>

        <form id="expertForm" method="POST" action="<?= BASE_URL . htmlspecialchars($formAction) ?>" enctype="multipart/form-data" class="p-6 sm:p-8 pt-4 space-y-5">
            <?php if ($mode === 'edit' && $expert): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars((string) $expert->getId()) ?>">
            <?php endif; ?>
            <input type="file" id="profile_image" name="profile_image" accept="image/*" class="hidden">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="bg-slate-50/60 rounded-xl p-3 border border-slate-100 focus-within:border-[#15803D]/30 focus-within:bg-white focus-within:ring-4 focus-within:ring-[#15803D]/5 transition-all duration-200">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Name</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($defaultValues['username']) ?>" class="w-full bg-transparent text-sm font-semibold text-slate-800 placeholder-slate-300 outline-none" placeholder="Enter Name" required>
                    <?php if (!empty($errors['username'])): ?><p class="mt-1 text-[10px] text-red-600"><?= htmlspecialchars($errors['username']) ?></p><?php endif; ?>
                </div>

                <div class="bg-slate-50/60 rounded-xl p-3 border border-slate-100 focus-within:border-[#15803D]/30 focus-within:bg-white focus-within:ring-4 focus-within:ring-[#15803D]/5 transition-all duration-200">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($defaultValues['email']) ?>" class="w-full bg-transparent text-sm font-semibold text-slate-800 placeholder-slate-300 outline-none" placeholder="Enter Email" required>
                    <?php if (!empty($errors['email'])): ?><p class="mt-1 text-[10px] text-red-600"><?= htmlspecialchars($errors['email']) ?></p><?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="bg-slate-50/60 rounded-xl p-3 border border-slate-100 focus-within:border-[#15803D]/30 focus-within:bg-white focus-within:ring-4 focus-within:ring-[#15803D]/5 transition-all duration-200">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Phone Number</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($defaultValues['phone']) ?>" class="w-full bg-transparent text-sm font-semibold text-slate-800 placeholder-slate-300 outline-none" placeholder="Enter Phone">
                </div>

                <div class="bg-slate-50/60 rounded-xl p-3 border border-slate-100 focus-within:border-[#15803D]/30 focus-within:bg-white focus-within:ring-4 focus-within:ring-[#15803D]/5 transition-all duration-200 relative">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Security Credentials</label>
                    <div class="flex items-center justify-between">
                        <input id="password" type="password" name="password" value="<?= htmlspecialchars($defaultValues['password']) ?>" class="w-full bg-transparent text-sm font-semibold text-slate-800 placeholder-slate-300 outline-none pr-8" placeholder="<?= $mode === 'edit' ? 'Leave blank to keep current password' : '••••••••' ?>" <?= $mode === 'edit' ? '' : 'required' ?>>
                        <?php if (!empty($errors['password'])): ?><p class="mt-1 text-[10px] text-red-600"><?= htmlspecialchars($errors['password']) ?></p><?php endif; ?>
                        <button type="button" id="togglePassword" class="absolute right-3 bottom-3 text-slate-400 hover:text-slate-600 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644R26.27 10.566a11.303 11.303 0 011.13 1.897l1.13-1.897zm21.928.322a1.012 1.012 0 010-.644l-2.45-4.5A11.303 11.303 0 0012 4.5c-4.13 0-7.803 2.22-9.715 5.566z" />
                                <circle cx="12" cy="12" r="3" stroke="currentColor" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50/60 rounded-xl p-3 border border-slate-100 focus-within:border-[#15803D]/30 focus-within:bg-white focus-within:ring-4 focus-within:ring-[#15803D]/5 transition-all duration-200">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Domain Authorization Boundary Address</label>
                <textarea name="address" rows="2" class="w-full bg-transparent text-sm font-semibold text-slate-800 placeholder-slate-300 outline-none resize-none" placeholder="Provide physical deployment area address..."><?= htmlspecialchars($defaultValues['address']) ?></textarea>
            </div>

            <div class="pt-2 flex justify-end">
                <button
                    type="submit"
                    id="submitBtn"
                    class="w-full sm:w-auto min-w-[160px] flex items-center justify-center gap-2 bg-[#15803D] hover:bg-[#116631] text-white text-xs font-bold uppercase tracking-wider px-6 py-3 rounded-xl shadow-md shadow-[#15803D]/10 hover:shadow-lg active:scale-[0.98] transition-all duration-150">
                    <span id="btnText"><?= htmlspecialchars($submitLabel) ?></span>
                    <svg id="btnSpinner" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</main>

<script>
    // Live Image Preview Engine
    const fileInput = document.getElementById('profile_image');
    const avatarPreview = document.getElementById('avatarPreview');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                alert("File size exceeds 5MB limit!");
                this.value = "";
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Password Toggle Utility
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePassword');

    togglePasswordBtn.addEventListener('click', () => {
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
    });

    // Button Submit Loading Interaction
    const form = document.getElementById('expertForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    form.addEventListener('submit', () => {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
        btnText.textContent = 'SAVING...';
        btnSpinner.classList.remove('hidden');
    });
</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>