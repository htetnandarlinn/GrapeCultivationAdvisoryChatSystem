<?php
$expertUser = $_SESSION['user'] ?? [
    'username' => 'Expert',
    'role' => 'Expert',
    'avatar' => null,
];
$expertInitials = strtoupper(substr($expertUser['username'], 0, 2));
?>
<header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-100 fixed top-0 right-0 left-0 md:left-72 z-30 px-4 sm:px-8 flex items-center justify-between">
    <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-50 transition active:scale-95">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
    </button>

    <div class="hidden sm:block">
        <span class="text-lg font-extrabold text-[#0F172A] tracking-tight">Dashboard</span>
    </div>

    <div class="flex items-center gap-4 ml-auto">
        <button class="relative p-2 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
            <span class="absolute top-1.5 right-1.5 h-4 w-4 bg-rose-500 text-[10px] font-black text-white flex items-center justify-center rounded-full ring-2 ring-white">5</span>
        </button>

        <div class="relative" id="profileDropdownContainer">
            <button id="profileDropdownTrigger" class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-slate-50 transition text-left">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-xs flex items-center justify-center border border-emerald-200 overflow-hidden shrink-0">
                    <?php if (!empty($expertUser['avatar'])): ?>
                        <img src="<?= BASE_URL . htmlspecialchars($expertUser['avatar']) ?>" alt="Profile" class="w-full h-full object-cover">
                    <?php else: ?>
                        <?= htmlspecialchars($expertInitials) ?>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:block">
                    <h4 class="text-xs font-bold text-slate-900 leading-none flex items-center gap-1">
                        <?= htmlspecialchars($expertUser['username']) ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-slate-400 transition-transform duration-200" id="dropdownArrow">
                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </h4>
                    <span class="text-[10px] text-emerald-600 font-bold mt-0.5 block"><?= htmlspecialchars($expertUser['role']) ?></span>
                </div>
            </button>

            <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-slate-100/80 py-1.5 z-50 opacity-0 scale-95 transition-all duration-200 origin-top-right">
                <div class="px-3.5 py-2 border-b border-slate-50">
                    <p class="text-[11px] text-slate-400 font-medium">Signed in as</p>
                    <p class="text-xs font-bold text-slate-800 truncate"><?= htmlspecialchars($expertUser['username']) ?></p>
                </div>
                <a href="#" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    My Profile Details
                </a>
                <div class="border-t border-slate-50 my-1"></div>
                <a href="<?= BASE_URL ?>/logout" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h6a2.25 2.25 0 0 1-2.25-2.25V15m-10.5-6L1.5 12m0 0l3.75 3.75M1.5 12h11.25" />
                    </svg>
                    Logout System
                </a>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('profileDropdownContainer');
    const trigger = document.getElementById('profileDropdownTrigger');
    const menu = document.getElementById('profileDropdownMenu');
    const arrow = document.getElementById('dropdownArrow');

    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        const isHidden = menu.classList.contains('hidden');
        if (isHidden) {
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('opacity-0', 'scale-95');
                menu.classList.add('opacity-100', 'scale-100');
                if (arrow) arrow.classList.add('rotate-180');
            }, 10);
        } else {
            closeMenu();
        }
    });

    document.addEventListener('click', (e) => {
        if (!container.contains(e.target)) {
            closeMenu();
        }
    });

    function closeMenu() {
        menu.classList.remove('opacity-100', 'scale-100');
        menu.classList.add('opacity-0', 'scale-95');
        if (arrow) arrow.classList.remove('rotate-180');
        setTimeout(() => {
            menu.classList.add('hidden');
        }, 200);
    }
});
</script>
