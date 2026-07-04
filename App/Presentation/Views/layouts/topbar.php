<?php
// Securely ensure the user session state exists before parsing data
$currentUser = $_SESSION['user'] ?? [
    'username' => 'U Aung',
    'role' => 'Farmer',
    'avatar' => null // falls back to initials if no file uploaded
];
?>
<header class="font-inter bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-40 px-4 sm:px-6 py-3.5 flex items-center justify-between lg:justify-end antialiased text-[#1a1a1a] w-full min-h-[73px]">
    
    <!-- Mobile Hamburger Switch -->
    <button id="mobileSidebarToggle" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-green-50 hover:text-[#2e7d32] transition-colors focus:outline-none" aria-label="Toggle Navigation Menu">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="flex items-center gap-3 sm:gap-4">
        
        <!-- Notifications Badge Layout Slot -->
        <a href="?page=notifications" class="relative w-9 h-9 rounded-xl hover:bg-green-50/80 border border-gray-100 transition-colors flex items-center justify-center text-[#555] group" aria-label="View Notifications">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:text-[#2e7d32] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
        </a>

        <!-- PROFILE TRIGGER CONTAINER DROPDOWN -->
        <div class="relative pl-2.5 border-l border-gray-200" id="profileDropdownContainer">
            <button id="profileDropdownTrigger" class="flex items-center gap-2.5 cursor-pointer group select-none focus:outline-none text-left">
                <!-- Rounded profile view circle container -->
                <div class="w-9 h-9 rounded-full overflow-hidden bg-[#eaf3e9] border border-green-200/60 flex items-center justify-center text-[#2e7d32] font-bold text-xs transition-all group-hover:border-[#2e7d32] shrink-0">
                    <?php if (!empty($currentUser['avatar'])): ?>
                        <img src="<?= BASE_URL . $currentUser['avatar'] ?>" alt="Profile" class="w-full h-full object-cover">
                    <?php else: ?>
                        <?= strtoupper(substr($currentUser['username'], 0, 2)) ?>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:block">
                    <span class="block text-xs font-bold text-[#111] tracking-tight group-hover:text-[#2e7d32] transition-colors leading-none flex items-center gap-1">
                        <?= htmlspecialchars($currentUser['username']) ?> 
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200 group-focus:rotate-180" id="dropdownArrow">
                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span class="block text-[9px] text-[#2e7d32] font-bold uppercase tracking-wider mt-1"><?= htmlspecialchars($currentUser['role']) ?></span>
                </div>
            </button>

            <!-- TRENDING: Modern Floating Popover Dropdown (Animated Menu Container) -->
            <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2.5 w-52 bg-white rounded-xl shadow-xl border border-gray-100/80 py-1.5 z-50 transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                <div class="px-3.5 py-2 border-b border-gray-50">
                    <p class="text-[11px] text-slate-400 font-medium">Signed in as</p>
                    <p class="text-xs font-bold text-slate-800 truncate"><?= htmlspecialchars($currentUser['username']) ?></p>
                </div>
                
                <a href="<?= BASE_URL ?>/profile" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-green-50 hover:text-[#2e7d32] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    My Profile Details
                </a>

                <div class="border-t border-gray-50 my-1"></div>
                
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
// Micro-interaction UI handling for the topbar profile settings
document.addEventListener('DOMContentLoaded', () => {
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
                if(arrow) arrow.classList.add('rotate-180');
            }, 10);
        } else {
            closeMenu();
        }
    });

    document.addEventListener('click', (e) => {
        if (!document.getElementById('profileDropdownContainer').contains(e.target)) {
            closeMenu();
        }
    });

    function closeMenu() {
        menu.classList.remove('opacity-100', 'scale-100');
        menu.classList.add('opacity-0', 'scale-95');
        if(arrow) arrow.classList.remove('rotate-180');
        setTimeout(() => {
            menu.classList.add('hidden');
        }, 200);
    }
});
</script>