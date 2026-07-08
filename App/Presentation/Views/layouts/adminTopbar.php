<header class="fixed top-0 right-0 left-0 lg:left-72 h-20 bg-white border-b border-slate-100 flex items-center justify-between px-4 sm:px-8 shadow-sm z-40 select-none">
    
    <div class="flex items-center gap-4">
        <button id="toggleSidebarBtn" class="p-2 text-slate-500 hover:bg-slate-50 rounded-xl lg:hidden focus:outline-none focus:ring-2 focus:ring-[#15803D]/20">
            <svg xmlns="http://www.w3.org/2000/xl" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight leading-none mb-1">Welcome back, Admin! 👏</h2>
            <p class="text-[11px] sm:text-xs text-slate-400">Here's what's happening in the system today.</p>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-4 relative">

        <button class="p-2 sm:p-2.5 text-slate-500 hover:bg-slate-50 rounded-xl relative transition-colors focus:outline-none">
            <span class="absolute top-2.5 right-2.5 w-1.5 h-1.5 bg-[#15803D] rounded-full ring-2 ring-white"></span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </button>

        <div class="h-8 w-[1px] bg-slate-100 hidden sm:block"></div>

        <a href="<?= BASE_URL ?>/logout" class="inline-flex items-center gap-2 rounded-xl border border-rose-100 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-600 transition-colors hover:bg-rose-100" aria-label="Logout">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="hidden sm:inline">Logout</span>
        </a>

        <div class="relative">
            <button id="profileMenuDropdownBtn" class="flex items-center gap-2 sm:gap-3 p-1 sm:pr-2 rounded-xl hover:bg-slate-50 transition-colors duration-150 focus:outline-none">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-green-50 text-[#15803D] font-bold text-xs sm:text-sm flex items-center justify-center border border-green-100/30 uppercase shrink-0">
                    AD
                </div>
                <div class="hidden sm:block text-left leading-none">
                    <span class="text-sm font-bold text-slate-800 block">Administrator</span>
                    <span class="text-[10px] text-[#15803D] font-bold tracking-wide uppercase mt-0.5 block">Admin</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="profileDropdownWindow" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100/80 p-2 hidden animate__animated animate__fadeInUp animate__faster z-50">
                <div class="px-4 py-2.5 border-b border-slate-50 text-left">
                    <span class="block text-[11px] font-semibold text-slate-400 tracking-wide uppercase">Signed in as</span>
                    <span class="block text-sm font-bold text-slate-700 truncate">Administrator</span>
                </div>
                <div class="py-1">
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-xl transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        My Profile Details
                    </a>
                    <a href="<?= BASE_URL ?>/logout" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50/50 rounded-xl transition-colors mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout System
                    </a>
                </div>
            </div>
        </div>

    </div>
</header>