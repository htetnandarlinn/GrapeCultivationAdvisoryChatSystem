<?php
/** @var \App\Domain\UserManagement\Entities\User $user */
?>
<section class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">My Profile</h2>
            <p class="text-sm text-slate-500 mt-1">View your account details.</p>
        </div>
        <a href="<?= BASE_URL ?>/profile/edit" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-[#15803D] hover:bg-green-700 text-white font-bold text-xs rounded-xl transition-all">Edit Profile</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        <div class="lg:col-span-4 bg-white rounded-[28px] border border-slate-200 p-6 text-center flex flex-col items-center justify-between h-full">
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
                <div class="w-full space-y-2.5 text-left border-t border-slate-100 pt-3 mb-4 text-xs text-slate-500">
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
        </div>

        <div class="lg:col-span-8 bg-white rounded-[28px] border border-slate-200 p-6 space-y-5">
            <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-2">Profile Overview</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Username</p>
                    <p class="text-sm font-bold text-slate-800"><?= htmlspecialchars($user->getUsername()); ?></p>
                </div>
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Email</p>
                        <p class="text-sm font-bold text-slate-800 break-all"><?= htmlspecialchars($user->getEmail()->getValue()); ?></p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-[#15803D] border border-emerald-100">Verified</span>
                </div>
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Phone</p>
                    <p class="text-sm font-bold text-slate-800"><?= htmlspecialchars($user->getPhoneNumber() ?: 'Not specified'); ?></p>
                </div>
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Account Type</p>
                    <p class="text-sm font-bold text-slate-800 capitalize"><?= htmlspecialchars($user->getType()->getValue()); ?></p>
                </div>
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 md:col-span-2">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Address</p>
                    <p class="text-sm font-semibold text-slate-700"><?= htmlspecialchars($user->getAddress() ?: 'Not provided.'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
