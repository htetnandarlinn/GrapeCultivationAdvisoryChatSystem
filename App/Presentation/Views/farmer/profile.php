<?php
/** @var \App\Domain\UserManagement\Entities\User $user */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Grape Advisory System</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(24px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes subtlePulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(2, 150, 100, 0.2); }
            50% { transform: scale(1.04); box-shadow: 0 0 0 8px rgba(2, 150, 100, 0); }
        }
        .animate-slide-in {
            animation: slideInRight 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-profile-pulse {
            animation: subtlePulse 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-[#f8f9fa] min-h-screen antialiased selection:bg-emerald-100 selection:text-emerald-900">

<main class="w-full max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8 animate-slide-in">
    
    <div class="flex items-center justify-between mb-6 border-b border-slate-200/60 pb-4">
        <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-400">
            <span class="hover:text-slate-600 transition-colors">Dashboard</span>
            <span class="text-slate-300">/</span>
            <span class="text-[#029664]">My Profile</span>
        </nav>
        <h1 class="text-sm font-black text-slate-800 tracking-tight">My Profile Details</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-100 shadow-xs p-5 text-center flex flex-col items-center justify-between h-full">
            <div class="w-full flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-emerald-50 border-4 border-emerald-500/10 shadow-inner overflow-hidden flex items-center justify-center text-[#029664] font-black text-2xl mb-3 transition-transform duration-300 animate-profile-pulse">
                    <?php if (!empty($user->getProfileImage())): ?>
                        <img src="<?= BASE_URL . $user->getProfileImage(); ?>" alt="Profile" class="w-full h-full object-cover">
                    <?php else: ?>
                        <?= strtoupper(substr($user->getUsername(), 0, 2)); ?>
                    <?php endif; ?>
                </div>
                
                <h2 class="text-lg font-bold text-slate-900 tracking-tight mb-0.5">
                    <?= htmlspecialchars($user->getUsername()); ?>
                </h2>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                    Registered <?= htmlspecialchars(ucfirst($user->getType()->getValue())); ?>
                </p>
                <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#029664]"></span>
                    Verified Account
                </div>

                <div class="w-full space-y-2.5 text-left border-t border-slate-100 pt-3 mb-4 text-xs text-slate-500">
                    <div class="flex justify-between items-center">
                        <span>User Role:</span>
                        <span class="font-bold text-[#029664] uppercase tracking-wider"><?= htmlspecialchars($user->getType()->getValue()); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Email Address:</span>
                        <span class="font-semibold text-slate-800 break-all"><?= htmlspecialchars($user->getEmail()->getValue()); ?></span>
                    </div>
                </div>
            </div>

            <div class="w-full space-y-1 pt-3 border-t border-slate-100/60">
                <div class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-emerald-50 text-[#029664] font-bold text-xs border border-emerald-100/70 shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    Personal Info
                </div>
                <a href="<?= BASE_URL ?>/profile/edit" class="flex items-center justify-between w-full px-3.5 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 font-semibold text-xs transition-all group">
                    <span class="flex items-center gap-3">
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.827m11.379-8.16l1.15-.827M8.14 21.27l.707-1.03m7.45-.808l.707-1.03M12 3v1.5m0 15V21m-4.743-10l-1.149-.827m11.379 8.16l-1.149-.827m-4.339 5.41l-.707-1.03m7.45-.808l-.707-1.03"/></svg>
                        Settings
                    </span>
                    <svg class="w-3 h-3 text-slate-300 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
        </div>

        <div class="lg:col-span-8 bg-white p-5 sm:p-6 rounded-2xl border border-slate-100 shadow-2xs flex flex-col justify-between space-y-5">
            <div>
                <h3 class="text-base font-bold text-slate-800 tracking-tight border-b border-slate-100 pb-2 mb-4">Profile Overview</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    
                    <div class="bg-slate-50/60 p-3.5 rounded-xl border border-slate-100/80 shadow-3xs">
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-0.5">Username</p>
                        <p class="text-xs font-bold text-slate-800"><?= htmlspecialchars($user->getUsername()); ?></p>
                    </div>

                    <div class="bg-slate-50/60 p-3.5 rounded-xl border border-slate-100/80 shadow-3xs flex items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-0.5">Email Address</p>
                            <p class="text-xs font-bold text-slate-800 break-all"><?= htmlspecialchars($user->getEmail()->getValue()); ?></p>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-[#029664] border border-emerald-100">
                            Verified
                        </span>
                    </div>

                    <div class="bg-slate-50/60 p-3.5 rounded-xl border border-slate-100/80 shadow-3xs">
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-0.5">Phone Connection</p>
                        <p class="text-xs font-bold text-slate-800"><?= htmlspecialchars($user->getPhoneNumber() ?: 'Not specified'); ?></p>
                    </div>

                    <div class="bg-slate-50/60 p-3.5 rounded-xl border border-slate-100/80 shadow-3xs">
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-0.5">Ecosystem Permissions Role</p>
                        <p class="text-xs font-bold text-slate-800 capitalize"><?= htmlspecialchars($user->getType()->getValue()); ?></p>
                    </div>

                    <div class="bg-slate-50/60 p-3.5 rounded-xl border border-slate-100/80 shadow-3xs md:col-span-2">
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-0.5">Cultivation Address Location</p>
                        <p class="text-xs font-semibold text-slate-700 leading-relaxed">
                            <?= htmlspecialchars($user->getAddress() ?: 'No background cultivation address location provided.'); ?>
                        </p>
                    </div>

                </div>
            </div>

            <div class="flex justify-end pt-3 border-t border-slate-100">
                <a href="<?= BASE_URL ?>/profile/edit" class="inline-flex items-center gap-1.5 px-5 py-2 bg-[#029664] hover:bg-emerald-700 text-white font-bold text-[11px] rounded-xl shadow-xs transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 11-2.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                    Modify Information Setting
                </a>
            </div>

        </div>
    </div>
</main>

</body>
</html>