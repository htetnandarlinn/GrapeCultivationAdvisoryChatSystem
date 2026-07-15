<?php
/** @var \App\Domain\UserManagement\Entities\User $user */
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
$success = $_SESSION['success'] ?? '';

unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['success']);

$componentOld = array_merge([
    'username' => $user->getUsername(),
    'email' => $user->getEmail()->getValue(),
    'phone' => $user->getPhoneNumber(),
    'address' => $user->getAddress(),
    'role' => $user->getType()->getValue(),
], $old);

$componentErrors = $errors;
?>
<style>
    .brand-input-focus input:focus, 
    .brand-input-focus textarea:focus {
        border-color: #15803D !important;
        box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.15) !important;
    }
</style>

<section class="space-y-6 brand-input-focus">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Edit Profile</h2>
            <p class="text-sm text-slate-500 mt-1">Update your account details.</p>
        </div>
        <a href="<?= BASE_URL ?>/profile" class="p-2.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition inline-flex">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        <div class="lg:col-span-4 bg-white rounded-[28px] border border-slate-200 p-5 text-center flex flex-col items-center justify-between h-full">
            <div class="w-full flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-emerald-50 border-4 border-emerald-500/10 overflow-hidden flex items-center justify-center text-[#15803D] font-black text-2xl mb-3">
                    <?php if (!empty($user->getProfileImage())): ?>
                        <img src="<?= BASE_URL . $user->getProfileImage(); ?>" alt="Profile" class="w-full h-full object-cover">
                    <?php else: ?>
                        <?= strtoupper(substr($user->getUsername(), 0, 2)); ?>
                    <?php endif; ?>
                </div>
                <h2 class="text-lg font-bold text-slate-900"><?= htmlspecialchars($user->getUsername()); ?></h2>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2"><?= htmlspecialchars(ucfirst($user->getType()->getValue())); ?></p>
                <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#15803D]"></span>Verified Account
                </div>
                <div class="w-full space-y-2 text-left border-t border-slate-100 pt-3 mb-4 text-xs text-slate-500">
                    <div class="flex justify-between items-center">
                        <span>User Role:</span>
                        <span class="font-bold text-[#15803D] uppercase tracking-wider"><?= htmlspecialchars($user->getType()->getValue()); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Email:</span>
                        <span class="font-semibold text-slate-800 break-all"><?= htmlspecialchars($user->getEmail()->getValue()); ?></span>
                    </div>
                </div>
            </div>
            <div class="w-full space-y-0.5 pt-3 border-t border-slate-100">
                <a href="<?= BASE_URL ?>/profile" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-slate-600 hover:bg-slate-50 font-semibold text-xs transition-all group">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    Personal Info
                </a>
                <div class="flex items-center justify-between w-full px-3.5 py-2 rounded-xl bg-emerald-50 text-[#15803D] font-bold text-xs border border-emerald-100 shadow-xs">
                    <span class="flex items-center gap-2.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.827m11.379-8.16l1.15-.827M8.14 21.27l.707-1.03m7.45-.808l.707-1.03M12 3v1.5m0 15V21m-4.743-10l-1.149-.827m11.379 8.16l-1.149-.827m-4.339 5.41l-.707-1.03m7.45-.808l-.707-1.03"/></svg>
                        Settings
                    </span>
                    <svg class="w-2.5 h-2.5 text-[#15803D]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 bg-white rounded-[28px] border border-slate-200 p-6">
            <form action="<?= BASE_URL ?>/profile/update" method="POST" enctype="multipart/form-data" novalidate class="space-y-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">Profile Overview</h3>

                    <?php include __DIR__ . '/../components/flash.php'; ?>

                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100 mb-4">
                        <div class="relative group w-10 h-10 rounded-full overflow-hidden bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0">
                            <?php if (!empty($user->getProfileImage())): ?>
                                <img src="<?= BASE_URL . $user->getProfileImage(); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-400"></div>
                            <?php endif; ?>
                            <label for="avatar-upload" class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center text-white text-[8px] font-black cursor-pointer transition-opacity">EDIT</label>
                            <input id="avatar-upload" type="file" name="avatar" accept="image/*" class="hidden">
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-xs">Profile Avatar</h4>
                            <p class="text-[10px] text-slate-400">JPG, PNG or WebP up to 2MB.</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="[&_label]:text-[11px] [&_label]:mb-0.5 [&_input]:py-1.5 [&_input]:px-3 [&_input]:text-xs">
                                <?php $label = 'Name'; $name = 'username'; include __DIR__ . '/../components/input.php'; ?>
                            </div>
                            <div class="[&_label]:text-[11px] [&_label]:mb-0.5 [&_input]:py-1.5 [&_input]:px-3 [&_input]:text-xs">
                                <?php $label = 'Email'; $name = 'email'; $type = 'email'; include __DIR__ . '/../components/input.php'; ?>
                            </div>
                            <div class="[&_label]:text-[11px] [&_label]:mb-0.5 [&_input]:py-1.5 [&_input]:px-3 [&_input]:text-xs">
                                <?php $label = 'Phone'; $name = 'phone'; include __DIR__ . '/../components/input.php'; ?>
                            </div>
                            <div class="md:col-span-2 [&_label]:text-[11px] [&_label]:mb-0.5 [&_input]:py-1.5 [&_input]:px-3 [&_input]:text-xs">
                                <?php $label = 'Address'; $name = 'address'; include __DIR__ . '/../components/input.php'; ?>
                            </div>
                            <div>
                                <label class="block mb-0.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Role</label>
                                <input type="text" value="<?= htmlspecialchars(ucfirst($user->getType()->getValue())); ?>" class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 mt-3 border-t border-slate-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="[&_label]:text-[11px] [&_label]:mb-0.5 [&_input]:py-1.5 [&_input]:px-3 [&_input]:text-xs [&_button]:text-xs">
                                <?php $label = 'New Password'; $name = 'password'; $placeholder = 'Leave blank to keep current'; include __DIR__ . '/../components/password-input.php'; ?>
                            </div>
                            <div class="[&_label]:text-[11px] [&_label]:mb-0.5 [&_input]:py-1.5 [&_input]:px-3 [&_input]:text-xs [&_button]:text-xs">
                                <?php $label = 'Confirm Password'; $name = 'confirm_password'; $placeholder = 'Confirm new password'; include __DIR__ . '/../components/password-input.php'; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <a href="<?= BASE_URL ?>/profile" class="px-3 py-2 text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                    <div class="[&_button]:inline-flex [&_button]:items-center [&_button]:gap-1.5 [&_button]:px-5 [&_button]:py-2 [&_button]:bg-[#15803D] [&_button]:hover:bg-green-700 [&_button]:text-white [&_button]:font-bold [&_button]:text-xs [&_button]:rounded-xl [&_button]:transition-all [&_button]:cursor-pointer">
                        <?php $text = 'Save Changes'; include __DIR__ . '/../components/button.php'; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
function togglePassword(id, button) {
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
        button.innerHTML = "🙈";
    } else {
        input.type = "password";
        button.innerHTML = "👁";
    }
}
</script>
