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

        <a href="<?= BASE_URL ?>/logout" class="inline-flex items-center gap-2 rounded-xl border border-rose-100 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-600 transition-colors hover:bg-rose-100" aria-label="Logout">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="hidden sm:inline">Logout</span>
        </a>

        <div class="relative group">
            <button class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-slate-50 transition text-left">
                <div class="w-8 h-8 rounded-lg bg-slate-100 overflow-hidden border border-slate-200">
                    <img src="https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&q=80&w=150" alt="Profile avatar" class="w-full h-full object-cover">
                </div>
                <div class="hidden sm:block">
                    <h4 class="text-xs font-bold text-slate-900 leading-none">Dr. Khine Mar</h4>
                    <span class="text-[10px] text-emerald-600 font-bold mt-0.5 block">Plant Pathologist</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
            </button>
        </div>
    </div>
</header>